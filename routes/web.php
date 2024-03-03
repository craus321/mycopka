<?php


use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExcelController;





Route::get('/', [UploadController::class, 'index'])->name('upload.form');
Route::post('/', [UploadController::class, 'upload'])->name('upload.submit');
Route::post('/upload/save', [UploadController::class, 'saveToDatabase'])->name('upload.saveToDatabase');


use App\Http\Controllers\MongoDBConnectionController;

Route::get('/check-mongodb-connection', [MongoDBConnectionController::class, 'checkConnection']);
