#!/usr/bin/env bash

set -e  # Exit immediately if a command exits with a non-zero status

# Log function to track the output
log() {
  echo "[$(date +'%Y-%m-%d %H:%M:%S')] $1"
}

# Move to the application directory
log "Navigating to /app directory"
cd /app || { log "Failed to change directory to /app"; exit 1; }

# Cache the application configuration
log "Caching config..."
php artisan config:cache || { log "Config cache failed"; exit 1; }

# Cache the routes
log "Caching routes..."
php artisan route:cache || { log "Route cache failed"; exit 1; }

if [ ! -L /app/public/storage ]; then
    echo "Attempting to link storage"
    php artisan storage:link
else
    echo "Storage link already exists."
fi

log "Deploy script finished successfully"
