<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Services\Logs;
use App\Models\WebRole;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;

class ApiAuthController extends Controller
{
    protected $logs, $activity_logs;

    public function __construct()
    {
        $this->logs = new Logs('ApiAuthController');
        $this->activity_logs = new Logs('Activity');
    }

    public function users()
    {
        return response()->json([
            'user' => User::get(),
        ], 200);
    }

    public function register(RegisterRequest $request)
    {
        $this->validateError();

        $web_user_role = WebRole::where('name', 'basic_user')->first()->id;
        $latestSeq = (User::max('seq') ?? 0) + 1;
        $username = "User{$latestSeq}";

        $user = User::create([
            'username' => $username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'web_role_id' => $web_user_role,
        ]);
        $token = $user->createToken('token')->plainTextToken;
        if(!$user) {
            return response()->json([
                'message' => 'Registration failed!',
            ], 400);
        }

        $message = "New user has registered! \r\n Username \t : {$username} \r\n Email \t\t : {$request->email}";
        $this->logs->write($message);
        $this->activity_logs->write($message);
        return response()->json([
            'message' => 'You have registered successfully!',
            'token' => $token,
        ], 200);
    }

    public function login(LoginRequest $request)
    {
        $this->validateError();
        $user_query = User::where('username', $request->email)->orWhere('email', $request->email);
        $user = $user_query->first();

        if(!$user) return response()->json(['message' => 'Invalid credentials'], 400);
        if($user->status != 'active') {
            $access = true;
            switch($user->status) {
                case 'locked':
                    $failedLogin = strtotime($user->failed_login);
                    $currentTime = time();
                    if(($currentTime - $failedLogin) < 86400) {
                        $access = false; // reject before 24 hours this account got locked
                    } else {
                        $user_query->update([
                            'status'        => 'active',
                            'failed_login'  => null,
                        ]);
                    }
                break;
                default:
                    $access = false;
                break;
            }
            if($access == false) return response()->json(['message' => 'Your account is disabled. Contact admin for further inquiry'], 400);
        }

        if(Auth::attempt([
            'email' => $user->email, 'password' => $request->password
        ], $request->remember)) {
            $token = $user->createToken('token')->plainTextToken;
            $user_query->update([ 'last_login' => now() ]);
            $this->activity_logs->write("'{$user->email}' log in");
            return response()->json([
                'message' => 'Welcome back '.$user->username,
                'token' => $token,
            ], 200);
        } else {
            $this->activity_logs->write("User attempted to log in with email '{$user->email}' failed");
            return response()->json([
                'message' => "Invalid credentials"
            ], 400);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        $user = auth()->user();
        $this->activity_logs->write("'{$user->email}' log out");
        return response()->json([
            'message' => 'Loged out'
        ], 200);
    }
}
