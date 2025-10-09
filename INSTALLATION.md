# 🚀 Guía de Instalación - Sistema de Gestión de Olimpiadas

Esta guía te llevará paso a paso por el proceso de instalación y configuración del sistema.

## ✅ Pre-requisitos

Antes de comenzar, asegúrate de tener instalado:

- **PHP 8.1 o superior** con las siguientes extensiones:
  - OpenSSL
  - PDO
  - Mbstring
  - Tokenizer
  - XML
  - Ctype
  - JSON
  - BCMath
  - Fileinfo

- **Composer** (gestor de dependencias PHP)
- **MySQL 5.7+** o **MariaDB 10.3+**
- **Node.js 16+** y **NPM** (para compilar assets)
- **Git** (opcional, para clonar repositorio)

### Verificar versiones instaladas:

```bash
php -v
composer --version
mysql --version
node -v
npm -v
```

## 📦 Paso 1: Obtener el Código

El código ya está en `C:\DevProjects\Olimpiadas`, así que ya tienes este paso completado.

## 🔧 Paso 2: Instalar Dependencias de PHP

Abre una terminal en la carpeta del proyecto y ejecuta:

```bash
cd C:\DevProjects\Olimpiadas
composer install
```

Esto instalará todas las dependencias de Laravel y paquetes necesarios.

## 📦 Paso 3: Instalar Dependencias de JavaScript

```bash
npm install
```

Esto instalará Tailwind CSS, Alpine.js y otras dependencias del frontend.

## ⚙️ Paso 4: Configurar Variables de Entorno

1. **Copia el archivo de ejemplo:**
   ```bash
   copy .env.example .env
   ```

2. **Edita el archivo `.env`** y configura tus credenciales de base de datos:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=olimpiadas
   DB_USERNAME=root
   DB_PASSWORD=tu_password_aqui
   ```

## 🔑 Paso 5: Generar Clave de Aplicación

```bash
php artisan key:generate
```

Este comando generará una clave única para tu aplicación y la guardará en el archivo `.env`.

## 🗄️ Paso 6: Crear la Base de Datos

### Opción A: Desde MySQL CLI

```bash
mysql -u root -p
```

Luego ejecuta:

```sql
CREATE DATABASE olimpiadas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

### Opción B: Desde phpMyAdmin

1. Abre phpMyAdmin en tu navegador
2. Click en "Nueva base de datos"
3. Nombre: `olimpiadas`
4. Cotejamiento: `utf8mb4_unicode_ci`
5. Click en "Crear"

## 🔄 Paso 7: Ejecutar Migraciones

Este comando creará todas las tablas necesarias:

```bash
php artisan migrate
```

Deberías ver algo como:
```
Migration table created successfully.
Migrating: 2024_01_01_000000_create_users_table
Migrated:  2024_01_01_000000_create_users_table
...
```

## 🌱 Paso 8: Poblar la Base de Datos (Datos de Ejemplo)

```bash
php artisan db:seed
```

Esto creará:
- 2 usuarios de prueba (admin y público)
- 4 tipos de juego (Fútbol, Natación, Voleibol, Atletismo)
- 5 alianzas/países (México, Brasil, Argentina, USA, Colombia)
- 10 personas (competidores)
- 2 competencias con resultados
- Varios enfrentamientos con ganadores

Verás un mensaje como:
```
Database seeded successfully!
Admin: admin@olimpiadas.com / password
Public: publico@olimpiadas.com / password
```

## 🎨 Paso 9: Compilar Assets (CSS y JavaScript)

### Para Desarrollo:
```bash
npm run dev
```

Este comando quedará ejecutándose y recompilará automáticamente los cambios.

### Para Producción:
```bash
npm run build
```

## 🚀 Paso 10: Iniciar el Servidor

En una **nueva terminal** (deja la anterior con `npm run dev` corriendo):

```bash
php artisan serve
```

Verás algo como:
```
INFO  Server running on [http://127.0.0.1:8000].
Press Ctrl+C to stop the server.
```

## 🎉 Paso 11: Acceder a la Aplicación

Abre tu navegador y ve a:

**Dashboard Público:** http://localhost:8000

