# Hamilton Beach Paraguay — Sitio Web Oficial

Sitio web corporativo y catálogo de productos para **Distec**, distribuidor oficial de Hamilton Beach en Paraguay.
Construido con Laravel 12, Blade y Tailwind CSS v4. Frontend completo con panel de administración CMS.

---

## Stack técnico

- **Laravel 12** + PHP 8.2
- **Blade** templates
- **Tailwind CSS v4** con `@theme` para tokens de marca
- **@tailwindcss/typography** para prose/rich text
- Assets compilados localmente (`public/build` incluido en git — el servidor no corre npm)

## Estructura de vistas

```
resources/views/
├── layouts/
│   └── app.blade.php             Layout base
├── partials/
│   ├── navbar.blade.php          Navbar 3 filas: marquee + logo/contacto + menú sticky con búsqueda
│   ├── footer.blade.php          Footer con logo, links y redes
│   └── cta-ayuda.blade.php       CTA "¿No encontraste tu respuesta?" reutilizable
├── index.blade.php               Inicio: hero, trust badges, productos destacados, categorías
├── productos.blade.php           Catálogo con sidebar de filtros y grilla de productos
├── producto-detalle.blade.php    Ficha: galería, descripción corta, prose body, puntos de venta
├── preguntas-frecuentes.blade.php
├── centro-ayuda.blade.php
├── servicio-tecnico.blade.php    Cuerpo editable vía texto enriquecido + cta-ayuda
├── manuales-de-producto.blade.php
├── garantia-de-producto.blade.php
└── contacto.blade.php            Héroe verde, datos con SVG, formulario, redes sociales
```

## Rutas

| URL | Vista |
|-----|-------|
| `/` | index |
| `/productos` | productos |
| `/productos/{id}` | producto-detalle |
| `/preguntas-frecuentes` | preguntas-frecuentes |
| `/centro-ayuda` | centro-ayuda |
| `/servicio-tecnico` | servicio-tecnico |
| `/manuales-de-producto` | manuales-de-producto |
| `/garantia-de-producto` | garantia-de-producto |
| `/contacto` | contacto |

## Colores de marca

Definidos en `resources/css/app.css` con `@theme`:

| Token | Hex | Uso |
|-------|-----|-----|
| `brand` | `#387900` | Color principal |
| `brand-dark` | `#2d6200` | Hover, fondos oscuros |
| `brand-light` | `#f0f7e6` | Fondos suaves |
| `brand-muted` | `#c8e6a0` | Texto sobre fondo verde |

## Instalación local

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

Abrir: http://localhost:8000

> No se necesita `npm run build` — los assets compilados están en `public/build`.

## Servidor de staging

- **URL**: http://hamilton.webparaguay.com *(staging — dominio de producción pendiente)*
- **Hosting**: Plesk en 177.251.252.12 (puerto SSH 53931, usuario `hamiltonprueba`)
- **Document root**: `httpdocs/public`
- **PHP**: 8.2 via Plesk
- **Base de datos**: MySQL (configurada en `.env` del servidor)

### Deploy / actualización

```bash
# En el servidor via SSH
ssh hamiltonprueba@177.251.252.12 -p 53931

cd ~/httpdocs
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan config:clear
php artisan cache:clear
php artisan migrate --force
```

> Los assets CSS/JS se compilan localmente y se suben con el commit. No correr `npm` en el servidor.

---

**Estado**: Frontend + panel admin CMS completo. Desplegado en staging `hamilton.webparaguay.com`.
