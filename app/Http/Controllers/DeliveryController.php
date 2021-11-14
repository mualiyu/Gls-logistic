<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class DeliveryController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware('customerAuth');
    // }


    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'package_id' => ['required'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $p = Package::find($request->package_id);
        if ($p) {
            # code...
            $otp = rand(100000, 999999);

            // $msg = "GLS\n your OTP is " . $otp . " will expire in the next 10-mins, Do not share otp, \n " . date("l jS \of F Y h:i:s A") . ".";
            // $msg = strval($msg);
            // $new = substr($p->phone, 0, 1);
            // if ($new == 0) {
            //     $d = substr($p->phone, -10);
            //     $num = '234' . $d;
            // } elseif ($new == 6) {
            //     $d = substr($p->phone, -9);
            //     $num = '237' . $d;
            // } else {
            //     $num = $p->phone;
            // }

            // $to = $num;
            // try {
            //     $send = Http::get("https://api.sms.to/sms/send?api_key=gHdD8WP3soGaTjDsWTIp9yjgP1egtzIa&bypass_optout=true&to=+" . $to . "&message=" . $msg . "&sender_id=GLS");
            // } catch (\Throwable $th) {
            //     return back()->with('error', 'Failed to send Opt, Try again!');
            // }
            $customer_data = [
                'subject' => 'Confirm Package delivery',
                'email' => $p->email,
                'content' => "GLS\n your OTP is " . $otp . " will expire in the next 10-mins, Do not share your otp, \n " . date("l jS \of F Y h:i:s A") . ".",
            ];
            try {
                Mail::send('main.email.c_receipt', $customer_data, function ($message) use ($customer_data) {
                    $message->from('info@gls.com', 'GLS');
                    $message->sender('info@gls.com', 'GLS');
                    $message->to($customer_data['email']);
                    $message->subject($customer_data['subject']);
                });
            } catch (\Throwable $th) {
                // return back()->with('success', 'Package Has been confirm at ' . $request->au_location . ', Update is Not sent to contact Email');
            }

            // return $send;

            $info = [
                'otp' => $otp,
                'data' => $request->all(),
            ];

            if (Cache::get($otp)) {
                Cache::forget($otp);

                Cache::put($otp, $info, now()->addMinutes(10));
            } else {
                Cache::put($otp, $info, now()->addMinutes(10));
            }

            return back()->with("otp", "yes");
        }


        // return Cache::get($otp);

    }


    public function verify_delivery(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => ['required'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $info = Cache::get($request->otp);

        // return $info;
        if ($info) {
            $package = Package::where('id', '=', $info['data']['package_id'])->update([
                'c_d' => 1,
            ]);

            return back()->with('success', 'You have Successful Confirmed your delivery, Thank you for using our service.');
        } else {
            return back()->with(['err' => 'Oops, Otp is incorrect', 'otp' => 'yes']);
        }

        return 1;
    }
}
