# Sistema de Gestión de Olimpiadas

Sistema completo de gestión de Juegos Olímpicos desarrollado con Laravel 10, MySQL y Tailwind CSS.

## 🎯 Características Principales

### Autenticación y Roles
- **Organizador**: Acceso completo al panel de administración (CRUD)
- **Público/Visitante**: Acceso solo al dashboard público (lectura)

### Entidades Gestionadas
1. **Usuarios**: Gestión completa de usuarios del sistema (Organizadores y Públicos)
2. **Tipos de Juego**: Definición de deportes con métricas (Tiempo, Goles, Sets, Contador)
3. **Alianzas/Países**: Equipos participantes con logos y miembros
4. **Personas**: Competidores y organizadores asociados a alianzas
5. **Competencias**: Torneos con sistema de puntuación personalizado
6. **Enfrentamientos**: Partidos con resultados y ganadores

### Funcionalidades Clave

#### Panel de Administración (Organizador)
- ✅ **Gestión de Usuarios**: CRUD completo, cambio de roles, permisos
- ✅ CRUD completo para todas las entidades (6 módulos)
- ✅ Registro de resultados y ganadores
- ✅ Sistema automático de cálculo de rankings
- ✅ Soporte para competencias regulares y simultáneas
- ✅ Exportación a Excel (clasificación, competencias, enfrentamientos)
- ✅ Dashboard con estadísticas en tiempo real

#### Dashboard Público
- ✅ Clasificación general con medallas (Oro, Plata, Bronce)
- ✅ Visualización de enfrentamientos recientes y próximos
- ✅ Detalles de cada partido con participantes y resultados
- ✅ Aplicación de regla de visibilidad de personas (≤3: nombres completos, >3: solo alianza)

### Lógica de Puntuación
- **Competencias Regulares**: Ranking basado en suma de victorias
- **Competencias Simultáneas**: Ranking directo del enfrentamiento (ej: natación)
- **Puntos Personalizables**: Oro, Plata y Bronce con valores configurables
- **Cálculo Automático**: Rankings finales al completar todos los enfrentamientos

## 🚀 Instalación

### Requisitos Previos
- PHP 8.1 o superior
- Composer
- MySQL 5.7 o superior
- Node.js y NPM

### Pasos de Instalación

1. **Clonar el repositorio**
```bash
cd C:\DevProjects\Olimpiadas
```

2. **Instalar dependencias PHP**
```bash
composer install
```

3. **Instalar dependencias JavaScript**
```bash
npm install
```

4. **Configurar el archivo .env**
```bash
cp .env.example .env
```

Editar `.env` y configurar la base de datos:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=olimpiadas
DB_USERNAME=root
DB_PASSWORD=tu_password
```

5. **Generar clave de aplicación**
```bash
php artisan key:generate
```

6. **Crear base de datos**
```sql
CREATE DATABASE olimpiadas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

7. **Ejecutar migraciones**
```bash
php artisan migrate
```

8. **Poblar base de datos con datos de ejemplo**
```bash
php artisan db:seed
```

9. **Compilar assets**
```bash
npm run dev
```

10. **Iniciar servidor de desarrollo**
```bash
php artisan serve
```

La aplicación estará disponible en: `http://localhost:8000`

## 👥 Usuarios de Prueba

Después de ejecutar `php artisan db:seed`:

### Organizador (Administrador)
- **Email**: admin@olimpiadas.com
- **Password**: password
- **Acceso**: Panel completo de administración

### Público
- **Email**: publico@olimpiadas.com
- **Password**: password
- **Acceso**: Solo dashboard público

## 📁 Estructura del Proyecto

```
Olimpiadas/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/           # Controladores del panel admin
│   │   │   └── Auth/            # Controladores de autenticación
│   │   └── Middleware/
│   │       └── IsOrganizer.php  # Middleware de autorización
│   ├── Models/                  # Modelos Eloquent
│   └── Exports/                 # Clases de exportación Excel
├── database/
│   ├── migrations/              # Migraciones de base de datos
│   └── seeders/                 # Seeders con datos de ejemplo
├── resources/
│   ├── views/
│   │   ├── admin/               # Vistas del panel admin
│   │   ├── auth/                # Vistas de autenticación
│   │   └── layouts/             # Layouts base
│   ├── css/
│   └── js/
└── routes/
    └── web.php                  # Definición de rutas
```

