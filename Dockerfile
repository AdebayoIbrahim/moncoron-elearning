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

# Copy the .env file (or .env.example) into the container
COPY .env.example /app/.env

# Clear Composer cache
RUN composer clear-cache

# Install Composer dependencies, ignoring platform requirements
RUN composer install --ignore-platform-reqs --prefer-dist --no-scripts --no-progress --no-suggest --no-interaction --no-dev --no-autoloader

# Generate optimized autoload files and run post-install scripts
RUN composer dump-autoload && composer run-script post-autoload-dump

# Install Node.js dependencies
RUN npm ci

# Copy Nginx configuration file
COPY ./conf/nginx/nginx-site.conf /etc/nginx/sites-available/default

# Ensure Nginx and the necessary folders are accessible by the non-root user
RUN chown -R user:user /var/run/nginx /var/log/nginx

# Expose port 80 for web traffic
EXPOSE 80

# Start Nginx and PHP-FPM
CMD ["sh", "-c", "nginx -g 'daemon off;' & php-fpm"]
