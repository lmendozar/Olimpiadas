# 🐳 Guía de Docker - Sistema de Gestión de Olimpiadas

## 📋 Descripción

Esta guía te ayudará a ejecutar la aplicación completa usando Docker y Docker Compose.

## ✅ Requisitos Previos

- **Docker** 20.10+
- **Docker Compose** 2.0+
- **Git** (para clonar el repositorio)

### Verificar instalación:
```bash
docker --version
docker-compose --version
```

---

## 🚀 Inicio Rápido

### Opción 1: Desde GitHub (Recomendado para Producción)

```bash
# 1. Clona el repositorio
git clone https://github.com/yourusername/olimpiadas.git
cd olimpiadas

# 2. Copia y configura variables de entorno
cp .env.docker .env

# 3. Edita el .env con tus datos
nano .env  # o notepad .env en Windows

# IMPORTANTE: Cambia estas variables:
# - DB_PASSWORD
# - DB_ROOT_PASSWORD
# - APP_KEY (se genera automáticamente si está vacío)
# - GITHUB_REPO (tu repositorio)

# 4. Construir e iniciar contenedores
docker-compose up -d --build

# 5. Ver logs
docker-compose logs -f app

# La aplicación estará disponible en:
# http://localhost
```

### Opción 2: Desarrollo Local

```bash
# 1. En tu directorio del proyecto
cd C:\DevProjects\Olimpiadas

# 2. Configurar variables
cp .env.docker .env
# Edita según necesites

# 3. Construir imagen local (sin GitHub)
docker build -t olimpiadas-app .

# 4. Iniciar servicios
docker-compose up -d

# 5. Acceder
# http://localhost
```

---

## 📦 Servicios Incluidos

| Servicio | Puerto | Descripción |
|----------|--------|-------------|
| **app** | 80 | Aplicación Laravel (Nginx + PHP-FPM) |
| **mysql** | 3306 | Base de datos MySQL 8.0 |

---

## ⚙️ Variables de Entorno

### Archivo: `.env` (principal)

```env
# === APLICACIÓN ===
APP_NAME="Olimpiadas Management System"
APP_ENV=production
APP_KEY=                    # Se genera automáticamente
APP_DEBUG=false
APP_URL=http://localhost

# === BASE DE DATOS ===
DB_DATABASE=olimpiadas
DB_USERNAME=olimpiadas_user
DB_PASSWORD=tu_password_seguro_aqui
DB_ROOT_PASSWORD=otro_password_seguro

# === DOCKER ===
APP_PORT=80                 # Puerto de la aplicación
PHPMYADMIN_PORT=8080        # Puerto de phpMyAdmin

# === GITHUB (Para build) ===
GITHUB_REPO=https://github.com/tu-usuario/olimpiadas.git
GITHUB_BRANCH=main

# === OTROS ===
DB_SEED=true               # Poblar DB en primer inicio
```

---

## 🛠️ Comandos Útiles

### Iniciar/Detener Servicios

```bash
# Iniciar todos los servicios
docker-compose up -d

# Detener todos los servicios
docker-compose down

# Detener y eliminar volúmenes (⚠️ BORRA LA BASE DE DATOS)
docker-compose down -v

# Ver logs en tiempo real
docker-compose logs -f

# Ver logs de un servicio específico
docker-compose logs -f app
docker-compose logs -f mysql
```

### Gestión de Contenedores

```bash
# Ver estado de contenedores
docker-compose ps

# Reiniciar un servicio
docker-compose restart app

# Reconstruir imagen
docker-compose build --no-cache app

# Reconstruir e iniciar
docker-compose up -d --build
```

### Comandos de Laravel dentro del contenedor

```bash
# Entrar al contenedor
docker-compose exec app bash

# Ejecutar comandos Artisan
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
docker-compose exec app php artisan optimize:clear
docker-compose exec app php artisan tinker

# Ver rutas
docker-compose exec app php artisan route:list

# Limpiar caché
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
```

### Base de Datos

```bash
# Conectar a MySQL
docker-compose exec mysql mysql -u root -p

# Hacer backup
docker-compose exec mysql mysqldump -u root -p olimpiadas > backup.sql

# Restaurar backup
docker-compose exec -T mysql mysql -u root -p olimpiadas < backup.sql

# Ver logs de MySQL
docker-compose logs -f mysql
```

---

## 🏗️ Arquitectura del Contenedor

