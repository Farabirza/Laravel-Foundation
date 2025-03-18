<?php

namespace App\Http\Controllers\Ajax;

use App\Models\RSVP;

use App\Models\User;
use App\Mail\OtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\AjaxController;
use Illuminate\Support\Facades\Validator;

class AjaxAuthController extends AjaxController
{
    public function action(Request $request)
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

            case 'send-otp':
                $validator = Validator::make($request->all(), [
                    'email' => ['required', 'email'],
                ], [
                    'email.required' => 'Please insert your email',
                    'email.email' => 'Invalid email format'
                ]);
                if ($validator->fails()) return response()->json(['errors' => $validator->errors()], 422);

                $otp = rand(100000, 999999);
                $otp_exp = now()->addMinutes(5);

                Session::put('otp', $otp);
                Session::put('otp_email', $request->email);
                Session::put('otp_expires_at', $otp_exp);

                User::where('email', $request->email)->update([
                    'otp_code' => $otp,
                    'otp_exp' => $otp_exp,
                ]);

                Mail::to($request->email)->send(new OtpMail($otp));

                return response()->json([
                    'message' => 'OTP code has been sent to your email',
                    'otp_exp' => $otp_exp
                ], 200);
            break;

            case 'submit-otp':
                $otp_code = '';
                foreach($request->otp_code as $code) $otp_code .= $code;
                // print_r($otp_code);exit();

                $user_query = User::where('email', $request->email);
                $user = $user_query->first();
                $current_date = date('Y-m-d');
                $current_time = date('Y-m-d H:i:s');

                if($user == '') return response()->json(['message' => 'Invalid credential'], 400);
                if($user->otp_exp == '') return response()->json(['message' => 'Please generate OTP code to reset your password'], 400);
                if($current_time >= $user->otp_exp) return response()->json(['message' => 'OTP code has expired, please generate a new one'], 400);

                if($user->otp_code != $otp_code) {
                    $failed_attempt = $user->failed_login_attempt == '' ? 0 : $user->failed_login_attempt;
                    $otp_exp_date = $user->otp_exp == '' ? date('Y-m-d') : date('Y-m-d', strtotime($user->otp_exp));

                    if($otp_exp_date != $current_date) {
                        $failed_attempt = 1;
                    } else {
                        $failed_attempt++;
                    }
                    $remaining_chance = $failed_attempt >= 5 ? 0 : 5 - $failed_attempt;

                    if($failed_attempt >= 5) {
                        $user_query->update([
                            'failed_login_attempt' => $failed_attempt,
                            'failed_login' => $current_time,
                            'status' => 'locked',
                        ]);
                        return response()->json([
                            'message' => 'You exceed available chance to submit OTP code, your account will be temporarily disabled. Contact admin for further inquiry.'
                        ], 400);
                    } else {
                        $user_query->update([
                            'failed_login_attempt' => $failed_attempt,
                        ]);
                        return response()->json([
                            'message' => "OTP code doesn't match, remaining chances : $remaining_chance"
                        ], 400);
                    }
                }
                $user_query->update([
                    'otp_code' => null,
                    'otp_exp' => null,
                    'last_login' => $current_time,
                    'failed_login_attempt' => 0,
                ]);
                return response()->json([
                    'message' => "Password successfully reset",
                ], 200);
            break;
        }
        return response()->json("Function not found", 404);
    }
}
