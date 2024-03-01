<?php



use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExcelController;





Route::get('/', 'App\Http\Controllers\UploadController@showUploadForm')->name('upload.form');
Route::post('/', 'App\Http\Controllers\UploadController@upload')->name('upload.submit');
Route::post('/upload/save', 'UploadController@saveToDatabase')->name('upload.saveToDatabase');


use App\Http\Controllers\MongoDBConnectionController;

Route::get('/check-mongodb-connection', [MongoDBConnectionController::class, 'checkConnection']);



Route::get('/1', function () {
    return view('info');
});