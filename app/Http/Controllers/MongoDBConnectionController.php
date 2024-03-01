<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jenssegers\Mongodb\Facades\MongoDB;

class MongoDBConnectionController extends Controller
{
    public function checkConnection()
    {
        try {
            // Попробуйте выполнить запрос к базе данных MongoDB
            $result = MongoDB::connection()->collection('your_collection')->get();
            
            // Если запрос прошел успешно, выводим сообщение об успешном подключении
            return response()->json(['message' => 'Подключение к MongoDB успешно!']);
        } catch (\Exception $e) {
            // Если возникла ошибка, выводим сообщение об ошибке подключения
            return response()->json(['error' => 'Ошибка подключения к MongoDB: ' . $e->getMessage()], 500);
        }
    }
}
