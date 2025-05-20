<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;
use Filament\Support\Assets\Theme;
use Filament\Navigation\NavigationItem;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    
    public function boot()
    {
        Filament::serving(function () {
            Filament::registerNavigationItems([
                NavigationItem::make(__('تغيير اللغة'))
                    ->url(route('change-locale'))
                    ->icon('heroicon-o-language')
                    ->sort(1000),
            ]);
        });
    }
    
    /**
     * Bootstrap any application services.
     */
    
    

    //  public function boot(): void
    //  {
    //      Filament::serving(function () {
    //          if (app()->getLocale() === 'ar') {
    //              Filament::registerTheme('css/filament-rtl.css');
    //          }
    //      });
    //  }
     
    
}
