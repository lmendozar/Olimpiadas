@echo off
echo ============================================
echo   Sistema de Gestion de Olimpiadas
echo   Script de Instalacion Automatica
echo ============================================
echo.

echo [1/9] Limpiando cache...
php artisan optimize:clear 2>nul

echo [2/9] Regenerando autoload...
composer dump-autoload

echo [3/9] Verificando archivo .env...
if not exist .env (
    echo ERROR: Archivo .env no encontrado!
    echo Por favor crea el archivo .env con las credenciales de MySQL
    echo Presiona cualquier tecla para abrir notepad y crearlo...
    pause >nul
    notepad .env
    echo Por favor guarda el archivo y ejecuta este script nuevamente
    pause
    exit /b 1
)

echo [4/9] Generando clave de aplicacion...
php artisan key:generate

echo [5/9] Limpiando cache de configuracion...
php artisan config:clear
php artisan cache:clear

echo [6/9] Ejecutando migraciones...
php artisan migrate --force

if %errorlevel% neq 0 (
    echo.
    echo ERROR: Las migraciones fallaron!
    echo Verifica que:
    echo   - MySQL este corriendo en XAMPP
    echo   - La base de datos 'olimpiadas' exista
    echo   - Las credenciales en .env sean correctas
    echo.
    pause
    exit /b 1
)

echo [7/9] Poblando base de datos con datos de ejemplo...
php artisan db:seed

if %errorlevel% neq 0 (
    echo.
    echo ERROR: El seeder fallo!
    echo Revisa storage\logs\laravel.log para mas detalles
    echo.
    pause
    exit /b 1
)

echo [8/9] Compilando assets...
call npm run build

echo [9/9] Instalacion completada!
echo.
echo ============================================
echo   INSTALACION EXITOSA!
echo ============================================
echo.
echo Credenciales de prueba:
echo   Organizador: admin@olimpiadas.com / password
echo   Publico: publico@olimpiadas.com / password
echo.
echo Para iniciar la aplicacion:
echo   npm run dev         (Terminal 1)
echo   php artisan serve   (Terminal 2)
echo.
echo Luego abre: http://localhost:8000
echo.
pause

