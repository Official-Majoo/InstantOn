FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libpq-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libwebp-dev \
    libxpm-dev

# Configure and install GD extension with additional image format support
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-xpm

# Install PHP extensions
RUN docker-php-ext-install pdo_pgsql pdo_mysql mbstring exif pcntl bcmath gd zip

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files first
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install --no-dev --no-scripts --optimize-autoloader

# Copy application code
COPY . .

# Set permissions (more comprehensive)
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Create Apache virtual host config with error logging
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
    ErrorLog /dev/stderr\n\
    CustomLog /dev/stdout combined\n\
    LogLevel info\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Enable PHP error reporting
RUN echo 'display_errors = On\n\
log_errors = On\n\
error_log = /dev/stderr' > /usr/local/etc/php/conf.d/error-logging.ini

# Create improved startup script with error handling
RUN echo '#!/bin/bash\n\
set -e\n\
echo "=== Starting Laravel Application ==="\n\
echo "Clearing caches..."\n\
php artisan config:clear || echo "Config clear failed"\n\
php artisan cache:clear || echo "Cache clear failed"\n\
echo "Running migrations..."\n\
php artisan migrate --force || echo "Migration failed - continuing anyway"\n\
echo "Running seeders..."\n\
php artisan db:seed --force || echo "Seeding failed - continuing anyway"\n\
echo "Caching configuration for production..."\n\
php artisan config:cache || echo "Config cache failed"\n\
echo "Starting Apache web server..."\n\
exec apache2-foreground' > /usr/local/bin/start.sh

RUN chmod +x /usr/local/bin/start.sh

# Expose port
EXPOSE 80

# Use startup script
CMD ["/usr/local/bin/start.sh"]