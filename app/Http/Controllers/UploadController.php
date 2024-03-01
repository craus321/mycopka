<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Part; 
use PhpOffice\PhpSpreadsheet\IOFactory;
use Goodby\CSV\Import\Standard\LexerConfig;
use Goodby\CSV\Import\Standard\Lexer;
use Goodby\CSV\Import\Standard\Interpreter;
use SplFileObject;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;



class UploadController extends Controller
{
    /**
     * Display the upload form.
     *
     * @return \Illuminate\View\View
     */
    public function showUploadForm(): \Illuminate\View\View
    {
        return view('upload_form');
    }

    /**
     * Process the uploaded file (CSV or XLSX).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function upload(Request $request)
    {
        if ($request->hasFile('file')) {
            $request->validate([
                'file' => 'required|mimes:xlsx,csv,txt',
            ]);

            $file = $request->file('file');
            $filePath = $file->getRealPath();

            $extension = $file->getClientOriginalExtension();
            if ($extension === 'csv' || $extension === 'txt') {
                $delimiter = $this->detectDelimiter($filePath);
                $records = $this->parseCsv($filePath, $delimiter);
            } elseif ($extension === 'xlsx') {
                $records = $this->parseExcel($filePath);
            } else {
                return back()->withErrors(['file' => 'Неподдерживаемый формат файла.']);
            }

            // MongoDB
            foreach ($records as $record) {
                Part::create([
                    'brand1' => $record['brand1'],
                    'part_number1' => $record['part_number1'],
                    'brand2' => $record['brand2'],
                    'part_number2' => $record['part_number2'],
                    'name' => $record['name'] ?? null,
                    'timestamp' => now(), // Время 
                    'counter' => 0, // Счетчик для проверки повторов
                ]);
            }

            // Ограничение 50 строк
            $limitedRecords = array_slice($records, 0, 50);

            return view('upload_form', ['records' => $limitedRecords]);
        } else {
            return back()->withErrors(['file' => 'Файл не был загружен.']);
        }
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

        $delimiters = [',', ';', '|', "\t"]; // разделитель

        foreach ($delimiters as $delimiter) {
            if (strpos($firstLine, $delimiter) !== false) {
                return $delimiter;
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
        $count = 0; // счетчик строк сколько

        $interpreter->addObserver(function (array $row) use (&$records, &$count) {
            $records[] = $row;
            $count++;

            //  после 50 строк
            if ($count >= 50) {
                return false;
            }
        });

        $lexer->parse($filePath, $interpreter);

        return $records;
    }

    /**
     * Parse an Excel file.
     *
     * @param string $filePath
     * @return array
     */
    private function parseExcel(string $filePath): array
    {
        $records = [];
        $count = 0; // счетчик строк
        
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
    
        foreach ($sheet->getRowIterator() as $row) {
            $rowData = [];
            foreach ($row->getCellIterator() as $cell) {
                $rowData[] = $cell->getValue();
            }
            $records[] = $rowData;
            $count++;
    
            // Остановить чтение файла после 50 строк
            if ($count >= 50) {
                break;
            }
        }
    
        return $records;
    }
}