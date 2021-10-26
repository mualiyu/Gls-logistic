<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api;
use App\Models\Item;
use App\Models\Package;
use App\Models\Tracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class PackageApiController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'api_user' => 'required',
            'api_key' => 'required',
            'customer' => 'required',
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

                $package = Package::where('customer_id', '=', $request->customer)->get();
                if (count($package) > 0) {
                    $res = [
                        'status' => true,
                        'data' => $package,
                    ];
                    return response()->json($res);
                } else {
                    $res = [
                        'status' => false,
                        'data' => 'Shipment Not Found',
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
            'package_id' => 'required',
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

                $package = Package::find($request->package_id);
                if ($package) {
                    $res = [
                        'status' => true,
                        'data' => $package
                    ];
                    return response()->json($res);
                } else {
                    $res = [
                        'status' => false,
                        'data' => 'Shipment Not Found',
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
            'customer' => 'required',
            'from' => 'required',
            'to' => 'required',
            'address_from' => 'required',
            'address_to' => 'required',
            'status' => 'nullable',
            'item_name' => 'required',
            'item_description' => 'required',
            'item_length' => 'required',
            'item_width' => 'required',
            'item_height' => 'required',
            'item_weight' => 'required',
            'amount' => 'required',
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

                $tracking_id = rand(100000000000, 999999999999);

                $arrayToInsert = [
                    'customer_id' => $request->customer,
                    'from' => $request->from,
                    'to' => $request->to,
                    'address_from' => $request->address_from,
                    'address_to' => $request->address_to,
                    'tracking_id' => $tracking_id,
                    'adjusted_amount' => 0,
                    'total_amount' => 0,
                    'status' => 0,
                ];

                $package = Package::create($arrayToInsert);

                Package::where('id', '=', $package->id)->update([
                    'adjusted_amount' => '0',
                    'total_amount' => '0',
                    'status' => '0',
                ]);

                if ($package) {
                    $item = Item::create([
                        'package_id' => $package->id,
                        'name' => $request->item_name,
                        'description' => $request->item_description,
                        'length' => $request->item_length,
                        'height' => $request->item_height,
                        'width' => $request->item_width,
                        'weight' => $request->item_weight,
                    ]);

                    if ($item) {

                        $a = $request->amount * 100;
                        $amount = $package->total_amount + $a;

                        Package::where('id', '=', $package->id)->update([
                            "status" => 1,
                            "total_amount" => $amount,
                        ]);

                        $tracking = Tracking::create([
                            'package_id' => $package->id,
                            'current_location' => $package->from,
                            'a_d' => 1,
                        ]);
                        Tracking::where('id', '=', $tracking->id)->update([
                            'a_d' => 1
                        ]);

                        if ($tracking) {
                            $data = [
                                'subject' => 'Package Receipt',
                                'email' => $package->customer->email,
                                'content' => 'Your Package has been Activated successfully \n And your tracking number is ' . $tracking->package->tracking_id . '',
                            ];

                            try {
                                Mail::send('main.email.receipt', $data, function ($message) use ($data) {
                                    $message->from('info@gls.com', 'GLS');
                                    $message->sender('info@gls.com', 'GLS');
                                    $message->to($data['email']);
                                    $message->subject($data['subject']);
                                });
                            } catch (\Throwable $th) {
                                // return back()->with('success', 'Package Has been Activated, Receipt is Not sent to Email');
                            }

                            $p_info = Package::where('id', '=', $package->id)->with('customer')->with('items')->with('trackings')->get();
                            $res = [
                                'status' => true,
                                'data' => $p_info,
                            ];
                            return response()->json($res);
                        } else {
                            Package::destroy($package->id);
                            Item::destroy($item->id);

                            $res = [
                                'status' => false,
                                'data' => 'Shipment tracking Not initiated, Try again.'
                            ];
                            return response()->json($res);
                        }

                        $res = [
                            'status' => true,
                            'data' => $package
                        ];
                        return response()->json($res);
                    } else {
                        Package::destroy($package->id);
                        $res = [
                            'status' => false,
                            'data' => 'Shipment item has not been Created, try again.'
                        ];
                        return response()->json($res);
                    }
                } else {
                    $res = [
                        'status' => false,
                        'data' => 'Shipment Not Created'
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
            'from' => 'required',
            'to' => 'required',
            'address_from' => 'required',
            'address_to' => 'required',
            'status' => 'nullable',
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

                $arrayToUpdate = [
                    'from' => $request->from,
                    'to' => $request->to,
                    'address_from' => $request->address_from,
                    'address_to' => $request->address_to,
                    'status' => $request->status,
                ];
                $package = Package::where('id', '=', $request->package_id)->update($arrayToUpdate);
                if ($package) {
                    $p = Package::find($request->package_id);
                    $res = [
                        'status' => true,
                        'data' => $p
                    ];
                    return response()->json($res);
                } else {
                    $res = [
                        'status' => false,
                        'data' => 'Shipment Not Updated'
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
