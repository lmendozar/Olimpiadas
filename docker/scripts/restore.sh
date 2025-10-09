#!/bin/bash
# Restore script for Olimpiadas Management System

if [ -z "$1" ]; then
    echo "❌ Error: No backup file specified"
    echo "Usage: ./restore.sh db_backup_20241009_120000.sql"
    echo ""
    echo "Available backups:"
    ls -1 ./backups/*.sql 2>/dev/null || echo "No backups found"
    exit 1
fi

BACKUP_FILE=$1

if [ ! -f "./backups/$BACKUP_FILE" ]; then
    echo "❌ Error: Backup file not found: ./backups/$BACKUP_FILE"
    exit 1
fi

# Load environment variables
source .env

echo "🏅 Olimpiadas Restore Script"
echo "============================"
echo "Backup file: $BACKUP_FILE"
echo ""

read -p "⚠️  This will OVERWRITE the current database. Continue? (yes/no): " confirm

if [ "$confirm" != "yes" ]; then
    echo "Restore cancelled."
    exit 0
fi

echo "📥 Restoring database..."
docker-compose exec -T mysql mysql \
    -u root \
    -p${DB_ROOT_PASSWORD} \
    ${DB_DATABASE} < "./backups/$BACKUP_FILE"

if [ $? -eq 0 ]; then
    echo "✅ Database restored successfully!"
    echo ""
    echo "🔄 Clearing application cache..."
    docker-compose exec app php artisan optimize:clear
    echo "✅ Done!"
else
    echo "❌ Database restore failed!"
    exit 1
fi

