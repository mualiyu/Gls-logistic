<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $a_packages = Package::where('status', '=', 1)->get();
        $ina_packages = Package::where('status', '=', 0)->get();
        $d_packages = Package::where('status', '=', 2)->get();
        return view('admin.index', compact('a_packages', 'ina_packages', 'd_packages'));
    }
}
