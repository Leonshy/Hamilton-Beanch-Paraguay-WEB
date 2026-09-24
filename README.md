# Hamilton Beach Paraguay — Sitio Web Oficial

Sitio web corporativo y catálogo de productos para **Distec**, distribuidor oficial de Hamilton Beach en Paraguay.
Construido con Laravel 12, Blade y Tailwind CSS v4. Frontend completo con panel de administración CMS.

---

## Stack técnico

| Capa | Tecnología |
|------|-----------|
| Backend | Laravel 12 + PHP 8.2 |
| Templates | Blade |
| CSS | Tailwind CSS v4 con `@theme` para tokens de marca |
| Editor WYSIWYG | TinyMCE 8 (self-hosted en `public/tinymce/`) |
| Permisos | Spatie Laravel Permission |
| Base de datos | MySQL (producción) · SQLite (desarrollo local) |
| Assets | Compilados localmente con Vite 7 + pnpm |
| Deploy | Script `deploy.sh` (SCP + git pull) |

> Los assets compilados (`public/build/`, `public/tinymce/`, `public/js/admin.js`) se suben al servidor via SCP. El servidor no corre npm/pnpm.

---

## Funcionalidades del CMS

### Panel de administración (`/admin`)

| Módulo | Descripción |
|--------|-------------|
| **Dashboard** | Resumen general |
| **Productos** | CRUD con galería, especificaciones, SKU, puntos de venta, PDF manual, SEO, preguntas frecuentes por producto |
| **Categorías** | CRUD con íconos SVG/emoji, ordenamiento drag-and-drop |
| **Banners** | Hero y banners intermedios con enlace opcional |
| **Anuncios** | Barra marquee superior configurable |
| **Puntos de venta** | CRUD con logo, URL y ordenamiento |
| **Páginas** | Contenido editable para Servicio Técnico, Manuales, Garantía y páginas genéricas |
| **Centro de Ayuda** | 4 secciones configurables (FAQ, Servicio, Manuales, Garantía) |
| **FAQs** | Preguntas frecuentes con editor de texto enriquecido |
| **Contactos** | Bandeja de mensajes recibidos del formulario. Cada envío dispara un email de notificación a la casilla de contacto configurada |
| **Biblioteca de Medios** | Subida de imágenes, PDFs y documentos (límite 64 MB). Las imágenes se redimensionan y convierten a WebP automáticamente; un archivo en uso (producto, categoría, banner, página, punto de venta) no se puede eliminar |
| **Usuarios** | Gestión de administradores con roles; usuarios protegidos (no editables ni eliminables desde el panel) |
| **Configuración** | General, Contacto, Redes sociales, Integraciones (GA4, Meta Pixel), Home. General/Integraciones/Modo mantenimiento son exclusivos del rol `admin` (un editor no puede inyectar scripts en el sitio) |

### Frontend público

- Catálogo de productos con búsqueda, filtro por categoría y ordenamiento
- Ficha de producto con galería, especificaciones, puntos de venta con logo, retailers personalizados, descarga de manual PDF y preguntas frecuentes propias del producto (la sección se oculta si no tiene ninguna cargada)
- Botón "Compartir" en la ficha de producto: usa el menú nativo del sistema (Web Share API) donde está disponible, y links directos a Facebook, X y WhatsApp en el resto de los navegadores
- El precio sugerido y el aviso "Disponible en puntos de venta" solo se muestran si el producto tiene esos datos cargados
- Carrusel de puntos de venta en homepage con orden aleatorio
- Páginas de soporte (Centro de Ayuda, FAQ, Servicio Técnico, Manuales, Garantía)
- Formulario de contacto con almacenamiento en BD, rate limiting (5/min), honeypot anti-spam y notificación por email
- Modo mantenimiento activable desde el admin
- Google Analytics, Meta Pixel y scripts personalizados inyectables desde el admin
- `sitemap.xml` y `robots.txt` dinámicos (basados en `APP_URL`)
- SEO: meta title/description, Open Graph, Twitter Card y `<link rel="canonical">` por página (con fallback automático si no se cargan a mano desde el admin); schema.org `Product` (JSON-LD) en la ficha de producto; redirect 301 de `www.` al dominio raíz
- Imágenes con `loading="lazy"` (salvo el hero y la imagen principal de producto, que llevan `fetchpriority="high"`)

---

## Rutas

### Frontend

