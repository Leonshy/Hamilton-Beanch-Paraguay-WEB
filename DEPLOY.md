# Hamilton Beach Paraguay — Guía de Despliegue

**Stack:** Laravel 12 · PHP 8.2 · MySQL · Apache + Plesk · TinyMCE 8 (self-hosted) · Tailwind CSS v4 · Vite 7

## ⚠️ Dos entornos separados — no confundir

Este proyecto tiene **dos instalaciones completamente independientes** en el mismo servidor físico (177.251.252.12), cada una con su propia carpeta, base de datos y usuario de sistema. No comparten nada entre sí — actualizar una NO actualiza la otra.

| | **Staging** | **Producción real** |
|---|---|---|
| Dominio | `hamilton.webparaguay.com` | **`hamiltonbeach.com.py`** |
| Carpeta | `/var/www/vhosts/hamilton.webparaguay.com/httpdocs` | `/var/www/vhosts/hamiltonbeach.com.py/httpdocs` |
| Usuario de sistema | `hamiltonprueba` | `hamilton` (usuario distinto, con su propio acceso SSH) |
| Base de datos | `hamilton_pru_db` | `hamilton_db` |
| `APP_URL` | `http://hamilton.webparaguay.com` (sin HTTPS) | `https://hamiltonbeach.com.py` |
| `deploy.sh` del repo | ✅ apunta acá | ❌ el script **no** llega acá |

**El script `deploy.sh` incluido en el repo solo despliega a staging.** Para publicar en producción real hay que repetir el proceso manual a mano con el usuario SSH de `hamilton` (ver sección 2.1). Esto se descubrió el 2026-07-17 al auditar el sitio: alguien había cargado un script de Google Tag Manager desde el panel admin de staging pensando que era el sitio real, y no se reflejaba en `hamiltonbeach.com.py` porque son bases de datos distintas.

---

