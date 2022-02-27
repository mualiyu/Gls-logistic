<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api;
use App\Models\Package;
use App\Models\Tracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
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
                'data' => $validator->errors(),
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
                'data' => $validator->errors(),
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

                            // sent notifications
                            $new = substr($package[0]->phone, 0, 1);
                            if ($new == 0) {
                                $d = substr($package[0]->phone, -10);
                                $num = '234' . $d;
                            } elseif ($new == 6) {
                                $d = substr($package[0]->phone, -9);
                                $num = '237' . $d;
                            } else {
                                $num = $package[0]->phone;
                            }
                            $to = $num;
                            // If package arrived at a location
                            if ($request->a_d == '1') {
                                $customer_data = [
                                    'subject' => 'Package Receipt',
                                    'email' => $package[0]->customer->email,
                                    // 'content' => "Bonjour Mr / Mme " . $tracking->package->name . ", votre commande est maintenant disponible. Vous serez contacté par un agent de liaison GLS.  \nVotre numéro tracking est " . $tracking->package->tracking_id . "\nMerci de suivile tracking de votre colis sur " . url('/') . ". \nRestant à votre disposition.",
                                    'content' => 'Your Shipment has arrived at ' . $request->current_location . ' And your tracking number is ' . $tracking->package->tracking_id . '',
                                ];
                                try {
                                    Mail::send('main.email.c_receipt', $customer_data, function ($message) use ($customer_data) {
                                        $message->from('no-reply@glscam.com', 'GLS');
                                        $message->sender('no-reply@glscam.com', 'GLS');
                                        $message->to($customer_data['email']);
                                        $message->subject($customer_data['subject']);
                                    });
                                } catch (\Throwable $th) {
                                    // return back()->with('success', 'Package Has been confirm at ' . $request->au_location . ', Update is Not sent to contact Email');
                                }



                                // 
                                // email data to client notice (mail)
                                $data = [
                                    'subject' => 'Package Receipt',
                                    'email' => $package[0]->email,
                                    'content' => "Bonjour " . $package[0]->name . ", votre commande Orange n° " . $package[0]->tracking_id . " vient d'arriver à " . $request->current_location . ". Un agent GLS vous contacte dans un instant. Merci de votre disponibilité.",
                                    // 'content' => "Bonjour Mr/Mme " . $package->name . ", Votre commande " . $tracking->package->tracking_id . " vient d'arriver à " . $request->au_location . " et confirmée par un agent GLS. Vous serez contacté chaque fois qu'il y aura une nouvelle mise à jour. \n Suivez votre commande ici : " . route('main_get_track_info_get', ['t_id' => $tracking->package->tracking_id]) . ". \n merci pour votre disponibilité",
                                    // 'content' => 'Your Shipment has arrived at ' . $request->au_location . ' And your tracking number is ' . $tracking->package->tracking_id . '',
                                ];
                                try {
                                    Mail::send('main.email.receipt', $data, function ($message) use ($data) {
                                        $message->from('no-reply@glscam.com', 'GLS');
                                        $message->sender('no-reply@glscam.com', 'GLS');
                                        $message->to($data['email']);
                                        $message->subject($data['subject']);
                                    });
                                } catch (\Throwable $th) {
                                    // return back()->with('success', 'Package Has been confirm at ' . $request->au_location . ', Update is Not sent to contact Email');
                                }


                                // Phone data to client notice (sms)
                                // $msg = "Bonjour Mr/Mme " . $package->name . ", \nVotre commande " . $tracking->package->tracking_id . " vient d'arriver à " . $request->au_location . " et confirmée par un agent GLS. Vous serez contacté chaque fois qu'il y aura une nouvelle mise à jour. \n Suivez votre commande ici : " . route('main_get_track_info_get', ['t_id' => $tracking->package->tracking_id]) . ". \nmerci pour votre disponibilité";
                                $msg = $data['content'];
                                $msg = strval($msg);

                                // Disable SMS 
                                try {
                                    Http::get("http://nitrosms.cm/api_v1?sub_account=081_glsdelivery1&sub_account_pass=123456789&action=send_sms&sender_id=Gls_Delivery&message=" . $msg . "&recipients=" . $to);
                                    // Http::get("https://api.sms.to/sms/send?api_key=gHdD8WP3soGaTjDsWTIp9yjgP1egtzIa&bypass_optout=true&to=+" . $to . "&message=" . $msg . "&sender_id=GLS");
                                } catch (\Throwable $th) {
                                    // return back()->with('success', 'Package Has been Activated, But Receipt is sent to only contact Email and Not to contact Phone');
                                }

                                // return back()->with('success', 'Package with ' . $package->tracking_id . ' tracking number Has been confirm at ' . $request->au_location . ', Update is sent to contact Email and phone');
                                // $ff = "arrived at";



                                // If package departed from location
                            } elseif ($request->a_d == '2') {

                                // Email to customer for change of location
                                $customer_data = [
                                    'subject' => 'Package Receipt',
                                    'email' => $package[0]->customer->email,
                                    'content' => 'Your Shipment has departed from  ' . $request->au_location . ' And your tracking number is ' . $tracking->package->tracking_id . '',
                                ];
                                try {
                                    Mail::send('main.email.c_receipt', $customer_data, function ($message) use ($customer_data) {
                                        $message->from('no-reply@glscam.com', 'GLS');
                                        $message->sender('no-reply@glscam.com', 'GLS');
                                        $message->to($customer_data['email']);
                                        $message->subject($customer_data['subject']);
                                    });
                                } catch (\Throwable $th) {
                                    // return back()->with('success', 'Package Has been confirm at ' . $request->au_location . ', Update is Not sent to contact Email');
                                }



                                // 
                                // email data to client notice for change in location
                                $data = [
                                    'subject' => 'Package Receipt',
                                    'email' => $package[0]->email,
                                    'content' => "Bonjour " . $package[0]->name . ", votre commande Orange n° " . $tracking->package->tracking_id . " vient de partir de " . $request->current_location . ". Vous serez contactez à la prochaine étape. Merci de votre confiance.",
                                    // 'content' => "Bonjour Mr/Mme " . $package->name . ", \nVotre commande " . $tracking->package->tracking_id . " vient de Partir de" . $request->au_location . " et confirmée par un agent GLS. Vous serez contacté chaque fois qu'il y aura une nouvelle mise à jour.  \n Suivez votre commande ici : " . route('main_get_track_info_get', ['t_id' => $tracking->package->tracking_id]) . ". \nmerci pour votre disponibilité",

                                ];
                                try {
                                    Mail::send('main.email.receipt', $data, function ($message) use ($data) {
                                        $message->from('no-reply@glscam.com', 'GLS');
                                        $message->sender('no-reply@glscam.com', 'GLS');
                                        $message->to($data['email']);
                                        $message->subject($data['subject']);
                                    });
                                } catch (\Throwable $th) {
                                    // return back()->with('success', 'Package Has been confirm at ' . $request->au_location . ', Update is Not sent to contact Email');
                                }

                                // Phone data notice (sms) to client
                                $msg = $data['content'];
                                $msg = strval($msg);

                                // Disable SMS 
                                try {
                                    Http::get("http://nitrosms.cm/api_v1?sub_account=081_glsdelivery1&sub_account_pass=123456789&action=send_sms&sender_id=Gls_Delivery&message=" . $msg . "&recipients=" . $to);
                                    // Http::get("https://api.sms.to/sms/send?api_key=gHdD8WP3soGaTjDsWTIp9yjgP1egtzIa&bypass_optout=true&to=+" . $to . "&message=" . $msg . "&sender_id=GLS");
                                } catch (\Throwable $th) {
                                    // return back()->with('success', 'Package Has been Activated, But Receipt is sent to only contact Email and Not to contact Phone');
                                }

                                // return back()->with('success', 'Package with ' . $package->tracking_id . ' tracking number Has been confirm at ' . $request->au_location . ', Update is sent to contact Email and phone');
                                // $ff = "detapted";


                                // If package Dispatched to customer
                            }
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
