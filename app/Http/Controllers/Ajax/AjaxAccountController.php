<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\AjaxController;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Logs;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AjaxAccountController extends AjaxController
{
    protected $logs, $activity_logs;

    public function __construct()
    {
        $this->logs = new Logs('AjaxAccountController');
        $this->activity_logs = new Logs('Activity');
    }

    public function action(Request $request)
    {
        DB::beginTransaction();
        DB::enableQueryLog();
        if(!$request->has('action')) return response()->json('Request not valid', 400);
        switch($request->action) {
            case 'update-account':
                $user = auth()->user();

                // Password
                if($request->has('password') && $request->password != '') {
                    $validator = Validator::make($request->all(), [
                        'password' => [
                            $request->password == '' ? '' : 'min:6', $request->password == '' ? '' : 'confirmed'
                        ]
                    ], [
                        'password.min' => 'Password requires at least 6 characters',
                        'password.confirmed' => "Password confirmation doesn't match",
                    ]);
                    if ($validator->fails()) return response()->json(['errors' => $validator->errors()], 422);

                    User::where('id', auth()->user()->id)->update([
                        'password' => Hash::make($request->password),
                    ]);
                    $this->activity_logs->write("'{$user->email}' password updated");
                }

                // Process user picture
                if($request->has('picture_base64') && $request->picture_base64 != '') {
                    $base64Image = $request->picture_base64;

                    try {
                        $file_name = auth()->user()->id;
                        $storeImage = $this->storeImage($base64Image, 'images/users', $file_name);
                        if($storeImage[0] == true) {
                            User::find(auth()->user()->id)->update([
                                'picture' => $storeImage[2]
                            ]);
                            $this->activity_logs->write("'{$user->email}' profile picture updated");
                        }
                    } catch(\Exception $e) {
                        $this->logs->write($e->getMessage());
                    }
                }
                DB::commit();

            return response()->json([
                'message' => 'Account updated'
            ], 200);

            case 'save-profile':
                $user = auth()->user();
                $validator = Validator::make($request->all(), [
                    'full_name' => ['required', 'max:50'],
                ], [
                    'full_name.required' => 'Full name cannot be empty'
                ]);
                if ($validator->fails()) return response()->json(['errors' => $validator->errors()], 422);

                $profile = User::where('id', auth()->user()->id)
                ->update([
                    'full_name'         => $request->full_name,
                    'gender'            => $request->gender,
                    'address_country'   => $request->address_country,
                    'address_city'      => $request->address_city,
                    'address_street'    => $request->address_street,
                    'zip_code'          => $request->zip_code,
                    'phone_code'        => $request->phone_number == '' ? '' : $request->phone_code,
                    'phone_number'      => $request->phone_number,
                ]);
                $this->activity_logs->write("'{$user->email}' account data updated");
                DB::commit();

            return response()->json([
                'profile' => $profile,
                'message' => 'Profile saved'
            ], 200);

            default:
            return response()->json([
                'Action not valid'
            ], 400);
        }
    }
}
