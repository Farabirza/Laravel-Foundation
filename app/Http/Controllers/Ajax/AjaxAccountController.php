<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Logs;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AjaxAccountController extends Controller
{
    protected $logs;

    public function __construct()
    {
        $this->logs = new Logs('AjaxAccountController');
    }

    public function action(Request $request)
    {
        DB::beginTransaction();
        DB::enableQueryLog();
        if(!$request->has('action')) return response()->json('Request not valid', 400);
        switch($request->action) {
            case 'update-account':
                $validator = Validator::make($request->all(), [
                    'username' => ['required', 'max:50', 'unique:users,username,'.auth()->user()->id],
                    'password' => [
                        $request->password == '' ? '' : 'min:6', $request->password == '' ? '' : 'confirmed'
                    ]
                ], [
                    'username.required' => 'Username cannot be empty',
                    'username.unique' => 'Username already used',
                    'password.min' => 'Password requires at least 6 characters',
                    'password.confirmed' => "Password confirmation doesn't match",
                ]);
                if ($validator->fails()) return response()->json(['errors' => $validator->errors()], 422);

                User::where('id', auth()->user()->id)->update([
                    'username' => $request->username,
                ]);

                if($request->password != '') {
                    User::where('id', auth()->user()->id)->update([
                        'password' => Hash::make($request->password),
                    ]);
                }

                DB::commit();
                return response()->json([
                    'message' => 'Account updated'
                ], 200);
            break;

            case 'save-profile':
                $validator = Validator::make($request->all(), [
                    'full_name' => ['required', 'max:50'],
                ], [
                    'full_name.required' => 'Full name cannot be empty'
                ]);
                if ($validator->fails()) return response()->json(['errors' => $validator->errors()], 422);

                $profile = Profile::updateOrCreate([
                    'user_id'           => auth()->user()->id
                ], [
                    'full_name'         => $request->full_name,
                    'gender'            => $request->gender,
                    'address_country'   => $request->address_country,
                    'address_city'      => $request->address_city,
                    'address_street'    => $request->address_street,
                    'zip_code'          => $request->zip_code,
                    'phone_code'        => $request->phone_number == '' ? '' : $request->phone_code,
                    'phone_number'      => $request->phone_number,
                ]);
                DB::commit();

                return response()->json([
                    'profile' => $profile,
                    'message' => 'Profile saved'
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
