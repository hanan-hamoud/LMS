<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = session('locale', config('app.locale'));

        // حماية: السماح فقط بـ 'ar' و 'en'
        if (!in_array($locale, ['ar', 'en'])) {
            $locale = 'en'; // أو 'ar' حسب ما تريد
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
