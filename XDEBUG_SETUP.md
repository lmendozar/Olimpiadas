# 🐛 Configuración de Xdebug para Laravel en VS Code

## ✅ Archivos Creados

He creado los siguientes archivos de configuración:

1. **`.vscode/launch.json`** - Configuraciones de depuración
2. **`.vscode/settings.json`** - Configuración de VS Code para PHP
3. **`.vscode/extensions.json`** - Extensiones recomendadas

## 📦 Extensiones Necesarias en VS Code

Instala estas extensiones (VS Code te las sugerirá automáticamente):

1. **PHP Debug** (xdebug.php-debug) - ¡ESENCIAL!
2. **PHP Intelephense** (bmewburn.vscode-intelephense-client)
3. **Laravel Blade** (onecentlin.laravel-blade)
4. **Tailwind CSS IntelliSense** (bradlc.vscode-tailwindcss)
5. **DotENV** (mikestead.dotenv)

Para instalarlas rápidamente:
```
Ctrl+Shift+P → "Extensions: Show Recommended Extensions"
```

## ⚙️ Configurar Xdebug en XAMPP

### Paso 1: Verificar si Xdebug está instalado

```bash
php -m | findstr xdebug
```

Si NO aparece nada, necesitas instalarlo.

### Paso 2: Editar php.ini

1. **Abre php.ini:**
   ```
   C:\xampp\php\php.ini
   ```

2. **Busca la sección `[XDebug]`** (Ctrl+F)

3. **Reemplaza o agrega estas líneas al final del archivo:**

   ```ini
   [XDebug]
   zend_extension=xdebug
   xdebug.mode=debug
   xdebug.start_with_request=yes
   xdebug.client_port=9003
   xdebug.client_host=127.0.0.1
   xdebug.log="C:\xampp\tmp\xdebug.log"
   xdebug.idekey=VSCODE
   ```

   **Para XAMPP 7.x o anterior (PHP 7.x):**
   ```ini
   [XDebug]
   zend_extension=C:\xampp\php\ext\php_xdebug.dll
   xdebug.mode=debug
   xdebug.start_with_request=yes
   xdebug.client_port=9003
   xdebug.client_host=127.0.0.1
   xdebug.log="C:\xampp\tmp\xdebug.log"
   xdebug.idekey=VSCODE
   ```

4. **Guarda el archivo** (Ctrl+S)

### Paso 3: Reiniciar Apache

1. Abre el **Panel de Control de XAMPP**
2. **Stop** Apache
3. **Start** Apache

### Paso 4: Verificar que Xdebug funciona

```bash
php -v
```

Deberías ver algo como:
```
PHP 8.x.x (cli) (built: ...)
...
    with Xdebug v3.x.x, Copyright (c) 2002-2023, by Derick Rethans
```

O verifica con:
```bash
php -i | findstr xdebug
```

## 🚀 Cómo Usar el Debugger

### Opción 1: Depurar el Servidor Web

1. **Pon un breakpoint** en cualquier línea (click en el margen izquierdo)
2. **Abre el panel de Debug** (Ctrl+Shift+D)
3. **Selecciona** "Launch Built-in web server"
4. **Presiona F5** (o click en "Start Debugging")
5. **Navega** en tu navegador a la ruta que quieres depurar

### Opción 2: Depurar con el Servidor Externo (php artisan serve)

1. **Inicia tu servidor Laravel:**
   ```bash
   php artisan serve
   ```

2. **En VS Code:**
   - Pon breakpoints donde quieras
   - Panel de Debug (Ctrl+Shift+D)
   - Selecciona "Listen for Xdebug"
   - Presiona F5

3. **Navega** a http://localhost:8000 y el debugger se detendrá en tus breakpoints

### Opción 3: Depurar Comandos Artisan

Para depurar comandos como `migrate`, `db:seed`, etc:

