#!/bin/bash
set -e

echo "🏅 Starting Olimpiadas Management System..."

# Wait for MySQL to be ready
echo "⏳ Waiting for MySQL to be ready..."
until php artisan db:show 2>/dev/null; do
    echo "   MySQL is unavailable - sleeping"
    sleep 2
done
echo "✅ MySQL is ready!"

# Generate APP_KEY if not set
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:=" ]; then
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

