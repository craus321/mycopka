<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{
    public function exportToHtml()
    {
        // Для примера создадим массив с данными
        $data = [
            ['Name', 'Email'],
            ['John Doe', 'john@example.com'],
            ['Jane Doe', 'jane@example.com'],
        ];

        // Экспортируем данные в HTML таблицу
        return view('export', compact('data'));

    }
}
