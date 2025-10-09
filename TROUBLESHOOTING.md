# 🔧 Solución de Problemas - Olimpiadas

## Error: "Trying to access array offset on null" en public/index.php

Este error generalmente se debe a que el archivo `.env` no está configurado correctamente.

### ✅ Solución Paso a Paso

#### 1. Verificar que existe el archivo .env

```bash
# En la raíz del proyecto, ejecuta:
dir .env

# Si dice "No se puede encontrar el archivo", créalo:
notepad .env
```

#### 2. Copiar contenido al archivo .env

Si el archivo está vacío o no existe, copia y pega esto:

```env
APP_NAME="Olimpiadas Management System"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=olimpiadas
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

Guarda el archivo (Ctrl+S) y cierra Notepad.

#### 3. Generar clave de aplicación

```bash
php artisan key:generate
```

Esto agregará una línea como: `APP_KEY=base64:xxx...xxx` a tu `.env`

#### 4. Verificar permisos de directorios

```bash
# En PowerShell como Administrador:
icacls storage /grant Users:F /t
icacls bootstrap\cache /grant Users:F /t
```

#### 5. Limpiar caché

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

#### 6. Regenerar autoload

```bash
composer dump-autoload
```

#### 7. Verificar que MySQL está corriendo

Abre el Panel de Control de XAMPP y asegúrate de que MySQL está iniciado (botón verde "Running").

#### 8. Crear la base de datos

```bash
# Abre MySQL desde consola
mysql -u root -p

# Crea la base de datos
CREATE DATABASE olimpiadas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

O usa phpMyAdmin: http://localhost/phpmyadmin

#### 9. Ejecutar migraciones

```bash
php artisan migrate
```

#### 10. Poblar datos

```bash
php artisan db:seed
```

#### 11. Iniciar el servidor

```bash
# Terminal 1
npm run dev

# Terminal 2 (nueva terminal)
php artisan serve
```

### 🎯 Verificación Final

Accede a: http://localhost:8000

Deberías ver el dashboard público sin errores.

## Otros Errores Comunes

### Error: "No application encryption key has been specified"

```bash
php artisan key:generate
php artisan config:clear
```

### Error: "could not find driver"

Edita `C:\xampp\php\php.ini`:

```ini
# Busca y descomenta (quita el ;):
extension=pdo_mysql
extension=mysqli
```

Reinicia Apache desde XAMPP.

### Error: "Access denied for user 'root'"

Edita `.env` y ajusta la contraseña de MySQL:

```env
DB_PASSWORD=tu_password_aqui
```

### Error: "Base table or view not found"

```bash
php artisan migrate:fresh
php artisan db:seed
```

### Error: Página en blanco

Revisa el log de errores:

```bash
# Abre el archivo de logs
notepad storage\logs\laravel.log
```

### Error: "The stream or file could not be opened"

```bash
# Dar permisos a storage
icacls storage /grant Users:F /t
```

### Error 500 sin mensaje

Activa el modo debug en `.env`:

```env
APP_DEBUG=true
```

Luego limpia la caché:

```bash
php artisan config:clear
```

## 📋 Checklist de Verificación

- [ ] Archivo `.env` existe y tiene contenido
- [ ] `APP_KEY` está generada en `.env`
- [ ] MySQL está corriendo (XAMPP)
- [ ] Base de datos `olimpiadas` existe
- [ ] `DB_PASSWORD` en `.env` es correcta
- [ ] Extensiones PHP habilitadas (pdo_mysql, mysqli)
- [ ] Permisos de storage correctos
- [ ] `composer install` ejecutado
- [ ] `npm install` ejecutado
- [ ] Migraciones ejecutadas
- [ ] Seeders ejecutados

## 🚀 Comandos de Emergencia

Si nada funciona, ejecuta esto en orden:

```bash
# 1. Limpiar todo
php artisan optimize:clear
composer dump-autoload

# 2. Regenerar .env
copy .env.example .env
php artisan key:generate

# 3. Configurar DB en .env (editar manualmente)

# 4. Recrear base de datos
php artisan migrate:fresh --seed

# 5. Compilar assets
npm run build

# 6. Iniciar
php artisan serve
```

## 📞 Log de Errores

Si sigues teniendo problemas, revisa:

1. **Laravel Log:**
   ```
   storage\logs\laravel.log
   ```

2. **PHP Error Log:**
   ```
   C:\xampp\php\logs\php_error_log
   ```

3. **Apache Error Log:**
   ```
   C:\xampp\apache\logs\error.log
   ```

4. **MySQL Error Log:**
   ```
   C:\xampp\mysql\data\mysql_error.log
   ```

## ✅ Estado Correcto

Si todo está bien configurado, `php artisan serve` debería mostrar:

```
INFO  Server running on [http://127.0.0.1:8000].
Press Ctrl+C to stop the server.
```

Y al acceder a http://localhost:8000 verás el dashboard público.

---

**¿Aún tienes problemas?** Envíame el contenido de `storage\logs\laravel.log` y te ayudo específicamente.