```
┌─────────────────────────────────────┐
│     Contenedor: olimpiadas_app      │
│                                     │
│  ┌─────────────────────────────┐   │
│  │         Nginx (80)           │   │
│  └────────────┬────────────────┘   │
│               │                     │
│  ┌────────────▼────────────────┐   │
│  │      PHP-FPM (9000)         │   │
│  │    Laravel Application      │   │
│  └─────────────────────────────┘   │
│                                     │
│  Supervisor supervisa ambos         │
└─────────────────────────────────────┘
                │
                │ (conexión de red)
                ▼
┌─────────────────────────────────────┐
│    Contenedor: olimpiadas_mysql     │
│         MySQL 8.0 (3306)            │
│    Volumen: mysql_data              │
└─────────────────────────────────────┘
```

---

## 📂 Estructura de Archivos Docker

```
Olimpiadas/
├── Dockerfile                  # Imagen de la aplicación
├── docker-compose.yml          # Orquestación de servicios
├── .dockerignore              # Archivos a ignorar
├── .env.docker                # Ejemplo de variables
├── docker/
│   ├── nginx.conf             # Configuración de Nginx
│   ├── supervisord.conf       # Supervisor para PHP-FPM + Nginx
│   ├── php.ini                # Configuración de PHP
│   ├── docker-entrypoint.sh   # Script de inicio
│   ├── env/
│   │   └── .env               # Template de .env
│   └── mysql/
│       └── init.sql           # Script de inicialización de MySQL
└── DOCKER.md                  # Esta documentación
```

---

## 🔧 Personalización

### Cambiar Puerto de la Aplicación

En `.env`:
```env
APP_PORT=8000
```

Luego:
```bash
docker-compose up -d
```

Acceso: http://localhost:8000

### Cambiar Versión de PHP

En `Dockerfile` línea 2:
```dockerfile
FROM php:8.3-fpm AS base  # Cambiar 8.2 a 8.3
```

### Agregar Extensiones de PHP

En `Dockerfile` después de línea 18:
```dockerfile
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    redis intl zip
```

### Usar Base de Datos Externa

En `.env`:
```env
DB_HOST=your-external-db.com
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_user
DB_PASSWORD=your_password
```

Y comenta el servicio MySQL en `docker-compose.yml`.

---

## 🔒 Seguridad en Producción

### 1. Cambiar Contraseñas

```env
DB_PASSWORD=UnPasswordMuySeguro123!
DB_ROOT_PASSWORD=OtroPasswordSeguro456!
```

### 2. Deshabilitar Debug

```env
APP_DEBUG=false
APP_ENV=production
```


### 4. Usar HTTPS

Necesitarás un proxy reverso como Traefik o Nginx adicional con certificados SSL.

---

## 🌐 Despliegue en Producción

### AWS EC2 / DigitalOcean / VPS

```bash
# 1. Conectar al servidor
ssh user@your-server.com

# 2. Instalar Docker y Docker Compose
curl -fsSL https://get.docker.com | sh
sudo usermod -aG docker $USER

# 3. Clonar repositorio
git clone https://github.com/yourusername/olimpiadas.git
cd olimpiadas

# 4. Configurar .env
cp .env.docker .env
nano .env
# Cambiar: DB_PASSWORD, APP_URL, etc.

# 5. Construir e iniciar
docker-compose up -d --build

# 6. Ver logs
docker-compose logs -f
```

### Con Dominio Propio

En `.env`:
```env
APP_URL=https://olimpiadas.tudominio.com
```

Luego configura un proxy reverso (Nginx/Traefik) con SSL.

---

## 🔄 Actualización de la Aplicación

### Actualizar desde GitHub:

```bash
# 1. Detener contenedores
docker-compose down

# 2. Reconstruir imagen (descarga últimos cambios de GitHub)
docker-compose build --no-cache app

# 3. Iniciar con nueva imagen
docker-compose up -d

# 4. Ejecutar migraciones si hay nuevas
docker-compose exec app php artisan migrate --force
```

### Actualizar solo código (sin rebuild):

```bash
# Dentro del contenedor
docker-compose exec app bash

# Pull últimos cambios
git pull origin main

# Instalar dependencias si cambiaron
composer install --no-dev --optimize-autoloader
npm ci && npm run build

# Limpiar caché
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
```

---

## 📊 Monitoreo y Logs

### Ver Logs en Tiempo Real:

