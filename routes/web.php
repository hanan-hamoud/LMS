<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
Route::get('/', function () {
    return view('welcome');
});


Route::post('/language-switch', function () {
    $newLocale = app()->getLocale() === 'ar' ? 'en' : 'ar';
    session(['locale' => $newLocale]);
    app()->setLocale($newLocale);
    
    return redirect()->back()->with('success', 'Language changed successfully');
})->name('language.switch');


Route::post('/filament/language-toggle', function () {
    $current = session('locale', config('app.locale'));
    $newLocale = $current === 'ar' ? 'en' : 'ar';
    session(['locale' => $newLocale]);
    app()->setLocale($newLocale);

    return redirect()->back();
})->name('filament.language.toggle');

// Route::post('/language-switch', function () {
//     $locale = App::getLocale() === 'ar' ? 'en' : 'ar';
//     Session::put('locale', $locale);
//     App::setLocale($locale);
//     return Redirect::back();
// })->name('language.switch');

Route::get('/admin/lang/{locale}', function ($locale, Request $request) {
    $availableLocales = ['ar', 'en'];

    if (in_array($locale, $availableLocales)) {
        Session::put('locale', $locale);
    }

    // إعادة التوجيه للصفحة السابقة
    return redirect()->back();
})->name('admin.lang.switch');





Route::get('/login', fn () => 'Login Page')->name('login');