<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function users_controller()
    {
        return view('pages.admin.admin_users', [
            'metaTags' => $this->metaTags,
            'title' => 'Admin Panel | Users',
        ]);
    }
}
