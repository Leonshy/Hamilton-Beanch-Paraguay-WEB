<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Frontend;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
*/
Route::get('/',                    [Frontend\HomeController::class,    'index'])->name('frontend.home');
Route::get('/productos',           [Frontend\ProductController::class, 'index'])->name('frontend.products.index');
Route::get('/productos/{product}', [Frontend\ProductController::class, 'show'])->name('frontend.products.show');
Route::get('/preguntas-frecuentes',[Frontend\PageController::class,    'preguntasFrecuentes'])->name('frontend.faqs');
Route::get('/centro-ayuda',        [Frontend\PageController::class,    'centroAyuda'])->name('frontend.help');
Route::get('/servicio-tecnico',    [Frontend\PageController::class,    'servicioTecnico'])->name('frontend.service');
Route::get('/manuales-de-producto',[Frontend\PageController::class,    'manuales'])->name('frontend.manuals');
Route::get('/garantia-de-producto',[Frontend\PageController::class,    'garantia'])->name('frontend.warranty');
Route::get('/paginas/{slug}',      [Frontend\PageController::class,   'show'])->name('frontend.pages.show');
Route::get('/contacto',            [Frontend\ContactController::class, 'index'])->name('frontend.contact');
Route::post('/contacto',           [Frontend\ContactController::class, 'store'])->name('frontend.contact.store');
Route::get('/sitemap.xml',         [Frontend\SitemapController::class, 'sitemap'])->name('frontend.sitemap');
Route::get('/robots.txt',          [Frontend\SitemapController::class, 'robots'])->name('frontend.robots');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {

    // Auth (unauthenticated)
    Route::get('/login',  [Admin\AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [Admin\AuthController::class, 'login'])->name('login.post');

    // Authenticated admin routes
    Route::middleware('admin')->group(function () {

        Route::post('/logout', [Admin\AuthController::class, 'logout'])->name('logout');

        Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

        // Products
        Route::resource('products',   Admin\ProductController::class);

        // Categories
        Route::post('/categories/reorder', [Admin\CategoryController::class, 'reorder'])->name('categories.reorder');
        Route::resource('categories', Admin\CategoryController::class);

        // Media
        Route::get('/media',         [Admin\MediaController::class, 'index'])->name('media.index');
        Route::post('/media',        [Admin\MediaController::class, 'store'])->name('media.store');
        Route::delete('/media/{media}', [Admin\MediaController::class, 'destroy'])->name('media.destroy');
        Route::get('/media/picker',  [Admin\MediaController::class, 'picker'])->name('media.picker');

        // Announcements (barra marquee)
        Route::post('/announcements/reorder', [Admin\AnnouncementController::class, 'reorder'])->name('announcements.reorder');
        Route::resource('announcements', Admin\AnnouncementController::class);

        // Pages
        Route::resource('pages', Admin\PageController::class);

        // Help Center (gestión de ítems de Centro de Ayuda)
        Route::get('/help-center',              [Admin\HelpCenterController::class, 'index'])->name('help-center.index');
        Route::put('/help-center/{page}',       [Admin\HelpCenterController::class, 'update'])->name('help-center.update');

        // FAQs
        Route::post('/faqs/reorder', [Admin\FaqController::class, 'reorder'])->name('faqs.reorder');
        Route::resource('faqs', Admin\FaqController::class);

        // Sale Points
        Route::post('/sale-points/reorder', [Admin\SalePointController::class, 'reorder'])->name('sale-points.reorder');
        Route::resource('sale-points', Admin\SalePointController::class);

        // Banners
        Route::post('/banners/reorder', [Admin\BannerController::class, 'reorder'])->name('banners.reorder');
        Route::resource('banners', Admin\BannerController::class);

        // Contacts
        Route::get('/contacts',                      [Admin\ContactController::class, 'index'])->name('contacts.index');
        Route::get('/contacts/{contact}',            [Admin\ContactController::class, 'show'])->name('contacts.show');
        Route::patch('/contacts/{contact}/status',   [Admin\ContactController::class, 'updateStatus'])->name('contacts.status');
        Route::delete('/contacts/{contact}',         [Admin\ContactController::class, 'destroy'])->name('contacts.destroy');

        // Settings
        Route::get('/settings/general',             [Admin\SettingsController::class, 'general'])->name('settings.general');
        Route::post('/settings/general',            [Admin\SettingsController::class, 'saveGeneral'])->name('settings.general.save');
        Route::get('/settings/contact',             [Admin\SettingsController::class, 'contact'])->name('settings.contact');
        Route::post('/settings/contact',            [Admin\SettingsController::class, 'saveContact'])->name('settings.contact.save');
        Route::get('/settings/social',              [Admin\SettingsController::class, 'social'])->name('settings.social');
        Route::post('/settings/social',             [Admin\SettingsController::class, 'saveSocial'])->name('settings.social.save');
        Route::get('/settings/integrations',        [Admin\SettingsController::class, 'integrations'])->name('settings.integrations');
        Route::post('/settings/integrations',       [Admin\SettingsController::class, 'saveIntegrations'])->name('settings.integrations.save');
        Route::get('/settings/home',                [Admin\SettingsController::class, 'home'])->name('settings.home');
        Route::post('/settings/home',               [Admin\SettingsController::class, 'saveHome'])->name('settings.home.save');
        Route::post('/settings/maintenance',        [Admin\SettingsController::class, 'toggleMaintenance'])->name('settings.maintenance');

        // Users (admin only)
        Route::resource('users', Admin\UserController::class)->middleware('can:admin-only');
    });
});
