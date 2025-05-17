#!/bin/sh

set -x # Enable debugging

echo "Starting entrypoint script..."

# Ensure storage and bootstrap/cache directories are writable
echo "Setting permissions for storage and bootstrap/cache..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Create .env if it doesn't exist
if [ ! -f /var/www/html/.env ]; then
    echo "Creating .env file from .env.example..."
    cp /var/www/html/.env.example /var/www/html/.env
fi

# Ensure .env is writable
chmod 666 /var/www/html/.env

# Run Composer install
echo "Running composer install..."
composer install --ignore-platform-reqs --no-interaction --optimize-autoloader

# Dump autoload files
echo "Dumping autoload files..."
composer dump-autoload

# Generate application key
echo "Generating application key..."
php artisan key:generate

# Clear and cache config
echo "Clearing and caching config..."
php artisan config:clear

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Passport keys generation
echo "Generating Passport keys..."
php artisan passport:keys --force

# Optimize the application
echo "Optimizing the application..."
php artisan optimize

# Start Supervisor
/usr/bin/supervisord -c /etc/supervisor/supervisord.conf
