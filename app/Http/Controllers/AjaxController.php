<?php

namespace App\Http\Controllers;

use getID3;
use DB;

abstract class AjaxController
{
    public $metaTags;

    public function __construct() {
        $this->metaTags = [
            'title' => 'Laravel Foundation',
            'description' => 'Buat undanganmu sendiri!',
        ];
    }

    public function validateError()
    {
        if(isset($validator) && $validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }
    }

    public function auth()
    {
        if (!auth()->check()) {
            return response()->json("Unauthenticated", 401);
        }
        return true;
    }

    public function storeImage($base64Image, $directory, $fileName)
    {
        if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $ext)) {
            $data = substr($base64Image, strpos($base64Image, ',') + 1);
            $ext = strtolower($ext[1]);

            // Validation
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
                return [false, 'Invalid image type'];
            }

            $data = base64_decode($data);
            if ($data != false) {
                $fileName .= ".$ext";
                $filePath = public_path("$directory/$fileName");
                if (!file_exists(public_path($directory))) {
                    mkdir(public_path($directory), 0755, true);
                }
                file_put_contents($filePath, $data);

                return [true, 'Image uploaded successfully', $fileName, $filePath];
            }
        }
        return [false, 'Invalid image file'];
    }


    function getMp3Metadata($filePath)
    {
        $getID3 = new getID3();

        $fileInfo = $getID3->analyze($filePath);

        $title = $fileInfo['tags']['id3v2']['title'][0] ?? null;
        $artist = $fileInfo['tags']['id3v2']['artist'][0] ?? null;
        $publisher = $fileInfo['tags']['id3v2']['publisher'][0] ?? null;
        $year = $fileInfo['tags']['id3v2']['year'][0] ?? null;

        return [
            'title' => $title,
            'artist' => $artist,
            'publisher' => $publisher,
            'year' => $year,
        ];
    }

    public function queryLog($logs)
    {
        $queryLog = DB::getQueryLog(); 
        $query = end($queryLog);
        $sql = $query['query'];
        $bindings = $query['bindings'];
        $finalQuery = vsprintf(str_replace('?', "'%s'", $sql), $bindings);
        $logs->write("\r\n".$finalQuery."\r\n");
    }
}
