# 🐳 Docker Quick Start - Olimpiadas

## ⚡ Inicio Rápido (5 minutos)

### Requisitos:
- Docker Desktop instalado
- Git instalado

---

## 🚀 Método 1: Script Automático (Más Fácil)

### En Linux/Mac:
```bash
chmod +x docker-start.sh
./docker-start.sh
```

### En Windows:
```bash
docker-start.bat
```

El script hará **TODO automáticamente**:
1. ✅ Verifica .env (crea si no existe)
2. ✅ Construye la imagen Docker
3. ✅ Inicia todos los servicios
4. ✅ Te muestra las URLs de acceso

---

## 🔧 Método 2: Manual

```bash
# 1. Copiar configuración
cp env.docker.example .env

# 2. Editar .env (IMPORTANTE)
# Cambiar:
#   - DB_PASSWORD
#   - DB_ROOT_PASSWORD
#   - GITHUB_REPO (tu repositorio)

# 3. Construir e iniciar
docker-compose up -d --build

# 4. Ver logs
docker-compose logs -f
```

---

## 🌐 Acceder a la Aplicación

Después de `docker-compose up -d`:

| Servicio | URL | Credenciales |
|----------|-----|--------------|
| **Aplicación** | http://localhost | admin@olimpiadas.com / password |
| **phpMyAdmin** | http://localhost:8080 | olimpiadas_user / (tu password) |

---

## 📋 Variables de Entorno Importantes

Edita el archivo `.env`:

```env
# === CAMBIAR ESTAS ===
DB_PASSWORD=tu_password_seguro
DB_ROOT_PASSWORD=otro_password_seguro

# === Opcional ===
APP_PORT=80                 # Puerto de la app
PHPMYADMIN_PORT=8080        # Puerto phpMyAdmin
DB_SEED=true               # Poblar datos de ejemplo
```

---

## 🎯 Arquitectura Docker

```
┌─────────────────────────────────┐
│   Contenedor: olimpiadas_app    │
│                                 │
│   Nginx (80) + PHP-FPM (9000)   │
│   Laravel Application           │
│   Supervisor (gestión)          │
└────────────┬────────────────────┘
             │
             │ Network: olimpiadas_network
             ▼
┌─────────────────────────────────┐
│  Contenedor: olimpiadas_mysql   │
│         MySQL 8.0               │
│    Volume: mysql_data           │
└────────────┬────────────────────┘
             │
             ▼
┌─────────────────────────────────┐
│ Contenedor: olimpiadas_phpmyadmin│
│      phpMyAdmin (8080)          │
└─────────────────────────────────┘
```

---

## 📦 Lo Que Hace el Dockerfile

1. **Copia tu código local** al contenedor
2. **Instala PHP 8.2** + extensiones necesarias
3. **Instala Composer** y dependencias PHP
4. **Instala Node.js** y compila assets
5. **Configura Nginx** para Laravel
6. **Configura Supervisor** para PHP-FPM + Nginx
7. **Ejecuta migraciones** automáticamente al iniciar
8. **Crea storage link**
9. **Optimiza** para producción

---

## ⚙️ Comandos Docker Útiles

### Gestión de Servicios:

```bash
# Iniciar servicios
docker-compose up -d

# Detener servicios
docker-compose down

# Reiniciar un servicio
docker-compose restart app

# Ver estado
docker-compose ps

# Ver logs
docker-compose logs -f app
```

### Comandos Laravel:

```bash
# Ejecutar migraciones
docker-compose exec app php artisan migrate

# Poblar base de datos
docker-compose exec app php artisan db:seed

# Limpiar caché
docker-compose exec app php artisan optimize:clear

# Tinker
docker-compose exec app php artisan tinker

# Crear usuario admin
docker-compose exec app php artisan tinker
>>> User::create(['name' => 'Admin', 'email' => 'admin@test.com', 'password' => Hash::make('password'), 'role' => 'organizador']);
```

### Base de Datos:

```bash
# Conectar a MySQL
docker-compose exec mysql mysql -u root -p

# Backup
docker-compose exec mysql mysqldump -u root -p olimpiadas > backup.sql

# Restaurar
docker-compose exec -T mysql mysql -u root -p olimpiadas < backup.sql
```

---

## 🔄 Actualizar la Aplicación

```bash
# 1. Hacer cambios en tu código local

# 2. Detener contenedores
docker-compose down

# 3. Reconstruir imagen con nuevos cambios
docker-compose build --no-cache app

# 4. Iniciar
docker-compose up -d

# 5. Ejecutar migraciones nuevas
docker-compose exec app php artisan migrate --force
```

---

## 🛠️ Personalización

### Cambiar Puerto de la App:

