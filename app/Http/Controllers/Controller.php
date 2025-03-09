<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

abstract class Controller
{
    public $metaTags;

    public function __construct() {
        $this->metaTags = [
            'title' => 'ruangnamu.com',
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


    public function queryLog($logs)
    {
        $queryLog = DB::getQueryLog();
        $query = end($queryLog);
        $sql = $query['query'];
        $bindings = $query['bindings'];
        $finalQuery = vsprintf(str_replace('?', "'%s'", $sql), $bindings);
        $logs->write($finalQuery."\r\n");
    }
}
