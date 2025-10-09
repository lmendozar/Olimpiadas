@echo off
echo ============================================
echo   Olimpiadas - Docker Deployment
echo ============================================
echo.

REM Check if .env exists
if not exist .env (
    echo WARNING: .env file not found!
    echo.
    echo Creating .env from template...
    copy env.docker.example .env
    echo.
    echo IMPORTANT: Edit .env file and configure:
    echo   - DB_PASSWORD
    echo   - DB_ROOT_PASSWORD
    echo   - GITHUB_REPO
    echo   - APP_URL
    echo.
    echo Then run this script again.
    pause
    exit /b 1
)

echo [1/4] Stopping existing containers...
docker-compose down

echo.
echo [2/4] Building Docker images...
docker-compose build --no-cache

echo.
echo [3/4] Starting services...
docker-compose up -d

echo.
echo [4/4] Waiting for services...
timeout /t 10 /nobreak >nul

echo.
echo ============================================
echo   DEPLOYMENT COMPLETED!
echo ============================================
echo.
echo Access points:
echo   Application:  http://localhost
echo   MySQL:        localhost:3306
echo.
echo Default credentials:
echo   Email: admin@olimpiadas.com
echo   Password: password
echo.
echo Useful commands:
echo   View logs:    docker-compose logs -f
echo   Stop:         docker-compose down
echo   Restart:      docker-compose restart
echo.
pause

