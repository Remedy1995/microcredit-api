FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    nginx \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev \
    libicu-dev \
    libxml2-dev \
    zip \
    libonig-dev \
    unzip \
    git \
    curl \
    libgd-dev

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath zip gd

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set environment variable to allow Composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER=1

# Copy application code
COPY . /var/www/html

# Install PHP dependencies using Composer
RUN composer install --no-dev --optimize-autoloader

# Copy Nginx configuration
COPY ./nginx.conf /etc/nginx/nginx.conf

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 80
EXPOSE 80

# Start PHP-FPM and Nginx
CMD ["sh", "-c", "php-fpm && nginx -g 'daemon off;'"]
