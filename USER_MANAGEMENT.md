# 👥 Gestión de Usuarios - Sistema Olimpiadas

## ✅ Funcionalidad Agregada

Se ha implementado un módulo completo de **Administración de Usuarios** en el panel de administración.

## 🎯 Características Implementadas

### 1. CRUD Completo de Usuarios
- ✅ **Listar Usuarios** - Ver todos los usuarios del sistema
- ✅ **Crear Usuario** - Registrar nuevos usuarios (organizadores o públicos)
- ✅ **Ver Usuario** - Detalles completos del usuario y permisos
- ✅ **Editar Usuario** - Actualizar nombre, email, rol y contraseña
- ✅ **Eliminar Usuario** - Borrar usuarios (excepto tu propia cuenta)

### 2. Gestión de Roles
- ✅ **Cambiar Rol** - Botón rápido para cambiar entre Organizador/Público
- ✅ **Protección** - No puedes cambiar tu propio rol
- ✅ **Validaciones** - Solo usuarios organizadores pueden administrar usuarios

### 3. Seguridad Implementada
- ✅ **Contraseñas Hasheadas** - Usando bcrypt
- ✅ **Validación de Email** - Email único en el sistema
- ✅ **Confirmación de Contraseña** - Doble verificación
- ✅ **Política de Contraseña** - Mínimo 8 caracteres
- ✅ **Protección Anti-Eliminación** - No puedes eliminarte a ti mismo
- ✅ **Protección de Rol** - No puedes cambiar tu propio rol

### 4. Interfaz de Usuario
- ✅ **Diseño Responsivo** - Compatible con móvil, tablet y desktop
- ✅ **Avatares** - Iniciales del usuario en círculos de colores
- ✅ **Badges de Rol** - Visualización clara del rol (Organizador/Público)
- ✅ **Identificación Personal** - Marca "Tú" en tu propia cuenta
- ✅ **Estadísticas** - Contador de usuarios y organizadores en dashboard

## 📍 Ubicación en el Sistema

### Sidebar del Admin
```
🏅 Admin Panel
  └─ Dashboard
  └─ 👥 Usuarios ← NUEVO
  └─ Tipos de Juego
  └─ Alianzas/Países
  └─ Competidores
  └─ Competencias
  └─ Enfrentamientos
```

### Rutas Disponibles

| Ruta | Método | Acción |
|------|--------|--------|
| `/admin/users` | GET | Listar usuarios |
| `/admin/users/create` | GET | Formulario crear usuario |
| `/admin/users` | POST | Guardar nuevo usuario |
| `/admin/users/{user}` | GET | Ver detalles de usuario |
| `/admin/users/{user}/edit` | GET | Formulario editar usuario |
| `/admin/users/{user}` | PUT/PATCH | Actualizar usuario |
| `/admin/users/{user}` | DELETE | Eliminar usuario |
| `/admin/users/{user}/toggle-role` | POST | Cambiar rol rápidamente |

## 🎨 Capturas de Funcionalidades

### Listado de Usuarios
- Tabla con todos los usuarios
- Columnas: Avatar, Nombre, Email, Rol, Fecha de registro
- Acciones: Ver, Editar, Cambiar Rol, Eliminar
- Paginación automática (15 por página)
- Resalta tu propia cuenta en azul

### Crear Usuario
- Formulario completo de registro
- Campos: Nombre, Email, Contraseña, Confirmar Contraseña, Rol
- Validación en tiempo real
- Mensajes de error claros

### Editar Usuario
- Pre-llenado con datos actuales
- Cambiar contraseña opcional (deja en blanco para no cambiarla)
- Protección: No puedes cambiar tu propio rol
- Campo de rol deshabilitado si editas tu cuenta

### Ver Usuario
- Avatar grande con inicial
- Información completa: Nombre, Email, Rol
- Fechas: Registro, Última actualización
- Estado de verificación de email
- Lista de permisos según rol
- Botón de edición rápida

### Cambiar Rol (Quick Action)
- Botón directo en listado
- Confirmación antes de cambiar
- Alterna entre Organizador ↔ Público
- Protegido: no funciona en tu propia cuenta

## 🔐 Permisos y Restricciones

### Organizador Puede:
✅ Ver todos los usuarios  
✅ Crear nuevos usuarios  
✅ Editar cualquier usuario (excepto cambiar su propio rol)  
✅ Eliminar usuarios (excepto a sí mismo)  
✅ Cambiar roles de otros usuarios  
✅ Acceso total al panel de administración  

### Público Puede:
❌ No tiene acceso al panel de administración  
✅ Solo puede ver el dashboard público  

### Usuario NO Puede:
❌ Eliminar su propia cuenta  
❌ Cambiar su propio rol  

## 💡 Casos de Uso

