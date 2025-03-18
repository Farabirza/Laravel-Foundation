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
    protected $logs;

    public function __construct()
    {
        $this->logs = new Logs('ApiAuthController');
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
                'alert' => 'Registration failed!',
            ], 400);
        }
        $this->logs->write("New user has registered! \r\n Username \t : {$username} \r\n Email \t\t : {$request->email}");
        return response()->json([
            'message' => 'You have registered successfully!',
            'token' => $token,
        ], 200);
    }

    public function login(LoginRequest $request)
    {
        $this->validateError();
        $user = User::where('username', $request->email)->orWhere('email', $request->email)->first();
        if(Auth::attempt([
            'email' => $user->email, 'password' => $request->password
        ], $request->remember)) {
            $token = $user->createToken('token')->plainTextToken;
            return response()->json([
                'message' => 'Welcome back '.$user->username,
                'token' => $token,
            ], 200);
        } else {
            return response()->json([
                'alert' => "Username atau password yang anda masukan tidak sesuai"
            ], 400);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'message' => 'Loged out'
        ], 200);
    }
}