**Login:** http://localhost:8000/login

### Credenciales de Prueba:

#### Organizador (Acceso Completo)
- **Email:** admin@olimpiadas.com
- **Password:** password

#### Público (Solo Lectura)
- **Email:** publico@olimpiadas.com
- **Password:** password

## ✅ Verificación de Instalación

Si todo está correcto, deberías poder:

1. ✅ Ver el dashboard público con las clasificaciones
2. ✅ Iniciar sesión como organizador
3. ✅ Acceder al panel de administración
4. ✅ Ver todas las entidades (tipos de juego, alianzas, personas, etc.)
5. ✅ Crear, editar y eliminar registros
6. ✅ Exportar datos a Excel

## 🔧 Solución de Problemas Comunes

### Error: "No application encryption key has been specified"

```bash
php artisan key:generate
php artisan config:clear
```

### Error: "SQLSTATE[HY000] [1045] Access denied"

Revisa tus credenciales de MySQL en el archivo `.env`:
- DB_USERNAME
- DB_PASSWORD
- DB_DATABASE

### Error: "Class 'Maatwebsite\Excel\...' not found"

```bash
composer dump-autoload
php artisan config:clear
```

### Los estilos no se cargan

```bash
npm run build
php artisan view:clear
```

### Error de permisos en storage/

```bash
# En Windows (PowerShell como Administrador)
icacls storage /grant Users:F /t
icacls bootstrap/cache /grant Users:F /t

# En Linux/Mac
chmod -R 775 storage bootstrap/cache
```

### El servidor web no inicia

Verifica que el puerto 8000 no esté ocupado:
```bash
# Usar otro puerto
php artisan serve --port=8001
```

## 🔄 Comandos Útiles Post-Instalación

### Limpiar toda la caché
```bash
php artisan optimize:clear
```

### Ver todas las rutas disponibles
```bash
php artisan route:list
```

### Refrescar la base de datos (⚠️ Borra todos los datos)
```bash
php artisan migrate:fresh --seed
```

### Crear un nuevo usuario desde consola
```bash
php artisan tinker
```
Luego:
```php
App\Models\User::create([
    'name' => 'Tu Nombre',
    'email' => 'tu@email.com',
    'password' => Hash::make('password'),
    'role' => 'organizador'
]);
```

## 📱 Acceso desde Otros Dispositivos en tu Red

Para acceder desde otros dispositivos (móvil, tablet):

1. Obtén tu IP local:
   ```bash
   # Windows
   ipconfig
   
   # Linux/Mac
   ifconfig
   ```

2. Inicia el servidor con tu IP:
   ```bash
   php artisan serve --host=0.0.0.0 --port=8000
   ```

3. Accede desde otro dispositivo:
   ```
   http://TU_IP_LOCAL:8000
   ```

## 🎯 Próximos Pasos

Una vez instalado, puedes:

1. **Explorar el Dashboard Público** - Ver clasificaciones y resultados
2. **Iniciar sesión como Admin** - Gestionar todo el sistema
3. **Crear tus propios datos** - Añadir nuevos tipos de juego, alianzas, etc.
4. **Registrar enfrentamientos** - Añadir resultados de partidos
5. **Exportar reportes** - Descargar clasificaciones en Excel

## 📚 Documentación Adicional

- [README.md](README.md) - Documentación completa del proyecto
- [Laravel Documentation](https://laravel.com/docs/10.x) - Framework utilizado
- [Tailwind CSS](https://tailwindcss.com/docs) - Framework CSS

## 🆘 Soporte

Si encuentras problemas durante la instalación:

1. Verifica que todos los pre-requisitos estén instalados
2. Revisa los logs en `storage/logs/laravel.log`
3. Asegúrate de que MySQL está corriendo
4. Verifica los permisos de las carpetas

## 🎓 Tips para Desarrollo

- Mantén `npm run dev` corriendo durante el desarrollo
- Usa `php artisan tinker` para experimentar con modelos
- Revisa `routes/web.php` para ver todas las rutas
- Los logs están en `storage/logs/laravel.log`

---

¡Listo! Tu sistema de Gestión de Olimpiadas está instalado y funcionando. 🎉

