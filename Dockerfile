FROM php:8.3-fpm

# Install necessary PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Install Nginx
RUN apt-get update && \
    apt-get install -y nginx && \
    rm -rf /var/lib/apt/lists/*

# Copy Nginx configuration
COPY ./nginx.conf /etc/nginx/sites-available/default

# Copy your application files
COPY ./your-laravel-app /var/www/html

# Set the working directory
WORKDIR /var/www/html

# Expose port 80
EXPOSE 80

# Start PHP-FPM and Nginx
CMD service nginx start && php-fpm
