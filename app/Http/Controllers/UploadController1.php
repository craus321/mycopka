<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Goodby\CSV\Import\Standard\LexerConfig;
use Goodby\CSV\Import\Standard\Lexer;
use Goodby\CSV\Import\Standard\Interpreter;
use Goodby\CSV\Import\Standard\LexerOption;
use SplFileObject;

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
                'file' => 'required|mimes:xlsx,csv,txt|max:2048',
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

          
            return view('upload_form', ['records' => $records]);
        } else {

            return back()->withErrors(['file' => 'File was not uploaded.']);
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
        $interpreter->addObserver(function (array $row) use (&$records) {
            $records[] = $row;
        });

        $lexer->parse($filePath, $interpreter);

        return $records;
    }

    /**
     * Parse an Excel file.
     *
     * @param string 
     * @return array
     */
    private function parseExcel(string $filePath): array
    {
        $records = [];
        
       
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        
       
        foreach ($sheet->getRowIterator() as $row) {
            $rowData = [];
            foreach ($row->getCellIterator() as $cell) {
                $rowData[] = $cell->getValue();
            }
            $records[] = $rowData;
        }

        return $records;
    }
}
