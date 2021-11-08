<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api;
use App\Models\Package;
use App\Models\Tracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TrackingApiController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'api_user' => 'required',
            'api_key' => 'required',
            'tracking_number' => 'required',
        ]);

        if ($validator->fails()) {
            $res = [
                'status' => false,
                'data' => $validator,
            ];
            return response()->json($res);
        }

        $api = Api::where('api_user', '=', $request->api_user)->get();
        if (count($api) > 0) {
            if ($api[0]->api_key == $request->api_key) {

                $package = Package::where('tracking_id', '=', $request->tracking_number)->get();

                if (count($package) > 0) {

                    if ($package[0]->status != 0) {
                        $tracking = Tracking::where('package_id', '=', $package[0]->id)->orderBy('created_at', 'desc')->get();

                        if (!count($tracking) > 0) {
                            $res = [
                                'status' => false,
                                'data' => 'No tracking detail found',
                            ];
                        }
                        $p = Package::where('id', '=', $package[0]->id)->with('trackings')->with('items')->get();

                        // $t_info = [
                        //     'package' => $package[0],
                        //     'track' => $tracking,
                        // ];

                        $res = [
                            'status' => true,
                            'data' => $p,
                        ];
                        return response()->json($res);
                    } else {
                        $res = [
                            'status' => false,
                            'data' => 'Shipment Not Activated',
                        ];
                        return response()->json($res);
                        // return back()->with('error', 'Package with this tracking number is not Activated in system, Make sure package is activated and try again.')->withInput();
                    }
                } else {
                    $res = [
                        'status' => false,
                        'data' => 'Shipment with this number Not Found',
                    ];
                    return response()->json($res);
                }
            } else {
                $res = [
                    'status' => false,
                    'data' => 'API_KEY Not correct'
                ];
                return response()->json($res);
            }
        } else {
            $res = [
                'status' => false,
                'data' => 'API_USER Not Found'
            ];
            return response()->json($res);
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'api_user' => 'required',
            'api_key' => 'required',
            'package_id' => 'required',
            'current_location' => 'required',
            'a_d' => 'required',
        ]);

        if ($validator->fails()) {
            $res = [
                'status' => false,
                'data' => $validator,
            ];
            return response()->json($res);
        }

        $api = Api::where('api_user', '=', $request->api_user)->get();
        if (count($api) > 0) {
            if ($api[0]->api_key == $request->api_key) {

                $package = Package::where('id', '=', $request->package_id)->get();

                if (count($package) > 0) {

                    if ($package[0]->status != 0) {
                        // $tracking = Tracking::where('package_id', '=', $package[0]->id)->orderBy('created_at', 'desc')->get();
                        $tracking = Tracking::create([
                            'package_id' => $request->package_id,
                            'current_location' => $request->current_location,
                            'a_d' => $request->a_d,
                        ]);
                        Tracking::where('id', '=', $tracking->id)->update([
                            'a_d' => $request->a_d,
                        ]);

                        if ($tracking) {
                            $p = Package::where('id', '=', $package[0]->id)->with('trackings')->with('items')->get();

                            $res = [
                                'status' => true,
                                'data' => $p,
                            ];
                            return response()->json($res);
                        } else {
                            $res = [
                                'status' => false,
                                'data' => 'Tracking location is not Updated',
                            ];
                            return response()->json($res);
                            // return back()->with('error', 'Package with this tracking number is not Activated in system, Make sure package is activated and try again.')->withInput();
                        }
                    } else {
                        $res = [
                            'status' => false,
                            'data' => 'Shipment Not Activated',
                        ];
                        return response()->json($res);
                        // return back()->with('error', 'Package with this tracking number is not Activated in system, Make sure package is activated and try again.')->withInput();
                    }
                } else {
                    $res = [
                        'status' => false,
                        'data' => 'Shipment with this number Not Found',
                    ];
                    return response()->json($res);
                }
            } else {
                $res = [
                    'status' => false,
                    'data' => 'API_KEY Not correct'
                ];
                return response()->json($res);
            }
        } else {
            $res = [
                'status' => false,
                'data' => 'API_USER Not Found'
            ];
            return response()->json($res);
        }
    }
}
