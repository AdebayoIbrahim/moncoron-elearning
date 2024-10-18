# Use PHP 8.3 with Nginx and PHP-FPM
FROM php:8.3-fpm AS process_initial

#request environment-agrs
ARG APP_NAME
ARG APP_URL
ARG APP_KEY
ARG AGORA_APP_ID
ARG VITE_AGORA_APP_ID
ARG AGORA_APP_CERTIFICATE
ARG SESSION_DRIVER
ARG SESSION_LIFETIME
ARG SESSION_ENCRYPT
ARG SESSION_PATH
ARG SESSION_DOMAIN
ARG SESSION_SECURE_COOKIE
ARG CACHE_STORE
ARG BROADCAST_CONNECTION
ARG MAIL_MAILER
ARG MAIL_PORT
ARG MAIL_HOST
ARG MAIL_USERNAME
ARG MAIL_PASSWORD
ARG MAIL_ENCRYPTION
ARG MAIL_FROM_ADDRESS
ARG PAYSTACK_PUBLIC_KEY
ARG PAYSTACK_SECRET_KEY
ARG PAYSTACK_PAYMENT_URL
ARG MERCHANT_EMAIL
ARG DB_CONNECTION
ARG DB_HOST
ARG DB_PORT
ARG DB_DATABASE
ARG DB_USERNAME
ARG DB_PASSWORD
ARG PUSHER_APP_ID
ARG PUSHER_APP_KEY                             
ARG PUSHER_APP_SECRET
ARG PUSHER_APP_CLUSTER
ARG BROADCAST_DRIVER

#SETTINGAPPENVIRONMENTS
# hard-coded-envs
ENV APP_DEBUG=false
ENV APP_TIMEZONE=UTC
ENV APP_ENV=production
ENV APP_FALLBACK_LOCALE=en
ENV APP_FAKER_LOCALE=en_US
ENV BCRYPT_ROUNDS=12
ENV LOG_CHANNEL=stack
ENV LOG_STACK=single
ENV LOG_DEPRECATIONS_CHANNEL=null
ENV LOG_LEVEL=debug
ENV APP_MAINTENANCE_DRIVER=file
ENV APP_MAINTENANCE_STORE=database
ENV MAIL_FROM_NAME=$APP_NAME
ENV VITE_APP_NAME=$APP_NAME
#secrete-variables
ENV APP_NAME=$APP_NAME
ENV APP_URL=$APP_URL
ENV APP_KEY=$APP_KEY
ENV AGORA_APP_ID=$AGORA_APP_ID
ENV VITE_AGORA_APP_ID=$AGORA_APP_ID
ENV AGORA_APP_CERTIFICATE=$AGORA_APP_CERTIFICATE 
ENV SESSION_DRIVER=$SESSION_DRIVER 
ENV SESSION_LIFETIME=$SESSION_LIFETIME
ENV SESSION_ENCRYPT=$SESSION_ENCRYPT
ENV SESSION_PATH=$SESSION_PATH
ENV SESSION_DOMAIN=$SESSION_DOMAIN
ENV SESSION_SECURE_COOKIE=$SESSION_SECURE_COOKIE
ENV CACHE_STORE=$CACHE_STORE
ENV BROADCAST_CONNECTION=$BROADCAST_CONNECTION
ENV MAIL_MAILER=$MAIL_MAILER
ENV MAIL_PORT=$MAIL_PORT
ENV MAIL_HOST=$MAIL_HOST
ENV MAIL_USERNAME=$MAIL_USERNAME
ENV MAIL_PASSWORD=$MAIL_PASSWORD
ENV MAIL_ENCRYPTION=$MAIL_ENCRYPTION
ENV MAIL_FROM_ADDRESS=$MAIL_FROM_ADDRESS
ENV PAYSTACK_PUBLIC_KEY=$PAYSTACK_PUBLIC_KEY
ENV PAYSTACK_SECRET_KEY=$PAYSTACK_SECRET_KEY
ENV PAYSTACK_PAYMENT_URL=$PAYSTACK_PAYMENT_URL
ENV MERCHANT_EMAIL=$MERCHANT_EMAIL
ENV DB_CONNECTION=$DB_CONNECTION
ENV DB_HOST=$DB_HOST
ENV DB_PORT=$DB_PORT
ENV DB_DATABASE=$DB_DATABASE
ENV DB_USERNAME=$DB_USERNAME
ENV DB_PASSWORD=$DB_PASSWORD
ENV PUSHER_APP_ID=$PUSHER_APP_ID
ENV PUSHER_APP_KEY=$PUSHER_APP_KEY                             
ENV PUSHER_APP_SECRET=$PUSHER_APP_SECRET
ENV PUSHER_APP_CLUSTER=$PUSHER_APP_CLUSTER
ENV BROADCAST_DRIVER=$BROADCAST_DRIVER
ENV MIX_PUSHER_APP_KEY=$PUSHER_APP_KEY
ENV MIX_PUSHER_APP_CLUSTER=$PUSHER_APP_CLUSTER


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

# Generate optimized autoload files and run post-install scripts
RUN composer dump-autoload && composer run-script post-autoload-dump

# Run deploy script as non-root user
RUN /usr/local/bin/deploy.sh

# Install Node.js dependencies and build assets
RUN npm ci && npm run build


# Copy Nginx configuration file
COPY ./conf/nginx/nginx-site.conf /etc/nginx/sites-available/default

# Run deploy script as non-root user
RUN /usr/local/bin/deploy.sh

# Expose port 80 for web traffic
EXPOSE 80

# Switch back to root to start services
USER root

# Set permissions for storage and cache
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# Start Nginx and PHP-FPM
CMD ["sh", "-c", "nginx -g 'daemon off;' & php-fpm"]
