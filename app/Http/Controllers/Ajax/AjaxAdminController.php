<?php

namespace App\Http\Controllers\Ajax;

use App\Models\User;
use App\Services\Logs;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Database\Query\Builder;
use App\Http\Controllers\AjaxController;

class AjaxAdminController extends AjaxController
{
    protected $logs;
    protected $months = ["January","February","March","April","May","June","July","August","September","October","November","December"];

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

            case 'chart-users-regis':
                $exp_periode = explode('-', $request->periode);
                $type = count($exp_periode);
                
                // Get users data
                $users = DB::table('users')
                    ->when($exp_periode, function(Builder $query) use ($exp_periode, $type) {
                        $query->whereYear('created_at', $exp_periode[0]);
                        if($type == 2) $query->whereMonth('created_at', $exp_periode[1]);
                    })
                    ->orderBy('created_at')
                    ->get();

                // Get maximum date
                $max_date = '';
                foreach($users as $user) {
                    $max_date = $max_date == '' ? Carbon::parse($user->created_at)->format('Y-m-d') : max($max_date, Carbon::parse($user->created_at)->format('Y-m-d'));
                }

                $labels = $data = [];
                if($users != '') {
                    switch((string) $type) {
                        case '1':
                            $max = $exp_periode[0] != date('Y') ? 12 : (int) Carbon::parse($max_date)->format('m');
                            for($i = 1; $i <= $max; $i++) {
                                $labels[] = $this->months[$i - 1];
                                $data[] = 0;
                            }
                            foreach($labels as $seq => $label) {
                                foreach($users as $user) {
                                    $month = (int) Carbon::parse($user->created_at)->format('m');
                                    if(($month - 1) == $seq) $data[$seq]++;
                                }
                            }
                        break;
                        case '2':
                            $max = (int) Carbon::parse($max_date)->format('d');
                            for($i = 1; $i <= $max; $i++) {
                                $labels[] = $i < 10 ? '0'.$i : (string) $i;
                                $data[] = 0;
                            }
                            foreach($labels as $seq => $label) {
                                foreach($users as $user) {
                                    $day = Carbon::parse($user->created_at)->format('d');
                                    if($day == $label) $data[$seq]++;
                                }
                            }
                        break;
                    }
                }

                return response()->json([
                    'labels' => $labels, 'data' => $data
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
