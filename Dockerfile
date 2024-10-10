# Use PHP 8.3 with Nginx and PHP-FPM
FROM php:8.3-fpm

# Create a non-root user
RUN useradd -m user

# Set the working directory
WORKDIR /app

# Switch to root user for package installation
USER root

# Install necessary PHP extensions and Nginx, including oniguruma for mbstring
RUN apt-get update && \
    apt-get install -y nginx libpng-dev libjpeg-dev libfreetype6-dev zip unzip git libonig-dev && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd pdo pdo_mysql mbstring exif pcntl bcmath && \
    rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy all project files to the container
COPY . /app

# Ensure artisan is executable (after copying files)
RUN chmod 755 /app/artisan

# Clear Composer cache as root
RUN composer clear-cache

# Switch to the non-root user before running Composer commands
USER user

# Install Composer dependencies, ignore platform requirements, and skip scripts
RUN composer install --no-dev --no-autoloader --ignore-platform-req=ext-exif --no-scripts --no-progress --prefer-dist

# Run the dump-autoload command
RUN composer dump-autoload

# Re-enable Laravel scripts for post-install processes, such as package discovery
# Note: Uncomment the following line if you want to run it after verifying installation works.
# RUN composer run-script post-autoload-dump

# Switch back to root for further commands
USER root

# Install Node.js dependencies
RUN npm ci

# Copy Nginx configuration file
COPY ./conf/nginx/nginx-site.conf /etc/nginx/sites-available/default

# Switch back to the non-root user
USER user

# Expose port 80 for web traffic
EXPOSE 80

# Start Nginx and PHP-FPM
CMD service nginx start && php-fpm