| URL | Descripción |
|-----|-------------|
| `/` | Homepage |
| `/productos` | Catálogo con filtros |
| `/productos/{slug}` | Ficha de producto |
| `/preguntas-frecuentes` | FAQ |
| `/centro-ayuda` | Centro de ayuda |
| `/servicio-tecnico` | Servicio técnico |
| `/manuales-de-producto` | Manuales |
| `/garantia-de-producto` | Garantía |
| `/paginas/{slug}` | Páginas dinámicas del CMS |
| `/contacto` | Formulario de contacto |
| `/sitemap.xml` | Sitemap dinámico |
| `/robots.txt` | Robots dinámico |

### Admin

Todas las rutas bajo `/admin` con middleware de autenticación.

---

## Modelos principales

| Modelo | Tabla | Notas |
|--------|-------|-------|
| `Product` | `products` | SoftDeletes, relación con Category, Media (imagen + galería), SalePoints (M2M), retailers (JSON), FAQs (1:N) |
| `ProductFaq` | `product_faqs` | Preguntas frecuentes de un producto (pregunta, respuesta, orden) |
| `Category` | `categories` | Tipo (product/help), íconos |
| `Banner` | `banners` | Posición (home / home_mid), enlace opcional |
| `SalePoint` | `sale_points` | Logo via Media, relación M2M con productos |
| `Page` | `pages` | Secciones fijas + páginas libres, show_in_footer |
| `Faq` | `faqs` | Editor enriquecido en respuesta |
| `Announcement` | `announcements` | Textos del marquee |
| `Media` | `media` | Archivos subidos (image / document / video), Storage::disk('public') |
| `Contact` | `contacts` | Mensajes del formulario |
| `SiteSetting` | `site_settings` | Configuración clave-valor con caché de 1 hora |
| `User` | `users` | Roles vía Spatie Permission; `is_protected` marca usuarios que no se pueden editar ni eliminar desde el panel |

---

## Colores de marca

Definidos en `resources/css/app.css` con `@theme`:

| Token | Hex | Uso |
|-------|-----|-----|
| `brand` | `#387900` | Color principal |
| `brand-dark` | `#2d6200` | Hover, fondos oscuros |
| `brand-light` | `#f0f7e6` | Fondos suaves |
| `brand-muted` | `#c8e6a0` | Texto sobre fondo verde |

---

