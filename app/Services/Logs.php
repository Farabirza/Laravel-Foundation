<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class Logs
{
    protected $fileName;
    
    public function __construct($fileName)
    {
        $this->fileName = $fileName;
    }

    public function write(string $message): void
    {
        $fileName = date('Ymd').'_'.$this->fileName;
        $filePath = storage_path("logs/$fileName.log");

        // Ensure logs.txt file exists, or create it
        if (!File::exists($filePath)) {
            File::put($filePath, '');
        }

        // Append the new log message to the file with a newline
        File::append($filePath, date('Y-m-d H:i')."\t".$message . PHP_EOL);
    }
}
