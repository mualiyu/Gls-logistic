<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index()
    {
        return view('main.index');
    }

    public function signin()
    {
        return view('main.login');
    }

    public function signup()
    {
        return view('main.signup');
    }
}
