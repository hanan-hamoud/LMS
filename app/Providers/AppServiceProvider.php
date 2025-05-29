<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;
use Filament\Support\Assets\Theme;
use Filament\Navigation\NavigationItem;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Route;
use Filament\Navigation\NavigationGroup;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
    public function boot(): void
{
    Filament::serving(function () {
        app()->setLocale(session('locale', config('app.locale')));

        Filament::registerNavigationGroups([
            NavigationGroup::make()
                ->label(__('الإعدادات'))
        ]);
        Filament::registerNavigationItems([
            NavigationItem::make(__('العربية'))
                ->group(__('الإعدادات'))
                ->icon('heroicon-o-language')
                ->url(route('locale.switch', ['locale' => 'ar']))
                ->sort(1001),
        
            NavigationItem::make(__('English'))
                ->group(__('الإعدادات'))
                ->icon('heroicon-o-language')
                ->url(route('locale.switch', ['locale' => 'en']))
                ->sort(1002),
        ]);
        
        
    });
}


     
    
}
