# Grand Line Cards - Deployment Guide

## 1. Prerequisites
-   **VPS**: Ubuntu 22.04 LTS (minimum 2GB RAM, 2 vCPUs).
-   **Domain**: `grandlinecards.com` pointed to VPS IP.
-   **Email Service**: AWS SES, Mailgun, or similar for transactional emails.

## 2. Server Setup (Docker Production)

### Step 1: Install Docker
```bash
curl -fsSL https://get.docker.com -o get-docker.sh
sh get-docker.sh
usermod -aG docker $USER
```

### Step 2: Clone Repository
```bash
git clone https://github.com/your-repo/grand-line-cards.git
cd grand-line-cards/GrandLineCards-Web
```

### Step 3: Environment Configuration
Copy the production example and configure secrets:
```bash
cp .env.example .env
nano .env
```
**Critical Variables**:
-   `APP_ENV=production`
-   `APP_DEBUG=false`
-   `APP_URL=https://grandlinecards.com`
-   `OPENAI_API_KEY=sk-...` (For translation/redaction)

### Step 4: Build & Launch
Use Laravel Sail (or raw docker-compose) for production.
```bash
./vendor/bin/sail up -d --build
```
*Note: In production, consider using a dedicated `docker-compose.prod.yml` with optimized Nginx/PHP-FPM images.*

### Step 5: Database & Assets
```bash
./vendor/bin/sail artisan migrate --force
./vendor/bin/sail artisan storage:link
./vendor/bin/sail artisan config:cache
./vendor/bin/sail artisan route:cache
./vendor/bin/sail artisan view:cache
```

## 3. SSL Configuration (Caddy/Traefik or Nginx)
We recommend using **Caddy** as a reverse proxy for automatic HTTPS.

**Caddyfile**:
```caddy
grandlinecards.com {
    reverse_proxy localhost:80
}
```

## 4. Updates / CI/CD
To update the application:
1.  `git pull origin main`
2.  `./vendor/bin/sail up -d --build`
3.  `./vendor/bin/sail artisan migrate --force`
