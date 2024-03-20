<?php


use App\Http\Controllers\UploadController;
use App\Models\CustomerMongoDB;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExcelController;





Route::get('/', [UploadController::class, 'index'])->name('upload.form');
Route::post('/', [UploadController::class, 'upload'])->name('upload.submit');
Route::post('/upload/save', [UploadController::class, 'saveToDatabase'])->name('upload.saveToDatabase');


use App\Http\Controllers\MongoDBConnectionController;
use Illuminate\Support\Facades\Schema;
use MongoDB\Laravel\Schema\Blueprint;

Route::get('/check-mongodb-connection', [MongoDBConnectionController::class, 'checkConnection']);
Route::get('/ping', function (Request  $request) {
    $connection = DB::connection('mongodb');
$msg = 'MongoDB is accessible!';
try {
        $connection->command(['ping' => 1]);
    } catch (\Exception  $e) {
        $msg = 'MongoDB is not accessible. Error: ' . $e->getMessage();
}
return ['msg' => $msg];
});

Route::get('/phpinfo', function (Request  $request) {
    echo phpinfo();
});



Route::view('/ok', 'ok')->name('ok');




Route::get('/photo', function () {
    return view('photo');
});
