<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/change-locale', function () {
    $newLocale = session('locale') === 'ar' ? 'en' : 'ar';
    session(['locale' => $newLocale]);
    app()->setLocale($newLocale);
    return back();
})->name('change-locale');

