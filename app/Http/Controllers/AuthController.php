<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Logs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    protected $logs;

    public function __construct()
    {
        $this->logs = new Logs(class_basename("AuthController"));
    }

    public function login(Request $request)
    {
        $query = User::where('username', $request->email)->orWhere('email', $request->email);
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
                return redirect()->intended('/');
            } else {
                if(!$user_by_email = User::where('email', $google->email)->first()) {
                    return view('auth.create_username', [
                        'metaTags' => $this->metaTags,
                        'title' => 'Laravel Foundation',
                        'user' => $google,
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
        // $get_name = str_replace(' ', '', $google->name);
        $username = $request->username;
        $create = User::create([
            'username' => $username,
            'email' => $request->google_email,
            'google_id' => $request->google_id,
            'password' => bcrypt($request->google_email),
            'email_verified_at' => date('Y-m-d H:i:s', time()),
        ]);
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
