<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Goodby\CSV\Import\Standard\LexerConfig;
use Goodby\CSV\Import\Standard\Lexer;
use Goodby\CSV\Import\Standard\Interpreter;
use Goodby\CSV\Import\Standard\LexerOption;
use SplFileObject;

class ProcessUploadedFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;

    /**
     * Create a new job instance.
     *
     * @param string $filePath
     * @return void
     */
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $extension = pathinfo($this->filePath, PATHINFO_EXTENSION);

        if ($extension === 'csv' || $extension === 'txt') {
            $delimiter = $this->detectDelimiter($this->filePath);
            $records = $this->parseCsv($this->filePath, $delimiter);
        } elseif ($extension === 'xlsx') {
            $records = $this->parseExcel($this->filePath);
        } else {
            throw new \Exception('Неподдерживаемый формат файла.');
        }

        // Обработка записей файла...
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

        $lexerOption = new LexerOption();
        $lexerOption->setToCharset('UTF-8');
        $lexerOption->setFromCharset('CP1251');

        $interpreter->unregisterObserver('fetch_parsed_row');
        $interpreter->registerObserver('fetch_parsed_row', function (array $row) use (&$records) {
            $records[] = $row;
        });

        $lexer->parse(new SplFileObject($filePath), $interpreter, $lexerOption);

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
