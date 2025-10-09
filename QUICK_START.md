# ⚡ Quick Start Guide - Olimpiadas

## 🚀 Inicio Rápido (5 minutos)

### 1️⃣ Instalar Dependencias
```bash
cd C:\DevProjects\Olimpiadas
composer install
npm install
```

### 2️⃣ Configurar Base de Datos
```bash
# Copiar configuración
copy .env.example .env

# Editar .env y configurar:
# DB_DATABASE=olimpiadas
# DB_USERNAME=root
# DB_PASSWORD=tu_password
```

### 3️⃣ Crear Base de Datos
```sql
-- En MySQL:
CREATE DATABASE olimpiadas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 4️⃣ Inicializar Aplicación
```bash
php artisan key:generate
php artisan migrate
php artisan db:seed
```

### 5️⃣ Compilar Assets & Iniciar
```bash
# Terminal 1 - Compilar CSS/JS
npm run dev

# Terminal 2 - Servidor Laravel
php artisan serve
```

### 6️⃣ Acceder a la Aplicación

🌐 **Abrir en navegador:** http://localhost:8000

## 👥 Credenciales de Prueba

### Organizador (Admin Completo)
```
Email: admin@olimpiadas.com
Password: password
```

### Público (Solo Lectura)
```
Email: publico@olimpiadas.com
Password: password
```

## 📋 Comandos Útiles

### Refrescar Todo (Borra datos)
```bash
php artisan migrate:fresh --seed
```

### Limpiar Caché
```bash
php artisan optimize:clear
```

### Ver Rutas
```bash
php artisan route:list
```

### Compilar para Producción
```bash
npm run build
```

## 🎯 Primeros Pasos

1. **Login como Admin:** http://localhost:8000/login
2. **Ir al Panel Admin** → Ver dashboard con estadísticas
3. **Explorar Módulos:**
   - Tipos de Juego
   - Alianzas/Países
   - Personas
   - Competencias
   - Enfrentamientos
4. **Crear tu primera competencia**
5. **Registrar enfrentamientos**
6. **Ver dashboard público**

## 📊 Datos de Ejemplo Incluidos

Después de `php artisan db:seed`:
- ✅ 2 usuarios (admin + público)
- ✅ 4 tipos de juego (Fútbol, Natación, Voleibol, Atletismo)
- ✅ 5 países (México, Brasil, Argentina, USA, Colombia)
- ✅ 10 competidores
- ✅ 2 competencias con resultados
- ✅ Varios enfrentamientos finalizados

## 🔗 URLs Principales

| Página | URL |
|--------|-----|
| Dashboard Público | http://localhost:8000 |
| Login | http://localhost:8000/login |
| Registro | http://localhost:8000/register |
| Panel Admin | http://localhost:8000/admin/dashboard |
| Tipos de Juego | http://localhost:8000/admin/game-types |
| Alianzas | http://localhost:8000/admin/alliances |
| Personas | http://localhost:8000/admin/people |
| Competencias | http://localhost:8000/admin/competitions |
| Enfrentamientos | http://localhost:8000/admin/matches |

## 🎨 Características Principales

- ✅ CRUD completo para todas las entidades
- ✅ Sistema de rankings automático
- ✅ Dashboard público con clasificación
- ✅ Exportación a Excel
- ✅ Diseño responsive (móvil, tablet, desktop)
- ✅ Autenticación con roles

## 📱 Acceso desde Móvil/Tablet

```bash
# Iniciar con tu IP local
php artisan serve --host=0.0.0.0 --port=8000

# Acceder desde otro dispositivo
http://TU_IP_LOCAL:8000
```

## 🆘 ¿Problemas?

### Error: "key not specified"
```bash
php artisan key:generate
```

### Error: "Access denied" (MySQL)
Revisa `.env`: DB_USERNAME, DB_PASSWORD

### Los estilos no cargan
```bash
npm run dev
# o
npm run build
```

### Permisos (Windows PowerShell Admin)
```bash
icacls storage /grant Users:F /t
icacls bootstrap\cache /grant Users:F /t
```

## 📚 Documentación Completa

- **README.md** - Documentación completa del proyecto
- **INSTALLATION.md** - Guía detallada de instalación
- **PROJECT_SUMMARY.md** - Resumen técnico completo

## 🎉 ¡Listo para Empezar!

Tu sistema de Gestión de Olimpiadas está configurado y listo para usar.

**¡Disfruta construyendo tus Juegos Olímpicos!** 🏅

---

### Flujo Típico de Uso:

1. **Admin crea:** Tipos de Juego → Alianzas → Personas
2. **Admin programa:** Competencias → Enfrentamientos
3. **Admin registra:** Resultados de partidos
4. **Admin finaliza:** Enfrentamientos y Competencias
5. **Sistema calcula:** Rankings automáticamente
6. **Público ve:** Clasificación general actualizada
7. **Admin exporta:** Reportes a Excel

### Próximos Pasos Sugeridos:

1. Explora el panel de administración
2. Crea tu primera competencia personalizada
3. Registra nuevos enfrentamientos
4. Prueba la exportación a Excel
5. Visualiza el dashboard público
6. Personaliza los puntos de las medallas
7. ¡Comienza tus propias Olimpiadas!

**¿Necesitas más ayuda?** Consulta README.md o INSTALLATION.md

