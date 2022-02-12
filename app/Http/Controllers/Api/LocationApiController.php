<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api;
use App\Models\Charge;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LocationApiController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'api_user' => 'required',
            'api_key' => 'required',
            // 'customer' => 'required',
        ]);

        if ($validator->fails()) {
            $res = [
                'status' => false,
                'data' => $validator->errors(),
            ];
            return response()->json($res);
        }

        $api = Api::where('api_user', '=', $request->api_user)->get();
        if (count($api) > 0) {
            if ($api[0]->api_key == $request->api_key) {

                $location = Location::orderBy('created_at', 'desc')->get();
                if (count($location) > 0) {
                    $res = [
                        'status' => true,
                        'data' => $location,
                    ];
                    return response()->json($res);
                } else {
                    $res = [
                        'status' => false,
                        'data' => 'Locations Not Found',
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

    public function getById(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'api_user' => 'required',
            'api_key' => 'required',
            'location_id' => 'required',
        ]);

        if ($validator->fails()) {
            $res = [
                'status' => false,
                'data' => $validator->errors(),
            ];
            return response()->json($res);
        }

        $api = Api::where('api_user', '=', $request->api_user)->get();
        if (count($api) > 0) {
            if ($api[0]->api_key == $request->api_key) {

                $location = Location::find($request->location_id);
                if ($location) {
                    $res = [
                        'status' => true,
                        'data' => $location,
                    ];
                    return response()->json($res);
                } else {
                    $res = [
                        'status' => false,
                        'data' => 'Location Not Found',
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

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'api_user' => 'required',
            'api_key' => 'required',
            'region' => ['required'],
            'city' => ['required'],
            'zone' => ['required'],
            'location' => ['required'],
            'amount' => ['required', 'integer'],
            'type' => ['required'],
        ]);

        if ($validator->fails()) {
            $res = [
                'status' => false,
                'data' => $validator->errors(),
            ];
            return response()->json($res);
        }

        $api = Api::where('api_user', '=', $request->api_user)->get();
        if (count($api) > 0) {
            if ($api[0]->api_key == $request->api_key) {


                $location = Location::create([
                    'region' => $request->region,
                    'city' => $request->city,
                    'zone' => $request->zone,
                    'location' => $request->location,
                    'type' => $request->type,
                ]);
                Location::where('id', '=',  $location->id)->update([
                    'type' => $request->type,
                ]);

                if ($location) {
                    $charge = Charge::create([
                        'location_id' => $location->id,
                        'amount' => $request->amount,
                    ]);

                    if ($charge) {
                        $l = Location::where('id', '=', $location->id)->with('charges')->get();
                        $res = [
                            'status' => true,
                            'data' => $l,
                        ];
                        return response()->json($res);
                        // return redirect()->route('admin_show_location', ['id' => $location->id])->with('success', 'Location created successfully');
                    } else {
                        $location->destroy();
                        $res = [
                            'status' => false,
                            'data' => 'Location Not Created'
                        ];
                        return response()->json($res);
                        // return back()->withInput()->with('error', 'Failed to create Location, try again');
                    }
                    $res = [
                        'status' => true,
                        'data' => $location
                    ];
                    return response()->json($res);
                } else {
                    $res = [
                        'status' => false,
                        'data' => 'Location Not Created'
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
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'api_user' => 'required',
            'api_key' => 'required',
            'location_id' => 'required',
        ]);

        if ($validator->fails()) {
            $res = [
                'status' => false,
                'data' => $validator->errors(),
            ];
            return response()->json($res);
        }

        $api = Api::where('api_user', '=', $request->api_user)->get();
        if (count($api) > 0) {
            if ($api[0]->api_key == $request->api_key) {
                $l = Location::find($request->location_id);

                if ($l) {
                    $c = Charge::where('location_id', '=', $l->id)->get();
                    Charge::destroy($c[0]->id);
                    $l->destroy($request->location_id);
                    $res = [
                        'status' => true,
                        'data' => "Location is deleted successfully",
                    ];
                    return response()->json($res);
                } else {
                    $res = [
                        'status' => false,
                        'data' => 'Location Not Found',
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
