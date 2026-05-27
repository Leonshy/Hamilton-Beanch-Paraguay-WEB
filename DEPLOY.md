# Hamilton Beach Paraguay — Guía de Despliegue

**Dominio de producción:** `hamilton.webparaguay.com`
**Stack:** Laravel 12 · PHP 8.2 · MySQL 8.0 · Apache + Plesk · TinyMCE 8 (self-hosted)

---

## Tabla de contenidos
1. [Requisitos del servidor](#1-requisitos-del-servidor)
2. [Preparar los archivos localmente](#2-preparar-los-archivos-localmente)
3. [Despliegue en Plesk — Paso a paso](#3-despliegue-en-plesk--paso-a-paso)
4. [Configuración del .env en producción](#4-configuración-del-env-en-producción)
5. [Comandos post-despliegue](#5-comandos-post-despliegue)
6. [Configuración inicial desde el panel admin](#6-configuración-inicial-desde-el-panel-admin)
7. [Errores comunes y soluciones](#7-errores-comunes-y-soluciones)
8. [Checklist final antes de publicar](#8-checklist-final-antes-de-publicar)
9. [Credenciales iniciales](#9-credenciales-iniciales)

---

## 1. Requisitos del servidor

| Componente | Versión mínima |
|------------|----------------|
| PHP | 8.2 o superior |
| MySQL | 8.0 o superior |
| Composer | 2.x |
| Apache | con `mod_rewrite` habilitado |

**Extensiones PHP requeridas:**
`pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`, `gd`

**Ajustes PHP recomendados (en Plesk → PHP Settings):**
```
memory_limit = 256M
upload_max_filesize = 50M
post_max_size = 50M
max_execution_time = 120
```

---

## 2. Preparar los archivos localmente

Los assets ya están compilados (`public/build/` incluido en git). No se necesita correr npm/pnpm en el servidor.

Si se hacen cambios al frontend y se necesita recompilar:
```bash
pnpm run build
```

### Qué subir / qué NO subir

| Incluir | Excluir |
|---------|---------|
| Todo el proyecto | `node_modules/` |
| `public/tinymce/` ✅ | `.env` (se crea en el servidor) |
| `public/build/` (incluido en git) ✅ | `storage/logs/*.log` |
| `vendor/` se instala en servidor con Composer | `.git/` |
| `public/images/icons/` (iconos SVG) ✅ | `reference/` |

---

## 3. Despliegue en Plesk — Paso a paso

### Paso 1: Crear el dominio en Plesk
1. Ingresar al panel Plesk con usuario `hamiltonprueba`
2. Ir a **Sitios Web y Dominios** → **Agregar dominio**
3. Configurar: `hamilton.webparaguay.com`
4. Anotar la ruta del **Document Root** (ej.: `/var/www/vhosts/webparaguay.com/hamilton.webparaguay.com/`)

### Paso 2: Configurar PHP en Plesk
1. Ir al dominio → **PHP**
2. Seleccionar **PHP 8.2** o superior
3. Ajustar los valores de memoria y tamaño de archivos (ver sección 1)

### Paso 3: Crear la base de datos MySQL
1. En Plesk → **Bases de datos** → **Agregar base de datos**
2. Nombre sugerido: `hamiltonbeach`
3. Crear usuario con todos los privilegios sobre esa DB
4. Anotar: host, nombre de DB, usuario y contraseña

### Paso 4: Subir los archivos

**Opción A — rsync por SSH (recomendado)**
```bash
rsync -avz \
  --exclude='node_modules' \
  --exclude='.git' \
  --exclude='.env' \
  --exclude='storage/logs' \
  --exclude='reference' \
  ./ usuario@hamilton.webparaguay.com:/ruta/al/proyecto/
```

**Opción B — FileZilla (FTP/SFTP)**
1. Conectar por SFTP
2. Subir todo el proyecto (excepto `node_modules/`, `.git/`, `reference/`) a la carpeta del proyecto
3. Verificar que `public/tinymce/` y `public/images/icons/` se hayan subido completos

### Paso 5: Instalar dependencias PHP en el servidor
```bash
ssh usuario@hamilton.webparaguay.com
cd /ruta/al/proyecto
composer install --no-dev --optimize-autoloader
```

### Paso 6: Configurar el Document Root
Laravel requiere que el Document Root apunte a `public/`, no a la raíz del proyecto.

1. En Plesk → dominio → **Configuración del hosting** → **Document Root**
2. Cambiar a:
   ```
   /ruta/al/proyecto/public
   ```

**Si no se puede cambiar el Document Root**, crear un `.htaccess` en `httpdocs/`:
```apache
RewriteEngine On
RewriteRule ^(.*)$ /ruta/al/proyecto/public/$1 [L]
```

### Paso 7: Crear y configurar el .env
```bash
cd /ruta/al/proyecto
cp .env.example .env
nano .env
```

Ver la sección siguiente con todos los valores exactos.

### Paso 8: Generar la APP_KEY
```bash
php artisan key:generate
```

### Paso 9: Ejecutar migraciones y seeders
```bash
php artisan migrate --force
php artisan db:seed --force
```

### Paso 10: Crear el enlace de storage
```bash
php artisan storage:link
```
Esto crea el symlink `public/storage → storage/app/public` para servir archivos subidos.

### Paso 11: Configurar permisos
```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chmod -R 775 storage/app
chmod -R 775 storage/framework
chmod -R 775 storage/logs
chown -R www-data:www-data storage bootstrap/cache
```

### Paso 12: Optimizar para producción
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer dump-autoload --optimize
```

---

## 4. Configuración del .env en producción

```env
APP_NAME="Hamilton Beach Paraguay"
APP_ENV=production
APP_KEY=                         # se genera con php artisan key:generate
APP_DEBUG=false
APP_URL=https://hamilton.webparaguay.com

APP_LOCALE=es
APP_FALLBACK_LOCALE=es
APP_FAKER_LOCALE=es_PY

LOG_CHANNEL=single
LOG_LEVEL=error

# ─── BASE DE DATOS ───────────────────────────────────────
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hamiltonbeach
DB_USERNAME=usuario_db
DB_PASSWORD=contraseña_db

# ─── SESIÓN ──────────────────────────────────────────────
SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=public
QUEUE_CONNECTION=sync
CACHE_STORE=file

# ─── CORREO SMTP ─────────────────────────────────────────
MAIL_MAILER=smtp
MAIL_SCHEME=ssl
MAIL_HOST=mail.webparaguay.com
MAIL_PORT=465
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_FROM_ADDRESS="noreply@hamilton.webparaguay.com"
MAIL_FROM_NAME="Hamilton Beach Paraguay"

# ─── EDITOR WYSIWYG ──────────────────────────────────────
# TinyMCE está auto-hospedado en public/tinymce/ — no requiere API key
TINYMCE_API_KEY=no-api-key

VITE_APP_NAME="${APP_NAME}"
```

---

## 5. Comandos post-despliegue

```bash
# Verificar que todo esté en orden
php artisan about

# Si algo falla, limpiar todos los cachés
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

---

## 6. Configuración inicial desde el panel admin

URL del panel: `https://hamilton.webparaguay.com/admin`

### Al ingresar por primera vez:
1. **Cambiar la contraseña del admin** (ver sección 9)
2. Ir a **Configuración del sitio**:
   - Subir el logo
   - Completar nombre, descripción, datos de contacto
3. Ir a **Banners**:
   - Crear el banner principal del hero de inicio
4. Ir a **Categorías**:
   - Verificar que las 4 categorías (Cafeteras, Tostadoras, Pavas, Molinillos) estén activas con sus íconos
5. Ir a **Productos**:
   - Cargar catálogo con imágenes y precios
6. Ir a **Anuncios**:
   - Configurar los textos del banner superior de desplazamiento
7. Ir a **Centro de ayuda**:
   - Verificar los 4 ítems (FAQ, Servicio Técnico, Manuales, Garantía)

---

## 7. Errores comunes y soluciones

### "No application encryption key has been specified"
```bash
php artisan key:generate
```

### Error 500 al cargar el sitio
```bash
# Activar debug temporalmente para ver el error
APP_DEBUG=true  # en .env
php artisan config:cache

# Revisar el log
tail -100 storage/logs/laravel.log

# Volver a desactivar debug
APP_DEBUG=false
php artisan config:cache
```

### Las imágenes subidas no se muestran
```bash
ls -la public/storage
php artisan storage:link
# Verificar APP_URL en .env (debe ser https://hamilton.webparaguay.com sin barra final)
```

### Error 404 en rutas (solo carga la página de inicio)
Apache no está aplicando el `.htaccess`:
1. Verificar que `mod_rewrite` esté habilitado
2. Verificar que `AllowOverride All` esté configurado
3. En Plesk → **Configuración de Apache** → habilitar `AllowOverride All`

### Íconos SVG de categorías no aparecen
```bash
ls public/images/icons/
# Deben estar los 18 archivos .svg (coffee-maker.svg, toaster.svg, etc.)
```

### El editor TinyMCE no aparece
```bash
ls -la public/tinymce/tinymce.min.js
# Si no existe, fue excluida al subir — re-subir la carpeta public/tinymce/ completa
```

### Error de conexión a MySQL
- Verificar datos de DB en `.env`
- En Plesk, el host suele ser `127.0.0.1` o `localhost`

### Caché antigua después de cambios
```bash
php artisan config:clear && php artisan route:clear && php artisan view:clear && php artisan cache:clear
```

---

## 8. Checklist final antes de publicar

### Servidor
- [ ] PHP 8.2+ con todas las extensiones
- [ ] Document Root apunta a `public/`
- [ ] `.env` configurado con datos reales de producción
- [ ] `APP_DEBUG=false`
- [ ] `APP_URL=https://hamilton.webparaguay.com`
- [ ] Certificado SSL/HTTPS instalado y activo
- [ ] `mod_rewrite` habilitado en Apache

### Base de datos
- [ ] Migraciones ejecutadas (`php artisan migrate --force`)
- [ ] Seeders ejecutados (`php artisan db:seed --force`)
- [ ] Conexión verificada

### Storage y archivos
- [ ] `php artisan storage:link` ejecutado
- [ ] Permisos correctos en `storage/` y `bootstrap/cache/`
- [ ] `public/.htaccess` presente
- [ ] `public/tinymce/` subido completo
- [ ] `public/images/icons/` con los 18 SVGs
- [ ] `public/build/` (assets compilados) presente

### Panel admin
- [ ] Acceso verificado en `https://hamilton.webparaguay.com/admin`
- [ ] Contraseña del admin cambiada
- [ ] Logo y favicon subidos
- [ ] Datos de contacto actualizados
- [ ] Banners del hero cargados
- [ ] Categorías con íconos verificadas

### Frontend
- [ ] Página de inicio carga correctamente
- [ ] Íconos de categorías visibles
- [ ] Banner de anuncios desplazándose
- [ ] Navegación funciona en desktop y mobile
- [ ] Catálogo de productos filtra por categoría
- [ ] Detalle de producto carga correctamente
- [ ] Formulario de contacto funciona
- [ ] Centro de ayuda muestra los 4 ítems con sus íconos

### Optimización
- [ ] `php artisan config:cache`
- [ ] `php artisan route:cache`
- [ ] `php artisan view:cache`
- [ ] `composer dump-autoload --optimize`

---

## 9. Credenciales iniciales

| Rol | Email | Contraseña |
|-----|-------|------------|
| Administrador | `admin@hamiltonbeach.com.py` | `Admin1234!` |

> **Cambiar la contraseña inmediatamente después del primer acceso.**

---

## Soporte técnico

**Stack:** Laravel 12 · PHP 8.2 · MySQL 8.0 · Apache + Plesk · TinyMCE 8 (self-hosted) · Tailwind CSS v4 · Vite 7
