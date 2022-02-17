<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api;
use App\Models\Item;
use App\Models\Location;
use App\Models\Package;
use App\Models\Tracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class PackageApiController extends Controller
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

                $package = Package::where('customer_id', '=', $api[0]->customer)->get();
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
                'data' => $validator->errors(),
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
            'address_to' => 'required',
            'status' => 'nullable',
            'item_type' => ['required'],
            'tracking_id' => ['required', 'unique:packages'],
            'item_description' => 'required',
            'item_weight' => 'required',
            'quantity' => ['required'],
            'client_email' => ['required'],
            'client_phone' => ['required'],
            'client_name' => ['required'],
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

                // $tracking_id = rand(100000000000, 999999999999);
                $location = Location::where('id', '=', $request->to)->get();

                if (count($location) > 0) {
                    $l_from = Location::find($request->from);
                    # code...
                    $arrayToInsert = [
                        'customer_id' => $api[0]->customer,
                        'from' => $l_from->location,
                        'to' => $location[0]->location,
                        'address_to' => $request->address_to,
                        'tracking_id' => $request->tracking_id,
                        'adjusted_amount' => 0,
                        'total_amount' => 0,
                        'status' => 0,
                        'email' => $request->client_email,
                        'name' => $request->client_name,
                        'phone' => $request->client_phone,
                    ];

                    $package = Package::create($arrayToInsert);

                    Package::where('id', '=', $package->id)->update([
                        'adjusted_amount' => '0',
                        'total_amount' => '0',
                        'status' => '0',
                        'email' => $request->client_email,
                        'phone' => $request->client_phone,
                        'name' => $request->client_name,
                    ]);

                    if ($package) {
                        $item = Item::create([
                            'package_id' => $package->id,
                            'name' => $request->item_type,
                            'description' => $request->item_description,
                            'weight' => $request->item_weight,
                            'quantity' => $request->quantity,
                        ]);
                        Item::where('id', '=', $item->id)->update([
                            'weight' => $request->item_weight,
                            'quantity' => $request->quantity,
                        ]);

                        if ($item) {

                            // $a = $request->amount * 100;
                            $amount = $package->total_amount + $package->to_location->charges[0]->amount;

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

                                // mail to customer
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

                                // Client mail 
                                $client_data = [
                                    'subject' => 'Package Receipt',
                                    'email' => $package->email,
                                    'content' => "Bonjour Mr / Mme " . $tracking->package->name . ", votre commande est maintenant disponible. Vous serez contacté par un agent de liaison GLS.  \nVotre numéro tracking est " . $tracking->package->tracking_id . "\nMerci de suivile tracking de votre colis sur " . route('main_get_track_info_get', ['t_id' => $tracking->package->tracking_id]) . ". \nRestant à votre disposition.",
                                ];
                                try {
                                    Mail::send('main.email.receipt', $client_data, function ($message) use ($client_data) {
                                        $message->from('info@gls.com', 'GLS');
                                        $message->sender('info@gls.com', 'GLS');
                                        $message->to($client_data['email']);
                                        $message->subject($client_data['subject']);
                                    });
                                } catch (\Throwable $th) {
                                    // return back()->with('success', 'Package Has been Activated, Receipt is Not sent to Email');
                                }

                                // sms Exit client

                                // client
                                $msg_c = "Bonjour Mr / Mme " . $tracking->package->name . ", votre commande est maintenant disponible. Vous serez contacté par un agent de liaison GLS.  \nVotre numéro tracking est " . $tracking->package->tracking_id . "\nMerci de suivile tracking de votre colis sur " . route('main_get_track_info_get', ['t_id' => $tracking->package->tracking_id]) . ". \nRestant à votre disposition.";
                                $msg_c = strval($msg_c);
                                $new_c = substr($package->phone, 0, 1);
                                if ($new_c == 0) {
                                    $d = substr($package->phone, -10);
                                    $num = '234' . $d;
                                } elseif ($new_c == 6) {
                                    $d = substr($package->phone, -9);
                                    $num = '237' . $d;
                                } else {
                                    $num = $package->phone;
                                }
                                $to_client = $num;


                                // customer
                                $msg = "Your Package has been Activated successfully \n And your tracking number is " . $tracking->package->tracking_id . ". \n \nTo track your shipment follow this link: {" . route('main_get_track_info_get', ['t_id' => $tracking->package->tracking_id]) . "} ";
                                $msg = strval($msg);
                                $new = substr($package->customer->phone, 0, 1);
                                if ($new == 0) {
                                    $d = substr($package->customer->phone, -10);
                                    $numm = '234' . $d;
                                } elseif ($new == 6) {
                                    $d = substr($package->customer->phone, -9);
                                    $numm = '237' . $d;
                                } else {
                                    $numm = $package->customer->phone;
                                }
                                $to_customer = $numm;


                                // Disable SMS 
                                // try {
                                //     Http::get("https://api.sms.to/sms/send?api_key=gHdD8WP3soGaTjDsWTIp9yjgP1egtzIa&bypass_optout=true&to=+" . $to_client . "&message=" . $msg_c . "&sender_id=GLS");
                                //     Http::get("https://api.sms.to/sms/send?api_key=gHdD8WP3soGaTjDsWTIp9yjgP1egtzIa&bypass_optout=true&to=+" . $to_customer . "&message=" . $msg . "&sender_id=GLS");
                                // } catch (\Throwable $th) {

                                //     // return back()->with('success', 'Package Has been Activated, Receipt is sent to contact Email but not Phone');
                                // }

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
                        'data' => " 'To field' not found in system, Please check locations Api."
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
            // 'address_from' => 'required',
            'address_to' => 'required',
            'status' => 'nullable',
            'client_phone' => 'required',
            'client_name' => 'required',
            'client_email' => 'required'
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

                $arrayToUpdate = [
                    'from' => $request->from,
                    'to' => $request->to,
                    // 'address_from' => $request->address_from,
                    'address_to' => $request->address_to,
                    // 'status' => $request->status,
                    'name' => $request->client_name,
                    'email' => $request->client_email,
                    'phone' => $request->client_phone,
                ];
                $package = Package::where('id', '=', $request->package_id)->update($arrayToUpdate);
                if ($package) {
                    $p = Package::where('id', '=', $request->package_id)->with('customer')->with('items')->with('trackings')->get();
                    $res = [
                        'status' => true,
                        'data' => $p[0]
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
