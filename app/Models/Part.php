<?php

namespace App\Models;


use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Part extends Eloquent
{
    protected $connection = 'mongodb'; // Указываем соединение с MongoDB
    protected $collection = 'raw_data'; // Указываем коллекцию в MongoDB

    protected $fillable = [
        'brand1',
        'part_number1',
        'brand2',
        'part_number2',
        'name', // Название может отсутствовать
        'timestamp', // Время добавления
        'counter', // Счетчик для проверки повторов
    ];

    // Если необходимо использовать инкремент для поля счетчика
    // protected $casts = [
    //     'counter' => 'integer',
    // ];

    // Если вы хотите использовать кастомный идентификатор
    // protected $primaryKey = '_id';
}