## Instalación local

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite   # si usás SQLite
php artisan migrate --seed
php artisan serve
```

Abrir: http://localhost:8000/admin  
Credenciales por defecto: `admin@hamiltonbeach.com.py` / `Admin1234!`

> No se necesita `pnpm run build` para desarrollo — los assets compilados ya están en `public/build` y `public/js/admin.js`.  
> Para ver cambios en CSS/JS del frontend, correr `pnpm run dev`.

---

## Comandos de consola

| Comando | Uso |
|---------|-----|
| `php artisan hb:create-super-admin {email} [--name=Webmaster]` | Crea o actualiza un admin protegido. Pide la contraseña por prompt oculto (no queda en el historial de bash). Se puede volver a correr sin duplicar el usuario. |
| `php artisan hb:import-product-images` | Importa imágenes de `public/images/products` al storage y las asocia a los productos semilla |
| `php artisan hb:optimize-media {--dry-run}` | Redimensiona (máx. 1600px de lado largo) y convierte a WebP las imágenes ya subidas a la biblioteca de medios. Idempotente — no vuelve a tocar una imagen ya optimizada. `--dry-run` muestra el ahorro proyectado sin modificar nada |

---

## Deploy

Ver [`DEPLOY.md`](DEPLOY.md) para la guía completa (incluye la distinción staging/producción).

Flujo rápido a **staging** con el script incluido:

```bash
git push origin main
./deploy.sh          # compila assets, SCP al servidor, git pull + artisan — solo staging
```

Para producción real (`hamiltonbeach.com.py`) el proceso es manual — ver `DEPLOY.md` sección 2.1.

---

## Servidor

⚠️ Hay **dos entornos separados** en el mismo servidor físico — no comparten carpeta ni base de datos. Ver [`DEPLOY.md`](DEPLOY.md) para el detalle completo.

| | Staging | Producción real |
|---|---|---|
| URL | http://hamilton.webparaguay.com | **https://hamiltonbeach.com.py** |
| Deploy | `./deploy.sh` (automatizado) | manual (ver `DEPLOY.md` sección 2.1) |

- **Hosting**: Plesk en 177.251.252.12
- **PHP CLI**: `/opt/plesk/php/8.2/bin/php`
- **Base de datos**: MySQL

---

## Seguridad

Auditoría de seguridad y rendimiento realizada el 2026-07-17. Estado actual:

- Dependencias PHP/JS sin vulnerabilidades conocidas (`composer audit` / `npm audit` en 0)
- Rate limiting (5 intentos/min) en el login del admin
- SVGs subidos a la biblioteca de medios se sanitizan automáticamente (`enshrined/svg-sanitize`) antes de guardarse
- Cabeceras de seguridad HTTP en todas las respuestas (`X-Frame-Options`, `X-Content-Type-Options`, `Referrer-Policy`, `Permissions-Policy`, `Strict-Transport-Security`) — middleware `App\Http\Middleware\SecurityHeaders`
- Contraseñas de usuarios admin: mínimo 10 caracteres con mayúscula, minúscula, número y símbolo; email validado con verificación de dominio DNS real
- Suite de tests automatizados (`php artisan test`, 46 tests) cubriendo login admin, permisos por rol, catálogo de productos, formulario de contacto, SEO, optimización de imágenes y protección de medios en uso
- Datos del "view composer" global (site settings, anuncios, categorías, páginas de footer) cacheados con invalidación automática al guardar desde el admin

Revisión de 2026-09-23:

- Usuarios protegidos (`users.is_protected`): ningún admin puede editarlos ni eliminarlos desde el panel. El campo no es asignable desde formularios; solo se activa con el comando `hb:create-super-admin`. En producción existe `webmaster@webparaguay.com` con esta protección.
- URLs personalizadas de puntos de venta por producto (`sale_point_url`) validadas como URL antes de guardarse (rechaza `javascript:` e intentos de inyección).
- Corregido el armado de los links de "¿Dónde comprar?" en la ficha de producto: el `href` salía con las comillas escapadas y mandaba a una ruta relativa rota.

Auditoría completa de seguridad/rendimiento/SEO/QA de 2026-09-24:

- **Configuración sensible restringida a `admin`**: las rutas de General, Integraciones y Modo mantenimiento solo exigían estar logueado — un editor podía entrar por URL directa e inyectar scripts en todo el sitio desde Integraciones. Ahora requieren `can:admin-only`.
- **Contacto endurecido**: rate limiting (`throttle:5,1`, igual que el login), honeypot invisible (campo `website`, finge éxito sin guardar si un bot lo completa), y notificación por email al `contact_email` configurado (con manejo de errores — un SMTP caído nunca rompe la experiencia del visitante).
- **URLs de retailers personalizados** (`retailers.url.*`) validadas, mismo criterio que `sale_point_url`.
- **Banners** (hero y mid): el link se armaba con `onclick="window.location.href='{{ $b->link_url }}'"`, sin escapar para contexto JS — ahora es un `<a href>` real.
- **`X-Powered-By`** suprimido de las respuestas (no expone la versión de PHP).
- **`SiteSetting::clearCache()`** dejó de usar `Cache::flush()` (borraba TODA la caché, incluidos los contadores de rate limiting del login) — ahora invalida solo sus propias claves.
- **Dependencias**: `league/commonmark` y `guzzlehttp/guzzle` actualizados (transitivos de Laravel, sin tocar `composer.json`); se saca `axios` del frontend (no se usaba, solo agregaba peso al JS público). `composer audit` y `pnpm audit`: 0 vulnerabilidades.
- **Imágenes optimizadas automáticamente**: `MediaService::upload()` redimensiona (máx. 1600px) y convierte a WebP calidad 82 al subir. Comando `hb:optimize-media` para reprocesar lo ya subido — en producción bajó la biblioteca completa de 253,8 MB a 7,5 MB.
- **SEO**: meta tags, Open Graph, Twitter Card, `canonical` (siempre a la versión sin filtros/sin duplicar por `/productos/{id}` vs `/productos/{slug}`) y schema.org `Product` por página. Redirect 301 `www.` → dominio raíz (confirmado que servía el mismo sitio duplicado).
- **Rendimiento**: `loading="lazy"` en imágenes secundarias, `fetchpriority="high"` en las críticas para LCP; Cache-Control/Expires agresivo (1 año, immutable) para `storage/` y `public/build/` (nombres de archivo únicos, nunca cambia el contenido); Bootstrap Icons con carga asíncrona (no bloquea el render inicial); view composer global acotado a `layouts.app` (antes corría también en cada vista del admin).
- **QA**: no se puede eliminar un archivo de la biblioteca que esté en uso; búsquedas (catálogo y biblioteca de medios) ya no tratan `%`/`_` como comodines de SQL cuando el usuario los escribe literal; código muerto eliminado (`MediaController::update` sin ruta).

> Pendiente: `Content-Security-Policy` — requiere mapear todos los dominios externos (Google Tag Manager, Facebook Pixel, Google Fonts, jsDelivr) antes de poder aplicarla sin romper integraciones.

---

**Estado**: CMS completo en producción — `hamiltonbeach.com.py` (staging separado en `hamilton.webparaguay.com`). Última auditoría integral: 2026-09-24.
