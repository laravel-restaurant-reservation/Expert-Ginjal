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

Route::get('/test-upload', function () {
    $pdfContent = file_get_contents(public_path('dummy.pdf'));
    $path = 'reports/test_' . now()->timestamp . '.pdf';

    Storage::disk('s3')->put($path, $pdfContent);

    return Storage::disk('s3')->url($path);
});