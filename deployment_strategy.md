# Grand Line Cards - Deployment Strategy

## Overview
This document outlines the strategy for deploying the Grand Line Cards application to a production environment.

## Infrastructure
- **Provider**: DigitalOcean / AWS / Linode (VPS).
- **OS**: Ubuntu 24.04 LTS.
- **Web Server**: Nginx (Reverse Proxy).
- **Application Server**: PHP 8.3 + FPM.
- **Database**: MySQL 8.0 (Managed or Local).
- **Queue Worker**: Supervisor (Redis driver).
- **Cache**: Redis.

## Deployment Steps (Manual/Scripted)

### 1. Server Provisioning
```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y nginx php8.3-fpm php8.3-cli php8.3-mysql php8.3-xml php8.3-curl git unzip supervisor redis-server mysql-server
```

### 2. Application Setup
```bash
cd /var/www
git clone https://github.com/your-repo/grand-line-cards.git
cd grand-line-cards
composer install --no-dev --optimize-autoloader
npm install && npm run build
cp .env.example .env
php artisan key:generate
php artisan storage:link
php artisan migrate --force
```

### 3. Nginx Configuration
Create `/etc/nginx/sites-available/grand-line-cards`:
```nginx
server {
    listen 80;
    server_name grandlinecards.com;
    root /var/www/grand-line-cards/public;

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
    }
}
```

### 4. Supervisor Configuration (Queues)
Create `/etc/supervisor/conf.d/glc-worker.conf`:
```ini
[program:glc-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/grand-line-cards/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/grand-line-cards/storage/logs/worker.log
```

### 5. SSL (Let's Encrypt)
```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d grandlinecards.com
```

## CI/CD Pipeline (GitHub Actions)
- **On Push to Main**:
    - Run Tests (`php artisan test`).
    - Run Linting.
    - If passed, SSH to server and run `git pull`, `composer install`, `php artisan migrate`, `npm run build`.
