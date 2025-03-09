<?php

namespace App\Http\Controllers\Ajax;

use App\Models\RSVP;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\AjaxController;

class AjaxAuthController extends AjaxController
{
    public function ajax(Request $request)
    {
        switch ($request->action) {
            case 'check_username':
                $exist = (User::where('username', $request->username)->first()) ? true : false;
                $error = false; $messages = [];
                if(strlen($request->username) < 3) {
                    $messages[] = "minimal terdiri atas 3 karakter";
                    $error = true;
                }
                if(preg_match("/^[A-Za-z0-9]+$/", $request->username) == 0) {
                    $messages[] = "hanya gunakan kombinasi huruf dan angka";
                    $error = true;
                }
                if($exist) {
                    $messages[] = "username telah digunakan";
                    $error = true;
                }
                if($error) {
                    return response()->json([
                        'available' => false,
                        'message' => $messages,
                    ], 200);
                }
                $messages[] = "username dapat digunakan";
                return response()->json([
                    'available' => true,
                    'message' => $messages,
                ], 200);
            break;
        }
        return response()->json("Function not found", 404);
    }
}
