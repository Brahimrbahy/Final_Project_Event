# Deployment Guide

## Pre-Deployment Checklist

### Environment Configuration
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure production database credentials
- [ ] Set up Stripe production keys
- [ ] Configure mail settings (SMTP/SendGrid/etc.)
- [ ] Set proper `APP_URL`

### Security
- [ ] Generate new `APP_KEY` for production
- [ ] Configure HTTPS/SSL certificates
- [ ] Set up proper file permissions
- [ ] Configure firewall rules
- [ ] Enable rate limiting
- [ ] Set up backup strategy

### Performance
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Run `php artisan view:cache`
- [ ] Run `npm run build` for production assets
- [ ] Configure Redis/Memcached for caching
- [ ] Set up queue workers

### Database
- [ ] Run migrations: `php artisan migrate --force`
- [ ] Seed production data if needed
- [ ] Set up database backups
- [ ] Configure database connection pooling

## Server Requirements

### Minimum Requirements
- PHP 8.1+
- MySQL 8.0+ / PostgreSQL 13+ / SQLite 3.8.8+
- Nginx 1.18+ / Apache 2.4+
- Composer 2.0+
- Node.js 16+ (for asset compilation)

### PHP Extensions
- BCMath
- Ctype
- Fileinfo
- JSON
- Mbstring
- OpenSSL
- PDO
- Tokenizer
- XML
- GD (for image processing)
- Intl (for internationalization)

## Deployment Steps

### 1. Server Setup
```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install PHP and extensions
sudo apt install php8.1-fpm php8.1-mysql php8.1-xml php8.1-mbstring php8.1-curl php8.1-zip php8.1-gd php8.1-intl

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs
```

### 2. Application Deployment
```bash
# Clone repository
git clone <repository-url> /var/www/event-management
cd /var/www/event-management

# Install dependencies
composer install --optimize-autoloader --no-dev
npm install
npm run build

# Set permissions
sudo chown -R www-data:www-data /var/www/event-management
sudo chmod -R 755 /var/www/event-management
sudo chmod -R 775 /var/www/event-management/storage
sudo chmod -R 775 /var/www/event-management/bootstrap/cache
```

### 3. Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database and other settings in .env
nano .env
```

### 4. Database Setup
```bash
# Run migrations
php artisan migrate --force

# Create storage link
php artisan storage:link

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 5. Web Server Configuration

#### Nginx Configuration
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/event-management/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### 6. Queue Workers (Optional)
```bash
# Install supervisor
sudo apt install supervisor

# Create supervisor configuration
sudo nano /etc/supervisor/conf.d/event-management-worker.conf
```

Supervisor configuration:
```ini
[program:event-management-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/event-management/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/event-management/storage/logs/worker.log
stopwaitsecs=3600
```

### 7. SSL Certificate (Let's Encrypt)
```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx

# Obtain SSL certificate
sudo certbot --nginx -d your-domain.com

# Auto-renewal
sudo crontab -e
# Add: 0 12 * * * /usr/bin/certbot renew --quiet
```

## Monitoring & Maintenance

### Log Monitoring
- Monitor Laravel logs: `/var/www/event-management/storage/logs/`
- Monitor web server logs: `/var/log/nginx/` or `/var/log/apache2/`
- Set up log rotation

### Backup Strategy
```bash
# Database backup script
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
mysqldump -u username -p database_name > /backups/db_backup_$DATE.sql
find /backups -name "db_backup_*.sql" -mtime +7 -delete
```

### Performance Monitoring
- Set up application monitoring (New Relic, Datadog, etc.)
- Monitor server resources (CPU, memory, disk)
- Set up uptime monitoring

### Security Updates
- Regularly update system packages
- Update Composer dependencies
- Monitor security advisories
- Implement security headers
- Regular security audits

## Troubleshooting

### Common Issues
1. **Permission errors**: Check file ownership and permissions
2. **Database connection**: Verify credentials and server accessibility
3. **Storage issues**: Ensure storage directory is writable
4. **Cache issues**: Clear all caches and regenerate
5. **Queue not processing**: Check queue worker status

### Useful Commands
```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Check application status
php artisan about

# Monitor queue
php artisan queue:monitor

# Check failed jobs
php artisan queue:failed
```
