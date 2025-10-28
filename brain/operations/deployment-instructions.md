# Deployment Instructions

## Overview
Complete deployment guide for the Tea Backend platform including environment setup, configuration, and production deployment.

## Prerequisites

### System Requirements
- **PHP**: 8.3+
- **Composer**: 2.0+
- **MySQL**: 8.0+
- **Redis**: 6.0+
- **Node.js**: 18+ (for asset compilation)
- **Nginx/Apache**: For web server

### Required Services
- **AWS S3**: For file storage
- **Mailgun/SES**: For email notifications
- **Expo Push API**: For push notifications
- **Redis**: For caching and queues

## Environment Setup

### 1. Clone Repository
```bash
git clone <repository-url>
cd tea-backend
```

### 2. Install Dependencies
```bash
composer install --no-dev --optimize-autoloader
npm install && npm run build
```

### 3. Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Setup
```bash
php artisan migrate --force
php artisan db:seed
```

### 5. Storage Setup
```bash
php artisan storage:link
```

## Production Configuration

### Environment Variables
```env
APP_NAME="Tea Backend"
APP_ENV=production
APP_KEY=base64:...
APP_DEBUG=false
APP_URL=https://api.tea.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tea_backend
DB_USERNAME=tea_user
DB_PASSWORD=secure_password

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

AWS_ACCESS_KEY_ID=your_access_key
AWS_SECRET_ACCESS_KEY=your_secret_key
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=tea-backend-storage

MAILGUN_DOMAIN=your_domain
MAILGUN_SECRET=your_secret

EXPO_ACCESS_TOKEN=your_expo_token
```

### Nginx Configuration
```nginx
server {
    listen 80;
    server_name api.tea.com;
    root /var/www/tea-backend/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

## Deployment Process

### 1. Pre-deployment Checklist
- [ ] All tests passing
- [ ] Code coverage >80%
- [ ] Security scan completed
- [ ] Performance tests passed
- [ ] Database migrations ready
- [ ] Environment variables configured

### 2. Database Migration
```bash
php artisan migrate --force
php artisan db:seed --class=ProductionSeeder
```

### 3. Cache Optimization
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

### 4. Queue Configuration
```bash
php artisan horizon:install
php artisan horizon:publish
```

### 5. File Permissions
```bash
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

## Monitoring Setup

### Laravel Horizon
```bash
php artisan horizon:install
php artisan horizon:publish
```

### Log Configuration
```php
// config/logging.php
'channels' => [
    'stack' => [
        'driver' => 'stack',
        'channels' => ['single', 'slack'],
    ],
    'slack' => [
        'driver' => 'slack',
        'url' => env('LOG_SLACK_WEBHOOK_URL'),
        'username' => 'Tea Backend',
        'emoji' => ':boom:',
        'level' => 'error',
    ],
],
```

## Security Configuration

### SSL/TLS Setup
```bash
# Install Let's Encrypt
certbot --nginx -d api.tea.com
```

### Firewall Configuration
```bash
# UFW rules
ufw allow 22
ufw allow 80
ufw allow 443
ufw enable
```

### Rate Limiting
```php
// config/rate-limiting.php
'api' => [
    'max_attempts' => 60,
    'decay_minutes' => 1,
],
```

## Performance Optimization

### Redis Configuration
```bash
# /etc/redis/redis.conf
maxmemory 256mb
maxmemory-policy allkeys-lru
```

### MySQL Optimization
```sql
-- /etc/mysql/mysql.conf.d/mysqld.cnf
innodb_buffer_pool_size = 1G
innodb_log_file_size = 256M
query_cache_size = 64M
```

### PHP-FPM Configuration
```ini
; /etc/php/8.3/fpm/pool.d/www.conf
pm = dynamic
pm.max_children = 50
pm.start_servers = 5
pm.min_spare_servers = 5
pm.max_spare_servers = 35
```

## Backup Strategy

### Database Backup
```bash
#!/bin/bash
# /etc/cron.daily/tea-backup
mysqldump -u tea_user -p tea_backend > /backups/tea-$(date +%Y%m%d).sql
```

### File Backup
```bash
#!/bin/bash
# /etc/cron.daily/tea-files
rsync -av /var/www/tea-backend/storage/app/ /backups/files/
```

## Health Checks

### Application Health
```bash
curl -f http://api.tea.com/health || exit 1
```

### Database Health
```bash
mysql -u tea_user -p -e "SELECT 1" tea_backend || exit 1
```

### Redis Health
```bash
redis-cli ping || exit 1
```

## Rollback Plan

### Database Rollback
```bash
php artisan migrate:rollback --step=1
```

### Code Rollback
```bash
git checkout previous-commit
composer install --no-dev
php artisan config:cache
```

### File Rollback
```bash
rsync -av /backups/files/ /var/www/tea-backend/storage/app/
```

## Troubleshooting

### Common Issues
1. **Permission Errors**: Check file permissions
2. **Database Connection**: Verify credentials
3. **Redis Connection**: Check Redis service
4. **Queue Jobs**: Check Horizon status

### Log Locations
- **Application**: `/var/www/tea-backend/storage/logs/`
- **Nginx**: `/var/log/nginx/`
- **PHP-FPM**: `/var/log/php8.3-fpm.log`
- **MySQL**: `/var/log/mysql/`

### Performance Monitoring
- **Laravel Telescope**: For debugging
- **Laravel Horizon**: For queue monitoring
- **New Relic**: For application monitoring
- **Grafana**: For metrics visualization

## Success Criteria

### Deployment Success
- [ ] All services running
- [ ] Database connected
- [ ] Redis connected
- [ ] Queue processing
- [ ] File storage working
- [ ] Email sending
- [ ] Push notifications working

### Performance Targets
- **API Response Time**: <200ms
- **Database Query Time**: <50ms
- **Queue Job Processing**: <30 seconds
- **File Upload Time**: <5 seconds

### Security Checklist
- [ ] SSL/TLS enabled
- [ ] Firewall configured
- [ ] Rate limiting active
- [ ] Input validation working
- [ ] Authorization enforced
- [ ] Logging enabled