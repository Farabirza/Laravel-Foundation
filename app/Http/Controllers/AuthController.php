<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Logs;
use App\Models\WebRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    protected $logs, $activity_logs;

    public function __construct()
    {
        $this->logs = new Logs("AuthController");
        $this->activity_logs = new Logs('Activity');
    }

    public function login(Request $request)
    {
        $query = User::where('email', $request->email);
        if($user = $query->first()) {
            if(Auth::attempt([
                'email' => $user->email, 'password' => $request->password
            ], $request->remember)) {
                $request->session()->regenerate();
                $query->update([
                    'last_login' => date('Y-m-d H:i:s')
                ]);
                return redirect()->intended('/');
            }
        }
        return back()->with('error', 'Username or password is not valid');
    }

    public function logout(Request $request)
    {
        $user = auth()->user();
        $this->activity_logs->write("'{$user->email}' log out");
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function google_login() {
        return Socialite::driver('google')->redirect();
    }

    public function google_callback() {
        try {
            $google = Socialite::driver('google')->user();
            $user = User::where('google_id', $google->id)->first();
            if($user) {

                $access = $this->account_status($user, User::where('google_id', $google->id));
                if($access[0] == false) return redirect('/')->with('error', $access[1]);

                Auth::login($user);

                $this->activity_logs->write("'{$user->email}' log in with Google");
                return redirect()->intended('/');
            } else {
                // If user already registered, retrieve user data. Otherwise, register
                $user_by_email = User::where('email', $google->email)->first();
                if(!$user_by_email) {
                    $username = explode('@', $google->email)[0];
                    $web_role_id = WebRole::where('name', 'basic_user')->first()->id;
                    $user_by_email = User::create([
                        'full_name'         => $username,
                        'email'             => $google->email,
                        'google_id'         => $google->id,
                        'password'          => bcrypt($google->email),
                        'email_verified_at' => date('Y-m-d H:i:s', time()),
                        'web_role_id'       => $web_role_id,
                        'status'            => 'active',
                    ]);
                }

                $access = $this->account_status($user_by_email, User::where('email', $google->email));
                if($access[0] == false) return redirect('/')->with('error', $access[1]);

                Auth::login($user_by_email);
                return redirect()->intended('/');
            }
        } catch (\Throwable $th) {
            $this->logs->write($th->getMessage());
            return redirect('/?info=Authentication failed');
        }
    }

    public function google_register(Request $request) {
        $username = explode('@', $request->google_email)[0];
        $create = User::create([
            'full_name'         => $username,
            'email'             => $request->google_email,
            'google_id'         => $request->google_id,
            'password'          => bcrypt($request->google_email),
            'email_verified_at' => date('Y-m-d H:i:s', time()),
            'status'            => 'active',
        ]);
        $this->activity_logs->write("'{$request->google_email}' register with Google");
        Auth::login($create);
        return redirect()->intended('/');
    }

    public function account_status($user, $user_query)
    {
        $access = true;
        $message = 'Your account is disabled. Contact admin for further inquiry';

        try {
            switch($user->status) {
                case 'active':
                    $access = true;
                    $message = '';
                break;
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
        } catch(\Exception $e) {
            $access = false;
            $message = $e->getMessage();
            $this->logs->write($e->getMessage());
        }
        return [$access, $message];
    }
}