### 1. Crear un Nuevo Organizador
1. Login como admin
2. Admin → Usuarios → Nuevo Usuario
3. Llenar formulario con rol "Organizador"
4. El nuevo usuario puede ahora administrar todo

### 2. Convertir Público en Organizador
1. Admin → Usuarios
2. Buscar el usuario público
3. Click en "Cambiar Rol"
4. Confirmar
5. ¡Usuario ahora tiene acceso admin!

### 3. Cambiar Contraseña de Usuario
1. Admin → Usuarios → Editar
2. Ingresar nueva contraseña
3. Confirmar nueva contraseña
4. Guardar (deja en blanco si no quieres cambiarla)

### 4. Ver Permisos de un Usuario
1. Admin → Usuarios → Ver
2. Se muestran todos los permisos según su rol
3. Verde ✓ = tiene acceso
4. Gris ✗ = sin acceso

## 📊 Estadísticas en Dashboard

El dashboard ahora muestra:
- **Total de Usuarios** - Contador total
- **Organizadores** - Cuántos tienen acceso admin
- **Enlace directo** - "Ver todos" al módulo de usuarios

## 🎨 Diseño

### Colores
- **Color Principal**: Índigo (#4f46e5)
- **Organizador**: Púrpura
- **Público**: Gris
- **Tu cuenta**: Azul claro (resaltado)

### Iconos
- 👤 Usuario individual
- 👥 Grupo de usuarios
- ⚙️ Acciones de administración
- ✓ Permisos concedidos
- ✗ Sin acceso

## 🚀 Uso Rápido

### Acceso al Módulo
```
http://localhost:8000/admin/users
```

### Crear Primer Usuario Admin
```bash
# Desde artisan tinker
php artisan tinker

User::create([
    'name' => 'Nuevo Admin',
    'email' => 'nuevo@admin.com',
    'password' => Hash::make('password'),
    'role' => 'organizador'
]);
```

## 📋 Archivos Creados/Modificados

### Nuevos Archivos (4)
1. ✅ `app/Http/Controllers/Admin/UserController.php`
2. ✅ `resources/views/admin/users/index.blade.php`
3. ✅ `resources/views/admin/users/create.blade.php`
4. ✅ `resources/views/admin/users/edit.blade.php`
5. ✅ `resources/views/admin/users/show.blade.php`

### Archivos Modificados (4)
1. ✅ `routes/web.php` - Rutas de usuarios agregadas
2. ✅ `resources/views/layouts/admin.blade.php` - Enlace en sidebar
3. ✅ `app/Http/Controllers/Admin/AdminDashboardController.php` - Stats de usuarios
4. ✅ `resources/views/admin/dashboard.blade.php` - Card de usuarios + botón

## ✨ Características Especiales

### Toggle de Rol con 1 Click
En la lista de usuarios, puedes cambiar rápidamente el rol de cualquier usuario con un solo botón, sin necesidad de entrar a editar.

### Vista de Permisos
En la página de detalles de cada usuario, se muestra visualmente qué puede y qué no puede hacer según su rol.

### Edición Segura
Al editar tu propia cuenta:
- El campo de rol se deshabilita automáticamente
- Puedes cambiar tu nombre, email y contraseña
- No puedes eliminarte accidentalmente

### Contraseña Opcional al Editar
Al editar un usuario, no es necesario ingresar contraseña. Solo si quieres cambiarla.

## 🎯 Validaciones Implementadas

| Campo | Validación |
|-------|-----------|
| Nombre | Requerido, máx 255 caracteres |
| Email | Requerido, formato email, único |
| Contraseña | Mín 8 caracteres, confirmación |
| Rol | Requerido, solo organizador/publico |

## 🔄 Flujo de Trabajo

```
Organizador Admin
    ↓
Crea nuevo usuario
    ↓
Asigna rol (Organizador/Público)
    ↓
Usuario recibe credenciales
    ↓
Usuario inicia sesión
    ↓
Acceso según rol asignado
```

## 📱 Responsive Design

✅ **Móvil**: Tabla adaptable con scroll horizontal  
✅ **Tablet**: Layout optimizado  
✅ **Desktop**: Vista completa con todas las columnas  

## 🎉 ¡Listo para Usar!

La gestión de usuarios está completamente implementada y lista para usar. Ahora los organizadores pueden:

- ✅ Crear nuevos usuarios (admin o público)
- ✅ Gestionar permisos cambiando roles
- ✅ Actualizar información de usuarios
- ✅ Ver detalles y permisos
- ✅ Eliminar usuarios innecesarios

**Accede a:** http://localhost:8000/admin/users

---

**Desarrollado con:**
- Laravel 10
- Tailwind CSS
- Alpine.js
- Seguridad y validaciones completas

