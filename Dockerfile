# Use PHP 8.3 with Nginx and PHP-FPM
FROM php:8.3-fpm

# Install necessary PHP extensions and Nginx
RUN apt-get update && \
    apt-get install -y nginx libpng-dev libjpeg-dev libfreetype6-dev curl && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd pdo pdo_mysql && \
    rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/bin --filename=composer && \
    chmod +x /usr/bin/composer

# Copy application files to the container
COPY . .

# Install Composer dependencies with verbose output for debugging
RUN composer install --no-dev --no-autoloader -vvv && composer dump-autoload -vvv



# Image config
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

# Laravel config
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr

# Allow composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER 1

# Copy Nginx configuration file (make sure you have a nginx.conf in the same directory)
COPY ./conf/nginx/nginx-site.conf /etc/nginx/sites-available/default

# Expose port 80 for web traffic
EXPOSE 80

# Start Nginx and PHP-FPM
CMD service nginx start && php-fpm
