#!/bin/bash
# Backup script for Olimpiadas Management System

BACKUP_DIR="./backups"
DATE=$(date +%Y%m%d_%H%M%S)
DB_CONTAINER="olimpiadas_mysql"

# Load environment variables
source .env

echo "🏅 Olimpiadas Backup Script"
echo "=========================="
echo ""

# Create backup directory
mkdir -p $BACKUP_DIR

# Backup database
echo "📦 Backing up database..."
docker-compose exec -T mysql mysqldump \
    -u root \
    -p${DB_ROOT_PASSWORD} \
    ${DB_DATABASE} > "${BACKUP_DIR}/db_backup_${DATE}.sql"

if [ $? -eq 0 ]; then
    echo "✅ Database backup created: ${BACKUP_DIR}/db_backup_${DATE}.sql"
else
    echo "❌ Database backup failed!"
    exit 1
fi

# Backup storage
echo "📁 Backing up storage files..."
docker run --rm \
    -v olimpiadas_storage_data:/data \
    -v $(pwd)/${BACKUP_DIR}:/backup \
    alpine tar czf /backup/storage_backup_${DATE}.tar.gz /data

if [ $? -eq 0 ]; then
    echo "✅ Storage backup created: ${BACKUP_DIR}/storage_backup_${DATE}.tar.gz"
else
    echo "❌ Storage backup failed!"
fi

# Cleanup old backups (keep last 7 days)
echo "🧹 Cleaning old backups (keeping last 7 days)..."
find ${BACKUP_DIR} -name "*.sql" -mtime +7 -delete
find ${BACKUP_DIR} -name "*.tar.gz" -mtime +7 -delete

echo ""
echo "✅ Backup completed successfully!"
echo "📍 Location: ${BACKUP_DIR}"
echo ""

