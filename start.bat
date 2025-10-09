@echo off
echo ============================================
echo   Iniciando Sistema de Olimpiadas
echo ============================================
echo.

echo Verificando que todo este listo...

if not exist .env (
    echo ERROR: Archivo .env no encontrado!
    echo Ejecuta primero: install.bat
    pause
    exit /b 1
)

if not exist vendor (
    echo ERROR: Dependencias no instaladas!
    echo Ejecuta primero: composer install
    pause
    exit /b 1
)

if not exist node_modules (
    echo ERROR: Dependencias de Node no instaladas!
    echo Ejecuta primero: npm install
    pause
    exit /b 1
)

echo.
echo Limpiando cache...
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo.
echo ============================================
echo   Servidor Iniciado!
echo ============================================
echo.
echo   URL: http://localhost:8000
echo.
echo   Credenciales:
echo     Admin: admin@olimpiadas.com / password
echo     Publico: publico@olimpiadas.com / password
echo.
echo   Presiona Ctrl+C para detener el servidor
echo.
echo ============================================
echo.

php artisan serve

