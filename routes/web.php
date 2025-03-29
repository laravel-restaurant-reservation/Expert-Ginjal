<?php

use Illuminate\Support\Facades\Route;

Route::get( '/', function () {
return view('landing');
});

Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::get( '/about', function () {
return view('about');
});

Route::get( '/blog', function () {
return view('blog');
});

Route::get( '/contact', function () {
return view('contact');
});