## Tabla de contenidos
1. [Requisitos del servidor](#1-requisitos-del-servidor)
2. [Deploy rutinario a staging (automatizado)](#2-deploy-rutinario-a-staging-automatizado)
   - [2.1 Deploy manual a producción real](#21-deploy-manual-a-producción-real-hamiltonbeachcompy)
3. [Primer despliegue desde cero](#3-primer-despliegue-desde-cero)
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

**Ajustes PHP recomendados (Plesk → PHP Settings del dominio):**
```
memory_limit = 256M
upload_max_filesize = 64M
post_max_size = 64M
max_execution_time = 120
```

> El límite de 64 MB también está validado a nivel de Laravel y en el navegador (client-side).

---

## 2. Deploy rutinario a staging (automatizado)

Para cada actualización de código en **staging** (`hamilton.webparaguay.com`), usar el script `deploy.sh` incluido en el proyecto:

```bash
git push origin main   # subir commits a GitHub
./deploy.sh            # compilar + copiar assets + git pull en servidor
```

El script hace automáticamente:
1. Compila assets con `pnpm run build`
2. Copia `public/build/` y `public/tinymce/` al servidor por SCP
3. En el servidor: `git pull origin main`
4. Corre `php artisan migrate --force`, `config:cache`, `route:cache`, `event:cache` y `view:clear`

**Importante:** El script usa el binario `/opt/plesk/php/8.2/bin/php` para los comandos artisan (el PHP del sistema en Plesk es una versión antigua).

### 2.1 Deploy manual a producción real (`hamiltonbeach.com.py`)

No hay script automatizado todavía — se hace a mano, conectado con el usuario SSH que tiene acceso a esa carpeta (no es `hamiltonprueba`, es un usuario distinto asociado a `hamilton`).

```bash
ssh <usuario-produccion>@177.251.252.12 -p <puerto>
cd /var/www/vhosts/hamiltonbeach.com.py/httpdocs

# 1. Traer el código nuevo
git fetch origin +refs/heads/main:refs/remotes/origin/main   # ver nota sobre CentOS 7 más abajo
git pull origin main

# 2. Dependencias PHP (sin paquetes de desarrollo)
composer install --no-dev --optimize-autoloader

# 3. Migraciones
php artisan migrate:status     # revisar qué falta antes de correr --force
php artisan migrate --force

# 4. Assets compilados (solo si el cambio tocó CSS/JS de resources/)
#    Si el deploy es solo backend/PHP no hace falta este paso.
#    Compilar localmente con `pnpm run build` y subir por SCP con las
#    credenciales de producción: public/build/ y public/tinymce/

# 5. Cachés
php artisan config:cache
php artisan route:cache
php artisan event:cache
php artisan view:clear
```

**⚠️ Bug conocido de `git fetch` en CentOS 7 (git 1.8.3.1):**
Este servidor corre CentOS 7 con git muy viejo (2013). Con `git fetch origin main` (nombre de rama sin refspec completo), git actualiza `FETCH_HEAD` pero **no mueve el ref `origin/main`**, y comandos como `git log HEAD..origin/main` van a mostrar vacío aunque haya commits nuevos en GitHub. La solución es usar el refspec explícito:
```bash
git fetch origin +refs/heads/main:refs/remotes/origin/main
```
Con eso sí se actualiza `origin/main` correctamente y `git pull` funciona sin problema. Si algún día se actualiza el git del servidor (`yum install git` con un repo más nuevo, o IUS/SCL), este workaround ya no debería hacer falta.

**Diagnóstico rápido si un `git pull`/`git fetch` "no trae nada" pero GitHub sí tiene el commit:**
```bash
git ls-remote origin main                      # confirma el SHA real en GitHub (desde tu máquina local)
curl -s https://api.github.com/repos/Leonshy/Hamilton-Beanch-Paraguay-WEB/commits/main | grep '"sha"'
git --version                                   # si es 1.8.x, aplica el workaround de arriba
```

### Después de un deploy con cambios en vistas

Si el deploy corrió correctamente, las vistas ya se limpian automáticamente. Si algo no se refleja:

```bash
# En el servidor
/opt/plesk/php/8.2/bin/php artisan view:clear
/opt/plesk/php/8.2/bin/php artisan config:cache
```

### Si git pull falla por conflictos

```bash
# En el servidor — descartar cambios locales y forzar pull
git checkout -- .
git pull origin main
```

---

## 3. Primer despliegue desde cero

### Paso 1: Crear el dominio en Plesk
1. Ingresar al panel Plesk
2. Ir a **Sitios Web y Dominios** → **Agregar dominio**
3. Configurar el Document Root para que apunte a `httpdocs/public`

### Paso 2: Configurar PHP en Plesk
1. Ir al dominio → **PHP**
2. Seleccionar **PHP 8.2**
3. Ajustar memoria y tamaño de archivos (ver sección 1)

### Paso 3: Crear la base de datos MySQL
1. En Plesk → **Bases de datos** → **Agregar base de datos**
2. Crear usuario con todos los privilegios
3. Anotar: host, nombre, usuario y contraseña

### Paso 4: Clonar el repositorio en el servidor

```bash
cd /var/www/vhosts/hamilton.webparaguay.com/
git clone https://github.com/Leonshy/Hamilton-Beanch-Paraguay-WEB.git httpdocs
cd httpdocs
```

### Paso 5: Instalar dependencias PHP

```bash
/opt/plesk/php/8.2/bin/php /usr/bin/composer install --no-dev --optimize-autoloader
```

### Paso 6: Configurar el .env

```bash
cp .env.example .env
nano .env   # completar con valores reales (ver sección 4)
```

### Paso 7: Generar APP_KEY y ejecutar migraciones

```bash
/opt/plesk/php/8.2/bin/php artisan key:generate
/opt/plesk/php/8.2/bin/php artisan migrate --force
/opt/plesk/php/8.2/bin/php artisan db:seed --force
```

### Paso 8: Crear enlace de storage

```bash
/opt/plesk/php/8.2/bin/php artisan storage:link
```

Verifica que exista el symlink: `public/storage → storage/app/public`

### Paso 9: Configurar permisos

```bash
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage/app storage/framework storage/logs
```

### Paso 10: Compilar assets y subir (desde local)

```bash
pnpm run build
./deploy.sh   # o copiar manualmente public/build/ y public/tinymce/
```

### Paso 11: Optimizar

```bash
/opt/plesk/php/8.2/bin/php artisan config:cache
/opt/plesk/php/8.2/bin/php artisan route:cache
/opt/plesk/php/8.2/bin/php artisan view:cache
```

---

## 4. Configuración del .env en producción

> Reemplazar `APP_URL`, `DB_DATABASE`, `DB_HOST` según el entorno (staging o producción real — ver tabla al inicio de este documento).

```env
APP_NAME="Hamilton Beach Paraguay"
APP_ENV=production
APP_KEY=                         # se genera con artisan key:generate
APP_DEBUG=false
APP_URL=https://hamiltonbeach.com.py    # en staging: http://hamilton.webparaguay.com

APP_LOCALE=es
APP_FALLBACK_LOCALE=es
APP_FAKER_LOCALE=es_PY

LOG_CHANNEL=single
LOG_LEVEL=error

# ─── SEGURIDAD ───────────────────────────────────────────
# Obligatorio si el sitio corre con HTTPS (producción real sí; confirmar en staging)
SESSION_SECURE_COOKIE=true

# ─── BASE DE DATOS ───────────────────────────────────────
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hamilton_db          # en staging: hamilton_pru_db
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

VITE_APP_NAME="${APP_NAME}"
```

---

## 5. Comandos post-despliegue

```bash
# Ver estado general de la aplicación
/opt/plesk/php/8.2/bin/php artisan about

# Limpiar todos los cachés (usar si algo no se refleja)
/opt/plesk/php/8.2/bin/php artisan config:clear
/opt/plesk/php/8.2/bin/php artisan route:clear
/opt/plesk/php/8.2/bin/php artisan view:clear
/opt/plesk/php/8.2/bin/php artisan cache:clear
```

---

## 6. Configuración inicial desde el panel admin

URL del panel: `https://hamilton.webparaguay.com/admin`

### Al ingresar por primera vez:
1. **Cambiar la contraseña del admin**
2. Ir a **Configuración → General**:
   - Subir logo y favicon
   - Completar tagline, meta description, datos de contacto
3. Ir a **Configuración → Redes sociales**: completar URLs
4. Ir a **Configuración → Integraciones**: configurar GA4 y/o Meta Pixel si corresponde
5. Ir a **Banners**: crear el banner principal del hero
6. Ir a **Anuncios**: configurar los textos del marquee superior
7. Ir a **Categorías**: verificar que las categorías estén activas con sus íconos
8. Ir a **Puntos de Venta**: cargar los distribuidores con sus logos
9. Ir a **Productos**: cargar el catálogo completo
10. Ir a **Centro de Ayuda**: verificar los 4 ítems (FAQ, Servicio Técnico, Manuales, Garantía)

---

## 7. Errores comunes y soluciones

### "No application encryption key has been specified"
```bash
/opt/plesk/php/8.2/bin/php artisan key:generate
```

### Error 500 al cargar el sitio
```bash
# Activar debug temporalmente
# En .env: APP_DEBUG=true
/opt/plesk/php/8.2/bin/php artisan config:cache

# Revisar el log
tail -100 storage/logs/laravel.log

# Volver a desactivar
# En .env: APP_DEBUG=false
/opt/plesk/php/8.2/bin/php artisan config:cache
```

### Las imágenes subidas no se muestran (404)
```bash
ls -la public/storage
# Si no existe el symlink:
/opt/plesk/php/8.2/bin/php artisan storage:link
# Verificar que APP_URL en .env sea correcto (sin barra final)
```

### Error 404 en rutas (solo carga la página de inicio)
Apache no está aplicando el `.htaccess`:
1. Verificar que `mod_rewrite` esté habilitado
2. En Plesk → **Configuración de Apache** → `AllowOverride All`

### git pull falla con "local changes would be overwritten"
```bash
git checkout -- public/js/admin.js   # o el archivo en conflicto
git pull origin main
```

> Esto ocurre si un deploy previo copió un archivo por SCP antes del git pull.

### Comandos artisan fallan con error de PHP version
```bash
# Usar siempre el binario correcto de Plesk:
/opt/plesk/php/8.2/bin/php artisan <comando>
# NO usar `php artisan` a secas (apunta a PHP 5.4 del sistema)
```

### Caché antigua después de cambios
```bash
/opt/plesk/php/8.2/bin/php artisan config:clear && \
/opt/plesk/php/8.2/bin/php artisan route:clear && \
/opt/plesk/php/8.2/bin/php artisan view:clear && \
/opt/plesk/php/8.2/bin/php artisan cache:clear
```

### El editor TinyMCE no aparece
```bash
ls -la public/tinymce/tinymce.min.js
# Si no existe, correr desde local:
./deploy.sh   # el script re-sube public/tinymce/ completo
```

---

## 8. Checklist final antes de publicar

### Servidor
- [ ] PHP 8.2+ con todas las extensiones requeridas
- [ ] Document Root apunta a `public/`
- [ ] `upload_max_filesize = 64M` y `post_max_size = 64M` en Plesk PHP Settings
- [ ] `.env` configurado con datos reales de producción
- [ ] `APP_DEBUG=false`
- [ ] `APP_URL=https://hamilton.webparaguay.com` (sin barra final)
- [ ] Certificado SSL/HTTPS instalado y activo
- [ ] `mod_rewrite` habilitado en Apache

### Base de datos
- [ ] Migraciones ejecutadas (`artisan migrate --force`)
- [ ] Seeders ejecutados (`artisan db:seed --force`)
- [ ] Conexión verificada

### Storage y archivos
- [ ] `artisan storage:link` ejecutado — symlink `public/storage` existe
- [ ] Permisos correctos en `storage/` y `bootstrap/cache/`
- [ ] `public/.htaccess` presente
- [ ] `public/tinymce/` subido completo (via deploy.sh)
- [ ] `public/build/` presente (via deploy.sh)
- [ ] `public/js/admin.js` presente (via git pull)

### Panel admin
- [ ] Acceso verificado en `/admin`
- [ ] Contraseña del admin cambiada
- [ ] Logo y favicon subidos en Configuración → General
- [ ] Datos de contacto y redes sociales completados
- [ ] Banners del hero cargados
- [ ] Categorías con íconos verificadas
- [ ] Puntos de venta con logos cargados

### Frontend
- [ ] Homepage carga correctamente
- [ ] Sitemap accesible en `/sitemap.xml`
- [ ] Robots accesible en `/robots.txt`
- [ ] Catálogo de productos filtra por categoría
- [ ] Ficha de producto muestra puntos de venta y retailers personalizados
- [ ] Manual PDF descargable (si corresponde)
- [ ] Formulario de contacto guarda mensajes en BD
- [ ] Centro de Ayuda muestra los 4 ítems

### Optimización
- [ ] `artisan config:cache`
- [ ] `artisan route:cache`
- [ ] `artisan event:cache`
- [ ] `artisan view:cache`

### Seguridad (auditoría 2026-07-17)
- [ ] `composer audit` en 0 vulnerabilidades
- [ ] Rate limiting activo en `/admin/login` (5 intentos/min)
- [ ] Cabeceras de seguridad presentes (`X-Frame-Options`, `X-Content-Type-Options`, `Referrer-Policy`, `Permissions-Policy`, `Strict-Transport-Security` si hay HTTPS) — verificar con `curl -I https://hamiltonbeach.com.py/`
- [ ] Contraseñas de usuarios admin nuevos: mínimo 10 caracteres, mayúscula, minúscula, número y símbolo (se valida automático en el form)
- [ ] Subida de SVG sanitizada automáticamente (`enshrined/svg-sanitize`) — no requiere acción manual

---

## 9. Credenciales iniciales

| Rol | Email | Contraseña |
|-----|-------|------------|
| Administrador | `admin@hamiltonbeach.com.py` | `Admin1234!` |

> **Cambiar la contraseña inmediatamente después del primer acceso.** Desde la auditoría de 2026-07-17, las contraseñas nuevas o cambios de contraseña deben tener mínimo 10 caracteres con mayúscula, minúscula, número y símbolo — la de esta tabla ya cumple el requisito.
