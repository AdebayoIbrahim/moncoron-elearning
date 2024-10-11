#!/usr/bin/env bash

# Exit immediately if a command exits with a non-zero status
set -e

# Ensure the script is running in the right directory
cd /app

echo "Running composer"
# Install Prestissimo for faster Composer installs (optional)
composer global require hirak/prestissimo

# Install Composer dependencies
echo "Installing Composer dependencies..."
composer install --no-dev --prefer-dist --no-scripts --no-progress --no-suggest

# Generating application key (uncomment if needed)
# echo "Generating application key..."
# php artisan key:generate --force

echo "Clearing application cache..."
php artisan cache:clear

echo "Clearing configuration cache..."
php artisan config:clear

echo "Clearing route cache..."
php artisan route:clear

echo "Caching configuration..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Running migrations..."
php artisan migrate --force

echo "Deployment complete!"
