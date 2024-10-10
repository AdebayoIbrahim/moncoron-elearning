# Use PHP 8.3 with FPM
FROM php:8.3-fpm

# Install necessary system packages
RUN apt-get update && \
    apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zlib1g-dev \
    git \
    zip \
    unzip && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd pdo pdo_mysql && \
    rm -rf /var/lib/apt/lists/*

# Copy Composer from its official image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set the working directory
WORKDIR /app

# Copy application files (including composer.json) to the container
COPY . /app/

# Clear Composer cache
RUN composer clear-cache

# Install Composer dependencies while ignoring platform requirements for missing extensions
RUN composer install --no-dev --ignore-platform-req=ext-exif && composer dump-autoload

# Expose port 80
EXPOSE 80

# Start PHP-FPM
CMD ["php-fpm"]
