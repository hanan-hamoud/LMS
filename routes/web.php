<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
Route::get('/', function () {
    return view('welcome');
});








Route::get('/switch-locale', function (\Illuminate\Http\Request $request) {
    $locale = $request->input('locale', config('app.locale'));
    session()->put('locale', $locale);
    return redirect()->back();
})->name('locale.switch');





Route::get('/login', fn () => 'Login Page')->name('login');