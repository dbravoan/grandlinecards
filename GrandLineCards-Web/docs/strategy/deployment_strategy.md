# Deployment Strategy: Grand Line Cards

## 1. Hosting & Infrastructure
- **Provider**: DigitalOcean or AWS Lightsail (Cost-effective scaling).
- **Server**: Ubuntu 22.04 LTS with Docker & Sail (or Supervisor/Nginx setup).
- **Database**: Managed MySQL 8.0 (for backups and stability) or local optimized MySQL.
- **Cache/Queue**: Redis (essential for queues and caching deck queries).
- **SSL**: Let's Encrypt via Certbot.

## 2. CI/CD Pipeline (GitHub Actions)
### Workflow Steps:
1.  **Test**: Run PHPUnit (Feature/Unit tests) on every push to `main`.
2.  **Build**: Compile assets with `npm run build`.
3.  **Deploy**:
    -   SSH into production server.
    -   `git pull` latest changes.
    -   `composer install --no-dev --optimize-autoloader`.
    -   `php artisan migrate --force`.
    -   `php artisan config:cache`, `route:cache`, `view:cache`.
    -   `npm run build` (or transfer build artifacts).
    -   Restart Queue Workers (`php artisan queue:restart`).

## 3. Environment Configuration (.env.production)
- `APP_ENV=production`
- `APP_DEBUG=false`
- `TELESCOPE_ENABLED=false` (unless debugging specific issue).
- Secure `APP_KEY` and Database Credentials.
- Mail Configuration (e.g., Mailgun/Resend) for Auth emails.

## 4. Backup Strategy
- **Database**: Daily automated backups (retained for 30 days).
- **Storage**: S3 Bucket for user-uploaded deck images (if applicable) or volume snapshots.

## 5. Monitoring
- **Error Tracking**: Sentry (integration via `sentry/sentry-laravel`).
- **Uptime**: UptimeRobot (monitor homepage and API health check).
- **Performance**: Laravel Pulse (since we are on Laravel 10/11) to monitor slow queries/jobs.
