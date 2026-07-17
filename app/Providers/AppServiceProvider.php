<?php

namespace App\Providers;

use App\Models\Announcement;
use App\Models\Category;
use App\Models\Page;
use App\Models\SiteSetting;
use App\Services\MediaService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(MediaService::class);
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            try {
                $siteSettings   = Cache::remember('nav.site_settings', 3600, fn () => SiteSetting::all()->pluck('value', 'key'));
                $announcements  = Cache::remember('nav.announcements', 3600, fn () => Announcement::active()->orderBy('order')->get());
                $footerPages    = Cache::remember('nav.footer_pages', 3600, fn () => Page::published()->where('show_in_footer', true)->orderBy('order')->get());
                $navCategories  = Cache::remember('nav.categories', 3600, fn () => Category::where('is_active', true)->where('type', 'product')->orderBy('order')->get());
            } catch (\Exception) {
                $siteSettings   = collect();
                $announcements  = collect();
                $footerPages    = collect();
                $navCategories  = collect();
            }
            $view->with(compact('siteSettings', 'announcements', 'footerPages', 'navCategories'));
        });

        Gate::define('admin-only', fn($user) => $user->hasRole('admin'));
    }
}
