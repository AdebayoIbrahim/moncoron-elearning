# Use PHP 8.3 with Nginx and PHP-FPM
FROM php:8.3-fpm

# Create a non-root user and add it to the 'www-data' group
RUN useradd -m user && usermod -a -G www-data user

# Install necessary PHP extensions and Nginx
RUN apt-get update && \
    apt-get install -y nginx libpng-dev libjpeg-dev libfreetype6-dev && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd pdo pdo_mysql && \
    rm -rf /var/lib/apt/lists/*

# Switch to non-root user
USER user

# Set the working directory to /app
WORKDIR /app

# Copy application files to the container (use root privileges temporarily)
USER root
COPY . /app/

# Set ownership of the /app directory to the non-root user
RUN chown -R user:user /app

# Switch back to non-root user
USER user

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer

# Clear Composer cache
RUN composer clear-cache

# Install Composer dependencies (non-root user)
RUN composer install --no-dev --no-autoloader && composer dump-autoload

# Install Node.js dependencies (non-root user)
RUN npm ci

# Switch back to root for Nginx configuration
USER root

# Copy Nginx configuration file
COPY ./conf/nginx/nginx-site.conf /etc/nginx/sites-available/default

# Expose port 80 for web traffic
EXPOSE 80

# Switch back to non-root for running services
USER user

# Start Nginx and PHP-FPM
CMD service nginx start && php-fpm
