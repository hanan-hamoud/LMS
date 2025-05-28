<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    // public function handle(Request $request, Closure $next)
    // {
    //     App::setLocale(Session::get('locale', config('app.locale')));
    //     return $next($request);
    // }

    public function handle(Request $request, Closure $next)
{
    \Log::info('Current locale before:', ['locale' => Session::get('locale'), 'app' => config('app.locale')]);
    
    App::setLocale(Session::get('locale', config('app.locale')));
    
    \Log::info('Current locale after:', ['locale' => App::getLocale()]);
    
    return $next($request);
}
}

