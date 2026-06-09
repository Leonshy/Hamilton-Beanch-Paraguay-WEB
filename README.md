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
| **Productos** | CRUD con galería, especificaciones, SKU, puntos de venta, PDF manual, SEO |
| **Categorías** | CRUD con íconos SVG/emoji, ordenamiento drag-and-drop |
| **Banners** | Hero y banners intermedios con enlace opcional |
| **Anuncios** | Barra marquee superior configurable |
| **Puntos de venta** | CRUD con logo, URL y ordenamiento |
| **Páginas** | Contenido editable para Servicio Técnico, Manuales, Garantía y páginas genéricas |
| **Centro de Ayuda** | 4 secciones configurables (FAQ, Servicio, Manuales, Garantía) |
| **FAQs** | Preguntas frecuentes con editor de texto enriquecido |
| **Contactos** | Bandeja de mensajes recibidos del formulario |
| **Biblioteca de Medios** | Subida de imágenes, PDFs y documentos (límite 64 MB) |
| **Usuarios** | Gestión de administradores con roles |
| **Configuración** | General, Contacto, Redes sociales, Integraciones (GA4, Meta Pixel), Home |

### Frontend público

- Catálogo de productos con búsqueda, filtro por categoría y ordenamiento
- Ficha de producto con galería, especificaciones, puntos de venta con logo, retailers personalizados y descarga de manual PDF
- Carrusel de puntos de venta en homepage con orden aleatorio
- Páginas de soporte (Centro de Ayuda, FAQ, Servicio Técnico, Manuales, Garantía)
- Formulario de contacto con almacenamiento en BD
- Modo mantenimiento activable desde el admin
- Google Analytics, Meta Pixel y scripts personalizados inyectables desde el admin
- `sitemap.xml` y `robots.txt` dinámicos (basados en `APP_URL`)

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
| `Product` | `products` | SoftDeletes, relación con Category, Media (imagen + galería), SalePoints (M2M), retailers (JSON) |
| `Category` | `categories` | Tipo (product/help), íconos |
| `Banner` | `banners` | Posición (home / home_mid), enlace opcional |
| `SalePoint` | `sale_points` | Logo via Media, relación M2M con productos |
| `Page` | `pages` | Secciones fijas + páginas libres, show_in_footer |
| `Faq` | `faqs` | Editor enriquecido en respuesta |
| `Announcement` | `announcements` | Textos del marquee |
| `Media` | `media` | Archivos subidos (image / document / video), Storage::disk('public') |
| `Contact` | `contacts` | Mensajes del formulario |
| `SiteSetting` | `site_settings` | Configuración clave-valor con caché de 1 hora |
| `User` | `users` | Roles vía Spatie Permission |

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

## Deploy

Ver [`DEPLOY.md`](DEPLOY.md) para la guía completa.

Flujo rápido con el script incluido:

```bash
git push origin main
./deploy.sh          # compila assets, SCP al servidor, git pull + artisan
```

---

## Servidor

- **URL**: http://hamilton.webparaguay.com
- **Hosting**: Plesk en 177.251.252.12 (puerto SSH 53931)
- **Document root**: `/httpdocs/public`
- **PHP CLI**: `/opt/plesk/php/8.2/bin/php`
- **Base de datos**: MySQL

---

**Estado**: CMS completo en producción — `hamilton.webparaguay.com`.