## 🎨 Tecnologías Utilizadas

- **Backend**: Laravel 10 (PHP 8.1+)
- **Base de Datos**: MySQL
- **Frontend**: Blade Templates, Tailwind CSS, Alpine.js
- **Autenticación**: Laravel Sanctum
- **Exportación**: Maatwebsite/Laravel-Excel
- **Build Tool**: Vite

## 📊 Funcionalidades Detalladas

### Sistema de Tipos de Juego
Define la métrica de resultado para cada deporte:
- **Tiempo**: Para deportes como natación, atletismo (formato: mm:ss.ms)
- **Goles**: Para fútbol, basketball (formato: 3-1)
- **Sets**: Para voleibol, tenis (formato: 3-0)
- **Contador**: Para cualquier otro deporte con conteo simple

### Gestión de Competencias
- Configuración de puntos para cada medalla
- Soporte para competencias regulares (múltiples enfrentamientos)
- Soporte para competencias simultáneas (un solo enfrentamiento define todo)
- Finalización automática y cálculo de rankings

### Sistema de Enfrentamientos
- Registro de fecha y hora
- Asignación de múltiples alianzas participantes
- Registro de resultado según métrica del tipo de juego
- Selección de ganador (o empate)
- Para competencias simultáneas: registro de posiciones exactas

### Exportación de Datos
- Clasificación general a Excel
- Todas las competencias con sus ganadores
- Listado completo de enfrentamientos

## 🔒 Seguridad

- Autenticación mediante Laravel Sanctum
- Middleware de autorización por roles
- Protección CSRF en todos los formularios
- Validación de datos en servidor
- Hash seguro de contraseñas

## 📱 Diseño Responsive

La aplicación es completamente responsive y funciona correctamente en:
- 📱 Móviles
- 📱 Tablets
- 💻 Desktop

## 🗄️ Modelo de Base de Datos

### Tablas Principales
- `users`: Usuarios del sistema (organizadores y público)
- `game_types`: Tipos de juegos/deportes
- `alliances`: Equipos/países participantes
- `people`: Personas (competidores y organizadores)
- `competitions`: Competencias olímpicas
- `matches`: Enfrentamientos/partidos
- `match_alliance`: Relación many-to-many entre matches y alliances
- `competition_rankings`: Rankings finales de competencias

### Relaciones
- Una alianza tiene muchas personas
- Una competencia pertenece a un tipo de juego
- Una competencia tiene muchos enfrentamientos
- Un enfrentamiento puede tener múltiples alianzas (many-to-many)
- Una competencia genera múltiples rankings

## 🎯 Reglas de Negocio Implementadas

1. **Eliminación Protegida**: No se pueden eliminar entidades en uso
2. **Cálculo Automático**: Rankings se calculan automáticamente al finalizar competencias
3. **Competencias Regulares**: Ranking por suma de victorias
4. **Competencias Simultáneas**: Ranking directo por posiciones
5. **Visibilidad de Personas**: Muestra nombres si ≤3 competidores, solo alianza si >3
6. **Validación de Resultados**: Formato según tipo de métrica del juego

## 🔄 Comandos Útiles

```bash
# Limpiar caché
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Refrescar base de datos
php artisan migrate:fresh --seed

# Compilar assets para producción
npm run build

# Ver rutas
php artisan route:list
```

## 📝 Notas Adicionales

- El sistema soporta múltiples alianzas en un mismo enfrentamiento
- Los puntos de las medallas son configurables por competencia
- El dashboard público es accesible sin autenticación
- Todas las fechas y horas se manejan en zona horaria local configurada en .env

## 🐛 Solución de Problemas

### Error de conexión a base de datos
Verificar credenciales en `.env` y que MySQL esté corriendo

### Error de permisos
```bash
chmod -R 775 storage bootstrap/cache
```

### Assets no se cargan
```bash
npm run dev
# o para producción
npm run build
```

## 📄 Licencia

Este proyecto es de código abierto bajo licencia MIT.

## 👨‍💻 Desarrollo

Sistema desarrollado como proyecto de gestión de eventos deportivos con arquitectura MVC, siguiendo las mejores prácticas de Laravel y diseño responsivo.

