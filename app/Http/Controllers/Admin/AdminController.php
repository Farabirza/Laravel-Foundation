<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function users_controller()
    {
        $users = User::orderByDesc('status')->orderBy('email')->get();

        return view('pages.admin.admin_users', [
            'users' => $users,
            'metaTags' => $this->metaTags,
            'title' => 'Admin Panel | Users',
        ]);
    }
}
