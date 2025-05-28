<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/change-locale', function () {
    $locale = session('locale', config('app.locale')) === 'ar' ? 'en' : 'ar';
    session()->put('locale', $locale);
    return redirect()->back();
})->name('change-locale');




Route::get('/login', fn () => 'Login Page')->name('login');