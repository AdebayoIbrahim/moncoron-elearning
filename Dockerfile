# Use PHP 8.3 with Nginx and PHP-FPM
FROM php:8.3-fpm

# Create a non-root user
RUN useradd -m user

# Install necessary PHP extensions and Nginx
RUN apt-get update && \
    apt-get install -y nginx libpng-dev libjpeg-dev libfreetype6-dev && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd pdo pdo_mysql && \
    rm -rf /var/lib/apt/lists/*

# Switch to root to install Composer
USER root

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer

# Switch back to the non-root user
USER user

# Set the working directory
WORKDIR /app

# Copy application files to the container
COPY . /app/

# Clear Composer cache
RUN composer clear-cache

# Install Composer dependencies
RUN composer install --no-dev --no-autoloader && composer dump-autoload

# Install Node.js dependencies
RUN npm ci

# Copy Nginx configuration file
COPY ./conf/nginx/nginx-site.conf /etc/nginx/sites-available/default

# Expose port 80 for web traffic
EXPOSE 80

# Start Nginx and PHP-FPM
CMD service nginx start && php-fpm
