#!/usr/bin/env bash

set -e  # Exit immediately if a command exits with a non-zero status

# Log function to track the output
log() {
  echo "[$(date +'%Y-%m-%d %H:%M:%S')] $1"
}

# Move to the application directory
log "Navigating to /app directory"
cd /app || { log "Failed to change directory to /app"; exit 1; }

# Clear old cached data
log "Clearing old cache..."
php artisan view:clear || { log "View cache clear failed"; exit 1; }
php artisan config:clear || { log "Config cache clear failed"; exit 1; }
php artisan route:clear || { log "Route cache clear failed"; exit 1; }
php artisan cache:clear || { log "Application cache clear failed"; exit 1; }

# Cache the application configuration
log "Caching config..."
php artisan config:cache || { log "Config cache failed"; exit 1; }

# Cache the routes
log "Caching routes..."
php artisan route:cache || { log "Route cache failed"; exit 1; }

# Link storage if not already linked
if [ ! -L /app/public/storage ]; then
    log "Attempting to link storage"
    php artisan storage:link || { log "Storage link failed"; exit 1; }
else
    log "Storage link already exists."
fi

log "Deploy script finished successfully"
