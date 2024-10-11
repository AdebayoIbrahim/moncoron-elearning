#!/usr/bin/env bash

set -e  # Exit immediately if a command exits with a non-zero status

# Log function to track the output
log() {
  echo "[$(date +'%Y-%m-%d %H:%M:%S')] $1"
}

# Move to the application directory
log "Navigating to /app directory"
cd /app || { log "Failed to change directory to /app"; exit 1; }

log "Running composer"
composer global require hirak/prestissimo || { log "Composer global require failed"; exit 1; }
composer install --no-dev --working-dir=/app || { log "Composer install failed"; exit 1; }

log "Caching config..."
php artisan config:cache || { log "Config cache failed"; exit 1; }

log "Caching routes..."
php artisan route:cache || { log "Route cache failed"; exit 1; }

log "Deploy script finished successfully"