```bash
# Todos los servicios
docker-compose logs -f

# Solo aplicación
docker-compose logs -f app

# Solo MySQL
docker-compose logs -f mysql

# Últimas 100 líneas
docker-compose logs --tail=100 app
```

### Logs de Laravel:

```bash
# Dentro del contenedor
docker-compose exec app tail -f storage/logs/laravel.log

# Desde el host (si tienes volumen montado)
tail -f storage/logs/laravel.log
```

### Estadísticas de Contenedores:

```bash
# Uso de recursos
docker stats

# Espacio en disco
docker system df
```

---

## 🗄️ Gestión de Datos

### Backup de Base de Datos:

```bash
# Crear backup
docker-compose exec mysql mysqldump -u root -p${DB_ROOT_PASSWORD} ${DB_DATABASE} > olimpiadas_backup_$(date +%Y%m%d).sql

# Restaurar backup
docker-compose exec -T mysql mysql -u root -p${DB_ROOT_PASSWORD} ${DB_DATABASE} < olimpiadas_backup_20241009.sql
```

### Backup de Storage:

```bash
# Crear backup de archivos subidos
docker run --rm -v olimpiadas_storage_data:/data -v $(pwd):/backup alpine tar czf /backup/storage_backup.tar.gz /data

# Restaurar
docker run --rm -v olimpiadas_storage_data:/data -v $(pwd):/backup alpine tar xzf /backup/storage_backup.tar.gz -C /
```

### Limpiar Volúmenes:

```bash
# ⚠️ ADVERTENCIA: Esto BORRARÁ todos los datos

# Ver volúmenes
docker volume ls

# Eliminar volúmenes específicos
docker volume rm olimpiadas_mysql_data
docker volume rm olimpiadas_storage_data
```

---

## 🐛 Solución de Problemas

### Error: "Port 80 already in use"

Cambiar puerto en `.env`:
```env
APP_PORT=8000
```

### Error: "MySQL Connection refused"

```bash
# Ver logs de MySQL
docker-compose logs mysql

# Verificar que MySQL esté listo
docker-compose exec mysql mysqladmin ping -h localhost -u root -p
```

### Error: "Permission denied" en storage

```bash
# Dentro del contenedor
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
```

### Error: "APP_KEY not set"

```bash
# Generar nueva clave
docker-compose exec app php artisan key:generate --force

# Reiniciar contenedor
docker-compose restart app
```

### Contenedor no inicia:

```bash
# Ver logs detallados
docker-compose logs --tail=50 app

# Verificar configuración
docker-compose config

# Reconstruir desde cero
docker-compose down -v
docker-compose up -d --build
```

---

## 🎯 Casos de Uso

### Desarrollo Local con Docker:

```bash
# Usar docker-compose con volúmenes para desarrollo
# Edita docker-compose.yml y agrega:
volumes:
  - .:/var/www/html
  
# Esto permitirá editar archivos localmente
# y ver cambios en tiempo real
```

### Staging Environment:

```env
APP_ENV=staging
APP_DEBUG=true
APP_URL=https://staging.olimpiadas.com
```

### Producción:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://olimpiadas.com
DB_PASSWORD=PasswordSuperSeguro123!
```

---

## 📈 Escalabilidad

### Múltiples Workers (Queue):

Si usas colas de trabajo, agrega en `docker-compose.yml`:

```yaml
queue:
  build:
    context: .
    dockerfile: Dockerfile
  container_name: olimpiadas_queue
  restart: unless-stopped
  command: php artisan queue:work --sleep=3 --tries=3
  depends_on:
    - app
  environment:
    # Mismas variables que app
  networks:
    - olimpiadas_network
```

### Load Balancer:

Para múltiples instancias:

```bash
docker-compose up -d --scale app=3
```

Luego configura un load balancer (Nginx, Traefik, etc.)

---

## 🔐 Mejores Prácticas

### 1. Secrets Management

No incluir passwords en el repositorio:

```bash
# Usar Docker secrets en producción
docker secret create db_password /path/to/password/file
```

### 2. Health Checks

Los health checks están configurados en `docker-compose.yml`:
- Aplicación: Verifica endpoint `/up`
- MySQL: Ping a la base de datos

### 3. Logging

Los logs se envían a stdout/stderr para integración con sistemas de logging centralizados.

### 4. Actualizaciones

Siempre haz backup antes de actualizar:

```bash
# Backup automático
./backup.sh  # (crear este script)

