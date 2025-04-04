<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\AjaxController;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Profile;
use App\Services\Logs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AjaxAdminController extends AjaxController
{
    protected $logs;

    public function __construct()
    {
        $this->logs = new Logs('AjaxAdminController');
    }

    public function action(Request $request)
    {
        DB::beginTransaction();
        DB::enableQueryLog();
        if(!$request->has('action')) return response()->json('Request not valid', 400);
        switch($request->action) {
            case 'user_detail':
                $user = User::with('web_role')->find($request->user_id);
                return response()->json([
                    $user
                ], 200);
            break;
            default:
                return response()->json([
                    'Action not valid'
                ], 400);
            break;
        }
    }
}
