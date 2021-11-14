<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Tracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TrackingController extends Controller
{
    public function index()
    {
        return view('main.tracking.index');
    }

    public function get_track(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'track' => ['required', 'integer', 'digits_between:12,12'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $package = Package::where('tracking_id', '=', $request->track)->get();

        if (count($package) > 0) {
            if ($package[0]->status != 0) {
                $tracking = Tracking::where('package_id', '=', $package[0]->id)->orderBy('created_at', 'asc')->get();

                // return view('main.tracking.show_info', compact('package', 'tracking'));
                return redirect()->route('main_get_track_info_get', ['t_id' => $package[0]->tracking_id]);
            } else {
                return back()->with('error', 'Package with this tracking number is not Activated in system, Make sure package is activated and try again.')->withInput();
            }
        } else {
            return back()->with('error', 'Package with this tracking number Not Found in GLS system, Check your Tracking number and try again.')->withInput();
        }
    }

    // Track get request
    public function get_track_get($t_id)
    {

        if ($t_id) {
            $package = Package::where('tracking_id', '=', $t_id)->get();

            if (count($package) > 0) {
                if ($package[0]->status != 0) {
                    $tracking = Tracking::where('package_id', '=', $package[0]->id)->orderBy('created_at', 'asc')->get();

                    return view('main.tracking.show_info', compact('package', 'tracking'));
                } else {
                    return back()->with('error', 'Package with this tracking number is not Activated in system, Make sure package is activated and try again.')->withInput();
                }
            } else {
                return back()->with('error', 'Package with this tracking number Not Found in GLS system, Check your Tracking number and try again.')->withInput();
            }
        }
    }
}
