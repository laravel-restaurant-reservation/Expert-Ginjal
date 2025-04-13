<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DiagnoseController;

Route::get( '/', function () {
return view('landing');
});

Route::get('/form', function () {
    return view('form', ['title' => 'Form']);
})->name('form');

Route::get('diagnose', function () {
    return view('diagnose', ['title' => 'Diagnosa']);
})->name('diagnose');

Route::post('diagnose/proses', [DiagnoseController::class, 'proses'])->name('diagnose.proses');

Route::get('/hasil/export', [DiagnoseController::class, 'exportPDF'])->name('hasil.export');
