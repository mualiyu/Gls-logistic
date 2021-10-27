<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Region;
use App\Models\Tracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

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

        if ($request->a_b == 1) {
            $ff = "arrived at";
        } else {
            $ff = "Dispatched from";
        }
        if ($tracking) {
            $data = [
                'subject' => 'Package Receipt',
                'email' => $package->email,
                'content' => 'Your Shipment has ' . $ff . ' ' . $request->au_location . ' \n And your tracking number is ' . $tracking->package->tracking_id . '',
            ];

            try {
                Mail::send('main.email.receipt', $data, function ($message) use ($data) {
                    $message->from('info@gls.com', 'GLS');
                    $message->sender('info@gls.com', 'GLS');
                    $message->to($data['email']);
                    $message->subject($data['subject']);
                });
            } catch (\Throwable $th) {
                return back()->with('success', 'Package Has been confirm at ' . $request->au_location . ', Update is Not sent to customer Email');
            }

            return back()->with('success', 'Package Has been confirm at ' . $request->au_location . ', Update is sent to contact Email');
        } else {
            return back()->with('error', 'Fail to Add Tracker.');
        }

        return $request->all();
    }
}