# Luego actualizar
docker-compose down
docker-compose up -d --build
```

---

## 📦 Construcción de Imagen

### Build Arguments:

```bash
# Construir con argumentos personalizados
docker-compose build --build-arg GITHUB_REPO=https://github.com/otro-repo/olimpiadas.git \
                     --build-arg GITHUB_BRANCH=develop \
                     app
```

### Multi-stage Build:

El Dockerfile usa multi-stage build para:
1. **Stage 1 (builder)**: Clona repo, instala dependencias, compila assets
2. **Stage 2 (production)**: Solo copia archivos necesarios, más ligero

---

## 🌍 URLs de Acceso

Después de `docker-compose up -d`:

| Servicio | URL | Credenciales |
|----------|-----|--------------|
| **Aplicación** | http://localhost | admin@olimpiadas.com / password |
| **phpMyAdmin** | http://localhost:8080 | olimpiadas_user / (tu password) |
| **API** | http://localhost/api | (Si implementas API) |

---

## 🔍 Debugging

### Logs de Nginx:

```bash
docker-compose exec app tail -f /var/log/nginx/access.log
docker-compose exec app tail -f /var/log/nginx/error.log
```

### Logs de PHP:

```bash
docker-compose exec app tail -f /var/log/php_errors.log
```

### Logs de Laravel:

```bash
docker-compose exec app tail -f storage/logs/laravel.log
```

### Entrar al Contenedor:

```bash
# Shell interactivo
docker-compose exec app bash

# Como root
docker-compose exec -u root app bash
```

---

## 🚀 Performance en Producción

### Optimizaciones Aplicadas:

1. ✅ **Opcache** habilitado en `php.ini`
2. ✅ **Config cache** en entrypoint
3. ✅ **Route cache** en entrypoint
4. ✅ **View cache** en entrypoint
5. ✅ **Composer autoload optimizado**
6. ✅ **Assets compilados** en build
7. ✅ **Multi-stage build** (imagen más pequeña)

### Verificar optimizaciones:

```bash
docker-compose exec app php artisan about

# Deberías ver:
# Config Cached: true
# Routes Cached: true
# Views Cached: true
```

---

## 📊 Tamaño de la Imagen

```bash
# Ver tamaño de imágenes
docker images | grep olimpiadas

# Típicamente:
# olimpiadas-app: ~500MB (optimizado)
```

---

## 🎓 Docker Compose - Comandos Avanzados

```bash
# Ver configuración final (con variables resueltas)
docker-compose config

# Validar docker-compose.yml
docker-compose config --quiet

# Recrear contenedores sin perder volúmenes
docker-compose up -d --force-recreate

# Ver recursos usados
docker-compose top

# Ejecutar comando único
docker-compose run --rm app php artisan inspire
```

---

## 🆘 Soporte

### Problemas Comunes:

1. **Puerto en uso**: Cambiar `APP_PORT` en `.env`
2. **MySQL no conecta**: Verificar contraseñas en `.env`
3. **Permisos de storage**: Ejecutar entrypoint script manualmente
4. **APP_KEY vacía**: Generar con `php artisan key:generate`

### Recursos:

- Docker Docs: https://docs.docker.com
- Docker Compose Docs: https://docs.docker.com/compose
- Laravel Docs: https://laravel.com/docs

---

## ✅ Checklist de Producción

Antes de desplegar en producción:

- [ ] Contraseñas seguras en `.env`
- [ ] `APP_DEBUG=false`
- [ ] `APP_ENV=production`
- [ ] Backup configurado
- [ ] Health checks funcionando
- [ ] SSL configurado
- [ ] Logs centralizados
- [ ] Monitoreo activo
- [ ] phpMyAdmin deshabilitado o protegido
- [ ] Firewall configurado

---

## 🎉 ¡Listo para Docker!

Tu aplicación está preparada para ejecutarse en Docker con:
- ✅ Dockerfile optimizado
- ✅ Docker Compose completo
- ✅ Configuración de producción
- ✅ Scripts automatizados
- ✅ Documentación completa

**Comandos para empezar:**
```bash
cp .env.docker .env
# Editar .env con tus datos
docker-compose up -d --build
```

**Acceder:**
- Aplicación: http://localhost
- phpMyAdmin: http://localhost:8080

---

**¡Disfruta tu aplicación Dockerizada!** 🐳🏅

