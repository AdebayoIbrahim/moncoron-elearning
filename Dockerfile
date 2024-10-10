# Use PHP 8.3 with Nginx and PHP-FPM
FROM php:8.3-fpm

# Create a non-root user
RUN useradd -m user

# Set the working directory
WORKDIR /app

# Switch to root user for package installation
USER root

# Install necessary PHP extensions and Nginx
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

# Copy the .env.example file to .env
COPY .env.example /app/.env

# Ensure /app directory is writable by the non-root user
RUN chown -R user:user /app

# Switch to non-root user for safe execution
USER user

# Install Composer dependencies
RUN composer install --ignore-platform-reqs --prefer-dist --no-scripts --no-progress --no-suggest --no-interaction --no-dev --no-autoloader

# Generate optimized autoload files
RUN composer dump-autoload && composer run-script post-autoload-dump

# Install Node.js dependencies
RUN npm ci

# Build assets for production using Vite
RUN npm run build

# Switch back to root to start services
USER root

# Copy Nginx configuration file
COPY ./conf/nginx/nginx-site.conf /etc/nginx/sites-available/default

# Set necessary permissions on storage, cache, and public directories
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache /app/public

# Set appropriate permissions
RUN chmod -R 755 /app/public

# Expose port 80 for web traffic
EXPOSE 80

# Start Nginx and PHP-FPM
CMD ["sh", "-c", "nginx -g 'daemon off;' & php-fpm"]
