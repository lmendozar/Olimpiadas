#!/bin/bash
set -e

echo "🏅 Starting Olimpiadas Management System..."

# Create .env file from environment variables if it doesn't exist or is a directory
if [ -d "/var/www/html/.env" ]; then
    echo "⚠️  Removing .env directory..."
    rm -rf /var/www/html/.env
fi

if [ ! -f "/var/www/html/.env" ]; then
    echo "📝 Creating .env file from environment variables..."
    cat > /var/www/html/.env << EOF
APP_NAME="${APP_NAME:-Laravel}"
APP_ENV=${APP_ENV:-production}
APP_KEY=
APP_DEBUG=${APP_DEBUG:-false}
APP_URL=${APP_URL:-http://localhost}

LOG_CHANNEL=${LOG_CHANNEL:-stack}
LOG_LEVEL=${LOG_LEVEL:-info}

DB_CONNECTION=${DB_CONNECTION:-mysql}
DB_HOST=${DB_HOST:-mysql}
DB_PORT=${DB_PORT:-3306}
DB_DATABASE=${DB_DATABASE:-olimpiadas}
DB_USERNAME=${DB_USERNAME:-root}
DB_PASSWORD=${DB_PASSWORD:-}

CACHE_DRIVER=${CACHE_DRIVER:-file}
SESSION_DRIVER=${SESSION_DRIVER:-file}
SESSION_LIFETIME=${SESSION_LIFETIME:-120}
QUEUE_CONNECTION=${QUEUE_CONNECTION:-sync}
EOF
    chown www-data:www-data /var/www/html/.env
fi

# Wait for MySQL to be ready
echo "⏳ Waiting for MySQL to be ready..."
until php artisan db:show 2>/dev/null; do
    echo "   MySQL is unavailable - sleeping"
    sleep 2
done
echo "✅ MySQL is ready!"

# Generate APP_KEY if not set
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:=" ] || [ "$APP_KEY" = "" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
fi

# Run migrations
echo "🗄️  Running database migrations..."
php artisan migrate --force

# Seed database if empty (optional)
if [ "$DB_SEED" = "true" ]; then
    echo "🌱 Seeding database..."
    php artisan db:seed --force
fi

# Cache configuration for production
if [ "$APP_ENV" = "production" ]; then
    echo "⚡ Optimizing for production..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
fi

# Create storage link
echo "🔗 Creating storage symlink..."
php artisan storage:link || true

# Set proper permissions
echo "🔐 Setting permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "✅ Application ready!"
echo "🌐 Access the app at: http://localhost"
echo ""

# Execute the main container command
exec "$@"