1. **Panel de Debug** (Ctrl+Shift+D)
2. **Selecciona:**
   - "Debug Artisan Command" (para cualquier comando)
   - "Debug Migrations" (para migraciones)
   - "Debug Seeder" (para seeders)
3. **Presiona F5**

Para cambiar el comando en "Debug Artisan Command", edita `.vscode/launch.json`:
```json
"args": [
    "tu:comando",
    "--opciones"
]
```

## 🎯 Configuraciones Disponibles en launch.json

| Configuración | Uso |
|--------------|-----|
| **Listen for Xdebug** | Escucha conexiones de Xdebug (usa con `php artisan serve`) |
| **Launch currently open script** | Depura el archivo PHP actual |
| **Launch Built-in web server** | Inicia servidor PHP con debugging |
| **Debug Artisan Command** | Depura comandos Artisan personalizados |
| **Debug Migrations** | Depura migraciones específicamente |
| **Debug Seeder** | Depura seeders específicamente |

## ⌨️ Atajos de Teclado

| Atajo | Acción |
|-------|--------|
| **F5** | Iniciar/Continuar debugging |
| **F9** | Poner/Quitar breakpoint |
| **F10** | Step Over (siguiente línea) |
| **F11** | Step Into (entrar a función) |
| **Shift+F11** | Step Out (salir de función) |
| **Shift+F5** | Detener debugging |
| **Ctrl+Shift+F5** | Reiniciar debugging |

## 🔍 Tips de Debugging

### 1. Ver Variables
Cuando el debugger se detiene, puedes ver:
- **Variables locales** en el panel izquierdo
- **Hover** sobre cualquier variable para ver su valor
- **Watch** para monitorear expresiones específicas

### 2. Evaluador de Expresiones
En el panel "Debug Console" puedes ejecutar código PHP:
```php
$user->email
count($alliances)
dd($match)
```

### 3. Breakpoints Condicionales
Click derecho en un breakpoint → "Edit Breakpoint":
```php
$user->id == 1
$alliance->name == "México"
```

### 4. Logpoints
En lugar de breakpoints, imprime valores sin detener:
```
Usuario: {$user->name}, Email: {$user->email}
```

## 🐛 Solución de Problemas

### "Cannot find Xdebug"
```bash
# Verifica que esté en php.ini
php -i | findstr xdebug

# Si no aparece, revisa la ruta de la extensión
zend_extension=C:\xampp\php\ext\php_xdebug.dll
```

### El debugger no se detiene
1. Verifica que Xdebug esté activo: `php -v`
2. Verifica el puerto en php.ini: `xdebug.client_port=9003`
3. Verifica que el breakpoint esté en una línea ejecutable (no en comentarios o espacios)
4. Reinicia Apache/servidor

### "Connection timeout"
```ini
# Agrega en php.ini:
xdebug.connect_timeout_ms=2000
```

### Firewall bloquea la conexión
Permite conexiones en el puerto 9003 para `php.exe`

## 📚 Recursos Adicionales

- [Xdebug Documentation](https://xdebug.org/docs/)
- [VS Code PHP Debug](https://marketplace.visualstudio.com/items?itemName=xdebug.php-debug)
- [Laravel Debugging](https://laravel.com/docs/10.x/debugging)

## 🎉 ¡Listo para Depurar!

Ahora puedes:
1. ✅ Poner breakpoints en cualquier parte de tu código Laravel
2. ✅ Inspeccionar variables en tiempo real
3. ✅ Depurar controladores, modelos, migraciones y seeders
4. ✅ Ver el flujo de ejecución paso a paso

**Ejemplo rápido:**
1. Abre `app/Http/Controllers/DashboardController.php`
2. Pon un breakpoint en la línea donde se obtienen los rankings
3. Presiona F5 y selecciona "Launch Built-in web server"
4. El navegador se abrirá automáticamente
5. ¡El debugger se detendrá en tu breakpoint!

---

**¿Problemas?** Revisa el log de Xdebug en `C:\xampp\tmp\xdebug.log`

