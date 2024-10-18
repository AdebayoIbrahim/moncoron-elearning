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
    apt-get install -y nginx libpng-dev libjpeg-dev libfreetype6-dev zip unzip git libonig-dev curl && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd pdo pdo_mysql mbstring exif pcntl bcmath && \
    rm -rf /var/lib/apt/lists/*

# Create necessary Nginx directories and set permissions
RUN mkdir -p /var/lib/nginx/tmp/client_body /var/log/nginx /var/lib/nginx/body && \
    chown -R user:user /var/lib/nginx /var/log/nginx /var/lib/nginx/tmp/client_body && \
    chmod -R 755 /var/lib/nginx /var/log/nginx /var/lib/nginx/tmp/client_body

# Install Node.js and npm
RUN curl -sL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy all project files to the container
COPY . /app

# Ensure the /app directory is writable by the non-root user
RUN chown -R user:user /app

# Switch to the non-root user for the next steps
USER user

# Ensure artisan is executable (after copying files)
RUN chmod 755 /app/artisan

# Switch back to root user to copy the deploy script
USER root

# Copy deploy script
COPY ./scripts/deploy.sh /usr/local/bin/deploy.sh

# Make sure the deploy script is executable
RUN chmod +x /usr/local/bin/deploy.sh

# Switch back to non-root user for running composer and other commands
USER user

# Clear Composer cache and install Composer dependencies
RUN composer clear-cache && \
    composer install --ignore-platform-reqs --prefer-dist --no-scripts --no-progress --no-suggest --no-interaction --no-dev --no-autoloader

# Run deploy script as non-root user
RUN /usr/local/bin/deploy.sh

# Generate optimized autoload files and run post-install scripts
RUN composer dump-autoload && composer run-script post-autoload-dump

# Install Node.js dependencies and build assets
RUN npm ci && npm run build

# Copy Nginx configuration file
COPY ./conf/nginx/nginx-site.conf /etc/nginx/sites-available/default

# Expose port 80 for web traffic
EXPOSE 80

# Switch back to root to start services
USER root

# Set permissions for storage and cache
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# Start Nginx and PHP-FPM
CMD ["sh", "-c", "nginx -g 'daemon off;' & php-fpm"]
