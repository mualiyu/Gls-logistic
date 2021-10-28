<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ebulksms;
use App\Models\Package;
use App\Models\Region;
use App\Models\Tracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Throwable;

class AdminTrackController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('admin.track.index');
    }

    public function track(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tracking_number' => ['required', 'integer', 'digits_between:12,12'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $package = Package::where('tracking_id', '=', $request->tracking_number)->get();

        $package = $package[0];

        $tracking = Tracking::where('package_id', '=', $package->id)->orderBy('created_at', 'desc')->get();

        return view('admin.track.info', compact('package', 'tracking'));
    }

    public function confirm_tracking(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'au_location' => ['required'],
            'a_d' => ['required'],
            'p_id' => ['required'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $package = Package::find($request->p_id);

        $tracking = Tracking::create([
            'package_id' => $package->id,
            'current_location' => $request->au_location,
            'a_d' => $request->a_d,
        ]);
        Tracking::where('id', '=', $tracking->id)->update([
            'a_d' => $request->a_d,
        ]);

        if ($request->a_d == '1') {
            $ff = "arrived at";
        } else {
            $ff = "Dispatched from";
        }

        if ($tracking) {
            $data = [
                'subject' => 'Package Receipt',
                'email' => $package->email,
                'content' => 'Your Shipment has ' . $ff . ' ' . $request->au_location . ' And your tracking number is ' . $tracking->package->tracking_id . '',
            ];

            // ebulk sma data
            // $ebulk = new Ebulksms();


            $msg = "Dear " . $package->phone . " \nYour Shipment has " . $ff . " " . $request->au_location . " And your tracking number is " . $tracking->package->tracking_id . ". \n  \nTo track your shipment flow this link: {" . url('/track') . "} ";
            $msg = strval($msg);

            $new = substr($package->phone, 0, 1);

            if ($new == 0) {
                $d = substr($package->phone, -10);
                $num = '234' . $d;
            } elseif ($new == 6) {
                $d = substr($package->phone, -9);
                $num = '237' . $d;
            } else {
                $num = $package->phone;
            }
            $to = $num;

            try {
                Mail::send('main.email.receipt', $data, function ($message) use ($data) {
                    $message->from('info@gls.com', 'GLS');
                    $message->sender('info@gls.com', 'GLS');
                    $message->to($data['email']);
                    $message->subject($data['subject']);
                });
            } catch (\Throwable $th) {
                return back()->with('success', 'Package Has been confirm at ' . $request->au_location . ', Update is Not sent to contact Email');
            }

            // try sending sms to contact phone
            try {
                Http::get("https://api.sms.to/sms/send?api_key=gHdD8WP3soGaTjDsWTIp9yjgP1egtzIa&bypass_optout=true&to=+" . $to . "&message=" . $msg . "&sender_id=GLS");
            } catch (\Throwable $th) {
                return back()->with('success', 'Package Has been Activated, But Receipt is sent to only contact Email and Not to contact Phone');
            }
            // try {
            //     $ebulk->useJSON($from, $ss, $to);
            // } catch (Throwable $th) {
            //     return back()->with('success', 'Package Has been confirm at ' . $request->au_location . ', Update is Not sent to contact phone');
            // }

            return back()->with('success', 'Package with ' . $package->tracking_id . ' tracking number Has been confirm at ' . $request->au_location . ', Update is sent to contact Email and phone');
        } else {
            return back()->with('error', 'Fail to Add Tracker.');
        }

        return $request->all();
    }
}
