#!/bin/bash
# Quick start script for Docker deployment

echo "🏅 Olimpiadas Management System - Docker Deployment"
echo "===================================================="
echo ""

# Check if .env exists
if [ ! -f .env ]; then
    echo "⚠️  .env file not found!"
    echo ""
    echo "Creating .env from template..."
    cp env.docker.example .env
    echo "✅ .env created!"
    echo ""
    echo "📝 IMPORTANT: Edit .env file and configure:"
    echo "   - DB_PASSWORD"
    echo "   - DB_ROOT_PASSWORD"
    echo "   - GITHUB_REPO (your repository URL)"
    echo "   - APP_URL (your domain)"
    echo ""
    echo "Then run this script again."
    exit 1
fi

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
    echo "❌ Docker is not running!"
    echo "Please start Docker and try again."
    exit 1
fi

echo "1️⃣  Stopping existing containers..."
docker-compose down

echo ""
echo "2️⃣  Building Docker images..."
docker-compose build --no-cache

echo ""
echo "3️⃣  Starting services..."
docker-compose up -d

echo ""
echo "4️⃣  Waiting for services to be ready..."
sleep 10

echo ""
echo "5️⃣  Checking service health..."
docker-compose ps

echo ""
echo "✅ Deployment completed!"
echo ""
echo "📍 Access points:"
echo "   Application:  http://localhost:${APP_PORT:-80}"
echo "   MySQL:        localhost:3306"
echo ""
echo "🔐 Default credentials:"
echo "   Email: admin@olimpiadas.com"
echo "   Password: password"
echo ""
echo "📋 Useful commands:"
echo "   View logs:    docker-compose logs -f"
echo "   Stop:         docker-compose down"
echo "   Restart:      docker-compose restart"
echo ""

