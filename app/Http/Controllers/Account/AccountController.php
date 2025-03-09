<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class AccountController extends Controller
{
    public function profile()
    {
        $ipc_path = public_path('json/international_phone_codes.json');
        $ipc_file = File::get($ipc_path);
        $ipc = json_decode($ipc_file, true);

        $profile = (object) [
            'full_name' => '',
            'address_country' => '',
            'address_city' => '',
            'address_street' => '',
            'zip_code' => '',
            'phone_code' => '',
            'phone_number' => '',
        ];
        if(isset(auth()->user()->profile)) $profile = auth()->user()->profile;

        return view('pages.account.account_profile', [
            'metaTags' => $this->metaTags,
            'title' => 'Account | Profile',
            'user' => auth()->user(),
            'profile' => $profile,
            'ipc' => $ipc,
        ]);
    }
}
