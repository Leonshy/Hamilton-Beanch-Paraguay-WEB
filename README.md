# Hamilton Beach Paraguay - Frontend

Frontend de catálogo de productos Hamilton Beach para Paraguay, construido con Laravel 11, Blade y Tailwind CSS.

## 🎨 Características

- ✨ Diseño responsive moderno usando Tailwind CSS
- 📱 Compatible con todos los dispositivos
- 🛍️ Catálogo de productos con filtros
- 📦 Página de detalle de producto con múltiples retailers
- ❓ Sección de preguntas frecuentes
- 🎯 Centro de ayuda completo
- 📧 Formulario de contacto
- 🎨 Diseño replicado del sitio de Argentina adaptado a Paraguay

## 🚀 Instalación Rápida

### Terminal:
```bash
cd /Users/leonshy/Hamilton-Beanch-Paraguay-WEB
php artisan serve
```

Luego abre: http://localhost:8000

## 📂 Estructura del Proyecto

**Vistas Creadas:**
- `index.blade.php` - Página de inicio
- `productos.blade.php` - Catálogo
- `producto-detalle.blade.php` - Detalle de producto  
- `preguntas-frecuentes.blade.php` - FAQs
- `centro-ayuda.blade.php` - Centro de ayuda
- `contacto.blade.php` - Formulario de contacto

**Componentes Reutilizables:**
- `layouts/app.blade.php` - Layout base
- `partials/navbar.blade.php` - Navegación
- `partials/footer.blade.php` - Pie de página

## 🔀 Rutas Disponibles

- `/` - Inicio
- `/productos` - Catálogo
- `/productos/{id}` - Detalle de producto
- `/preguntas-frecuentes` - FAQs
- `/centro-ayuda` - Ayuda
- `/contacto` - Contacto

## 🎨 Stack Técnico

✅ Laravel 11  
✅ PHP 8.x  
✅ Blade Templates  
✅ Tailwind CSS  
✅ Responsive Design  

## ⚙️ Configuración Inicial

Si es primera vez:
```bash
composer install
npm install
npm run dev
cp .env.example .env
php artisan key:generate
php artisan serve
```

## 📝 Datos

Todos los datos son **hardcodeados** para visualización. Listos para conectar a BD cuando lo necesites.

---

**Status**: ✅ Frontend Completo - Listo para visualizar  
**Próximo**: Conectar Backend + Base de Datos