En `.env`:
```env
APP_PORT=8000
```

Luego:
```bash
docker-compose up -d
```

Acceso: http://localhost:8000

### Cambiar Versión de PHP:

En `Dockerfile` línea 2:
```dockerfile
FROM php:8.3-fpm AS base
```

---

## 🐛 Solución de Problemas

### Puerto 80 ya en uso:

```env
# En .env
APP_PORT=8000
```

### MySQL no conecta:

```bash
# Ver logs
docker-compose logs mysql

# Verificar salud
docker-compose exec mysql mysql -u root -p -e "SELECT 1"
```

### Permisos de storage:

```bash
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
```

### APP_KEY vacía:

```bash
docker-compose exec app php artisan key:generate --force
docker-compose restart app
```

### Ver todos los logs:

```bash
docker-compose logs --tail=100
```

---

## 📊 Volúmenes de Datos

Docker crea 2 volúmenes persistentes:

| Volumen | Contenido |
|---------|-----------|
| `mysql_data` | Base de datos MySQL |
| `storage_data` | Archivos de Laravel (logs, uploads) |

### Ver volúmenes:
```bash
docker volume ls | grep olimpiadas
```

### Backup de volúmenes:
```bash
# Backup automático
chmod +x docker/scripts/backup.sh
./docker/scripts/backup.sh
```

---

## ✅ Verificación

Después de `docker-compose up -d`:

```bash
# 1. Ver contenedores corriendo
docker-compose ps

# Deberías ver 3 contenedores:
# - olimpiadas_app (healthy)
# - olimpiadas_mysql (healthy)
# - olimpiadas_phpmyadmin (running)

# 2. Verificar aplicación
curl http://localhost

# 3. Ver logs
docker-compose logs --tail=50 app
```

---

## 🎯 Flujo de Trabajo Completo

### Primera Vez:

```bash
# 1. Configurar
cp env.docker.example .env
nano .env  # Editar configuración

# 2. Construir e iniciar
docker-compose up -d --build

# 3. Verificar
docker-compose logs -f app

# 4. Acceder
http://localhost
```

### Desarrollo Continuo:

```bash
# 1. Hacer cambios en tu código local

# 2. Actualizar contenedor
docker-compose down
docker-compose up -d --build

# 3. ¡Listo! Cambios aplicados

# Opcional: Guardar en Git
git add .
git commit -m "Nuevas funcionalidades"
git push
```

---

## 🚀 Despliegue en Servidor

```bash
# En tu servidor (Linux)
ssh user@your-server.com

# Instalar Docker
curl -fsSL https://get.docker.com | sh
sudo usermod -aG docker $USER

# Instalar Docker Compose
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose

# Clonar repo
git clone https://github.com/yourusername/olimpiadas.git
cd olimpiadas

# Configurar
cp env.docker.example .env
nano .env
# Cambiar: DB_PASSWORD, APP_URL, etc.

# Iniciar
chmod +x docker-start.sh
./docker-start.sh

# Listo!
# Accede a: http://your-server-ip
```

---

## 📚 Archivos Docker Creados

| Archivo | Propósito |
|---------|-----------|
| `Dockerfile` | Construcción de imagen |
| `docker-compose.yml` | Orquestación de servicios |
| `.dockerignore` | Archivos a excluir |
| `env.docker.example` | Template de variables |
| `docker/nginx.conf` | Config de Nginx |
| `docker/supervisord.conf` | Gestor de procesos |
| `docker/php.ini` | Config de PHP |
| `docker/docker-entrypoint.sh` | Script de inicio |
| `docker/mysql/init.sql` | Init de MySQL |
| `docker/scripts/backup.sh` | Script de backup |
| `docker/scripts/restore.sh` | Script de restore |
| `docker-start.sh` | Inicio automático (Linux) |
| `docker-start.bat` | Inicio automático (Windows) |
| `DOCKER.md` | Documentación completa |
| `DOCKER_QUICK_START.md` | Esta guía |

---

## 🎉 ¡Listo para Docker!

Tu aplicación está lista para ejecutarse en Docker con:
- ✅ Multi-stage build optimizado
- ✅ Nginx + PHP-FPM
- ✅ MySQL 8.0
- ✅ phpMyAdmin incluido
- ✅ Scripts de backup/restore
- ✅ Health checks configurados
- ✅ Variables de entorno
- ✅ Optimizaciones de producción
- ✅ Documentación completa

**Empezar ahora:**
```bash
cp env.docker.example .env
# Editar .env
docker-compose up -d --build
```

**Acceder:**
http://localhost

---

**¿Problemas?** Lee `DOCKER.md` para documentación completa.

**¡Disfruta tu aplicación Dockerizada!** 🐳🏅

