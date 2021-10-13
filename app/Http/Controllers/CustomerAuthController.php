<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CustomerAuthController extends Controller
{
    public function show_signin()
    {
        if (session()->has('customer')) {
            return redirect()->route('main_home');
        }
        return view('main.auth.login');
    }

    public function show_signup()
    {
        if (session()->has('customer')) {
            return redirect()->route('main_home');
        }
        return view('main.auth.signup');
    }

    public function signin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $customer = Customer::where('username', '=', $request->username)->get();

        if (count($customer) > 0) {
            $p = hash('sha512', $request->password);
            if ($customer[0]->password == $p) {
                session(['customer' => $customer[0]]);
                return redirect()->route('main_home');
                // return $next($request);
            } else {
                return back()->with('error', 'Incorrect Password')->withInput();
            }
        } else {
            return back()->with('error', 'Username not found')->withInput();
        }
    }

    public function create_customer(Request $request)
    {
        // return $request->all();

        $validator = Validator::make($request->all(), [
            'fullname' => ['required', 'string'],
            'address' => ['required', 'string'],
            'email' => ['required', 'email', 'string', 'unique:customers'],
            'phone' => ['required', 'string', 'unique:customers'],
            'username' => ['required', 'string', 'unique:customers'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $customer = Customer::create([
            'name' => $request->fullname,
            'address' => $request->address,
            'email' => $request->email,
            'phone' => $request->phone,
            'username' => $request->username,
            'password' => hash('sha512', $request->password),
        ]);

        if ($customer) {
            session(['customer' => $customer]);
            return redirect()->route('main_home');
        } else {
            return back()->with('error', 'Customer not created, Try Again');
        }
    }

    public function customer_logout()
    {
        session()->forget('customer');

        return redirect()->route('main_signin');
    }
}
