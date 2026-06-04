<?php

namespace App\Providers;

use App\Models\Announcement;
use App\Models\SiteSetting;
use App\Services\MediaService;
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
                $siteSettings  = SiteSetting::all()->pluck('value', 'key');
                $announcements = Announcement::active()->orderBy('order')->get();
                $footerPages   = \App\Models\Page::published()->where('show_in_footer', true)->orderBy('order')->get();
            } catch (\Exception) {
                $siteSettings  = collect();
                $announcements = collect();
                $footerPages   = collect();
            }
            $view->with(compact('siteSettings', 'announcements', 'footerPages'));
        });

        Gate::define('admin-only', fn($user) => $user->hasRole('admin'));
    }
}
