<?php

namespace App\Http\Controllers;

use App\Models\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public $metaTags;

    public function index()
    {
        if(!$user = Auth::user()) {
            return view('index', [
                'metaTags' => $this->metaTags,
                'title' => config('app.name'),
            ]);
        }
        return view('home', [
            'metaTags' => $this->metaTags,
            'title' => config('app.name'),
        ]);
    }
}
