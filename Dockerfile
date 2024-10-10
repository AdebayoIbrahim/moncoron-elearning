# Use PHP 8.3 with Nginx and PHP-FPM
FROM php:8.3-fpm

# Create a non-root user
RUN useradd -m user

# Set the user to the non-root user
USER user

# Install necessary PHP extensions and Nginx
RUN apt-get update && \
    apt-get install -y nginx libpng-dev libjpeg-dev libfreetype6-dev zip unzip git && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd pdo pdo_mysql mbstring exif pcntl bcmath && \
    rm -rf /var/lib/apt/lists/*

# Copy application files to the container
COPY . /app/

# Set the working directory
WORKDIR /app

# Copy the environment file
COPY .env /app/.env

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Ensure .env file is readable and artisan is executable
RUN chmod 755 /app/artisan && chmod 755 /app/.env

# Clear Composer cache
RUN composer clear-cache

# Install Composer dependencies, ignore platform requirements, and skip scripts
RUN composer install --no-dev --no-autoloader --ignore-platform-req=ext-exif --no-scripts && composer dump-autoload

# Re-enable Laravel scripts for post-install processes, such as package discovery
RUN composer run-script post-autoload-dump

# Install Node.js dependencies
RUN npm ci

# Copy Nginx configuration file
COPY ./conf/nginx/nginx-site.conf /etc/nginx/sites-available/default

# Expose port 80 for web traffic
EXPOSE 80

# Start Nginx and PHP-FPM
CMD service nginx start && php-fpm
