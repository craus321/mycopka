<?php

namespace App\Http\Controllers;

use App\Models\CustomerMongoDB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Goodby\CSV\Import\Standard\LexerConfig;
use Goodby\CSV\Import\Standard\Lexer;
use Goodby\CSV\Import\Standard\Interpreter;
use SplFileObject;

class UploadController extends Controller
{
    /**
     * Display the upload form.
     *
     * @return \Illuminate\View\View
     */
    public function index(): \Illuminate\View\View
    {
        return view('upload_form');
    }


    /**
     * Process the uploaded file (CSV or XLSX).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Illuminate\Http\Response
     */
    public function upload(Request $request)
    {
        set_time_limit(0);

        if ($request->hasFile('file')) {
            $request->validate([
                'file' => 'required|mimes:csv,txt',
            ]);

            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();

            if ($extension === 'xlsx') {

                $csvFilePath = tempnam(sys_get_temp_dir(), 'csv');
                $writer = IOFactory::createWriter(IOFactory::load($file), 'Csv');
                $writer->setDelimiter(',');
                $writer->setEnclosure('"');
                $writer->save($csvFilePath);
            } elseif ($extension === 'csv' || $extension === 'txt') {
                $csvFilePath = $file->getRealPath();
            } else {
                return response('Неподдерживаемый формат файла.', 400);

            }

            $delimiter = $this->detectDelimiter($csvFilePath);
            $records = $this->parseCsv($csvFilePath, $delimiter);
            $headers = $records[0];

            // Проверяем наличие обязательных столбцов
            $mainAttributes = $this->getMainAttributesForCsv();
            $diffHeaders = array_diff($mainAttributes, $headers);

            // Если отсутствуют обязательные столбцы, пропускаем валидацию
            if (!empty($diffHeaders)) {
                $canUpload = true;
                foreach (['brand_cross', 'article_cross', 'name_cross'] as $requiredColumn) {
                    if (!in_array($requiredColumn, $headers)) {
                        $canUpload = false;
                        break;
                    }
                }

                if (!$canUpload) {
                    return response('Отсутствуют обязательные столбцы (' . implode(',', array_map(function($item) {
                            return '<strong style="color:red">' . $item . '</strong>';
                        }, $diffHeaders)) .  ')', 400);
                }
            }

            // Ограничение вывода до 50 строк
            $limitedRecords = array_slice($records, 0, 50);
            return view('data_show', ['records' => $limitedRecords]);
        } else {
            return response('Файл не был загружен.', 400);

        }
    }

    private function getMainAttributesForCsv(): array
    {
        return [
            'brand',
            'article',
            'brand_cross',
            'article_cross',
            'name_cross',
            'name'
        ];
    }

    public function saveToDatabase(Request $request)
{
    set_time_limit(0);

    // Получаем данные из запроса
    $fields = $request->input('fieldName');
    // Загружаем CSV файл
    $file = $request->file('file');
    // Проверяем, был ли загружен файл
    if ($file->isValid()) {
        // Читаем содержимое CSV файла
        $csvData = file_get_contents($file->path());
        // Разбиваем данные на строки
        $delimiter = $this->detectDelimiter($file->path());

        $rows = explode(PHP_EOL, $csvData);
        // Получаем названия столбцов из первой строки CSV файла
        $headers = str_getcsv(array_shift($rows), $delimiter);
        // Обрабатываем каждую строку CSV файла
        foreach ($rows as $row) {
            $newObj = [];
            $dataRow = str_getcsv($row, $delimiter);
            foreach ($dataRow as $key => $data) {
                $newObj[$headers[$key]] = $data;
            }
            $newObj['counter'] = 1;

            // Условие для поиска дубликатов
            $conditions = [
                'brand' => $newObj['brand'],
                'article' => $newObj['article'],
                'brand_cross' => $newObj['brand_cross'],
                'article_cross' => $newObj['article_cross'],
                'name_cross' => $newObj['name_cross'],
                'name' => $newObj['name']
            ];

            // Поиск дубликата
            $existingRecord = CustomerMongoDB::where($conditions)->first();

            // Если найден дубликат, увеличиваем счетчик
            if ($existingRecord) {
                $existingRecord->increment('counter');
            } else {
                // Создание новой записи
                CustomerMongoDB::create($newObj);
            }
        }


        // Возвращаем ответ об успешном сохранении
        return response()->json(['message' => 'Данные успешно сохранены в базу данных'], 200);
    } else {
        // Обработка ошибок при загрузке файла
        return response()->json(['error' => 'Ошибка при загрузке файла'], 400);
    }

}

    public static function quickRandom($length = 16)
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
    }

    /**
     * Detect delimiter of CSV file.
     *
     * @param string $filePath
     * @return string
     */
    private function detectDelimiter(string $filePath): string
    {
        $file = new SplFileObject($filePath);
        $firstLine = $file->fgets();
        $delimiters = [',', ';', '|', "\t"];

        foreach ($delimiters as $delimiter) {

            $encoding = mb_detect_encoding($firstLine, 'UTF-8, Windows-1251');
            $firstLineUtf8 = mb_convert_encoding($firstLine, 'UTF-8', $encoding);

            foreach ($delimiters as $delimiter) {
                if (strpos($firstLineUtf8, $delimiter) !== false) {
                    return $delimiter;
                }
            }
        }

        return ',';
    }

    /**
     * Parse CSV file with a specific delimiter.
     *
     * @param string $filePath
     * @param string $delimiter
     * @return array
     */
    private function parseCsv(string $filePath, string $delimiter): array
    {
        $config = new LexerConfig();
        $config->setDelimiter($delimiter);

        $lexer = new Lexer($config);
        $interpreter = new Interpreter();

        $records = [];
        $count = 0;

        $interpreter->addObserver(function (array $row) use (&$records, &$count) {

            $convertedRow = array_map(function ($value) {
                return mb_convert_encoding($value, 'UTF-8', mb_detect_encoding($value));
            }, $row);

            $records[] = $convertedRow;
            $count++;

            // Остановить чтение файла после 50 строк
            if ($count >= 50) {
                return false;
            }
        });

        $lexer->parse($filePath, $interpreter);

        return $records;
    }
}
