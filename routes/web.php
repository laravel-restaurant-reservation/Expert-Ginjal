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

Route::get('/cdn-test', function () {
    $path = 'demo_' . now()->timestamp . '.txt';
    Storage::disk('s3')->put($path, 'Hello from Laravel!');
    return Storage::disk('s3')->url($path);
});
