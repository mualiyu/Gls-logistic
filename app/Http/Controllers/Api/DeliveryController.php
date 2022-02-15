<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api;
use App\Models\Package;
use App\Models\Tracking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class DeliveryController extends Controller
{
    public function Index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'api_user' => 'required',
            'api_key' => 'required',
            "package_id" => "required",
            "agent_id" => "required",
            "delivery_image" => "image|mimes:jpeg,jpg,png,gif|max:9000|required",
            // rr
            "way_bill" => "required",
            "sign_by" => "required",
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
                $agent = User::find($request->agent_id);
                $package = Package::find($request->package_id);

                if ($request->hasFile("delivery_image")) {
                    $imageNameWExt = $request->file("delivery_image")->getClientOriginalName();
                    $imageName = pathinfo($imageNameWExt, PATHINFO_FILENAME);
                    $imageExt = $request->file("delivery_image")->getClientOriginalExtension();

                    $imageNameToStore = $package->tracking_id . "_" . time() . "." . $imageExt;


                    $request->file("delivery_image")->storeAs("public/package/delivery", $imageNameToStore);
                } else {

                    $res = [
                        'status' => false,
                        'data' => "Delivery file not Confirmed, Try Again."
                    ];
                    return response()->json($res);
                }

                $imageNameToStore = url("/storage/package/delivery/$imageNameToStore");

                if ($package->way_bill == null || $package->status == 2) {
                    $tracking = Tracking::where('package_id', '=', $package->id)->orderBy("created_at", "desc")->get();
                    if ($tracking[0]->current_location == $package->to) {
                        $arrayToUpdate = [
                            "delivery_image" => $imageNameToStore,
                            "way_bill" => $request->way_bill,
                            "s_by" => $request->sign_by,
                            "status" => '2',
                        ];

                        $package_update = Package::where('id', '=', $package->id)->update($arrayToUpdate);

                        if ($package_update) {
                            $p = Package::find($package->id);

                            $customer_data = [
                                'subject' => 'Package Receipt',
                                'email' => $p->customer->email,
                                'content' => "Your package has been delivered successfully to " . $p->address_to . "" . $p->to . " \nAnd its been confirmed by Agent [" . $agent->name . "] And received by [" . $request->sign_by . "]\n\nAnd your tracking number is " . $p->tracking_id . "",
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


                            // 
                            // email to client for delivery confirmation
                            $data = [
                                'subject' => 'Package Delivery notice',
                                'email' => $p->email,
                                'content' => "Bonjour " . $p->name . "\nVotre colis a été livré avec succès à " . $p->address_to . ", " . $p->to . " Et il a été confirmé par l'agent [" . $agent->name . "] Et reçu par [" . $p->s_by . "] Et votre numéro de commande est " . $p->tracking_id . " \nPour suivre votre envoi, suivez ce lien : {" . route('main_get_track_info_get', ['t_id' => $p->tracking_id]) . "} ",
                                // 'content' => "Your package has been delivered successfully to " . $p->address_to . "" . $p->to . " \nAnd its been confirmed by Agent [" . Auth::user()->name . "] And received by [" . $p->email . "]\n\nAnd your tracking number is " . $p->tracking_id . "",
                            ];
                            try {
                                Mail::send('main.email.receipt', $data, function ($message) use ($data) {
                                    $message->from('info@gls.com', 'GLS');
                                    $message->sender('info@gls.com', 'GLS');
                                    $message->to($data['email']);
                                    $message->subject($data['subject']);
                                });
                            } catch (\Throwable $th) {
                                // return back()->with('success', 'Package Has been Delivery confirmed to ' . $p->to . ', Update is Not sent to contact Email');
                            }


                            $msg = "Bonjour " . $p->name . "\nVotre colis a été livré avec succès à " . $p->address_to . ", " . $p->to . " Et il a été confirmé par l'agent [" . $agent->name . "] Et reçu par [" . $p->s_by . "] Et votre numéro de commande est " . $p->tracking_id . " \nPour suivre votre envoi, suivez ce lien : {" . route('main_get_track_info_get', ['t_id' => $p->tracking_id]) . "} ";
                            $msg = strval($msg);

                            $new = substr($p->phone, 0, 1);

                            if ($new == 0) {
                                $d = substr($p->phone, -10);
                                $num = '234' . $d;
                            } elseif ($new == 6) {
                                $d = substr($p->phone, -9);
                                $num = '237' . $d;
                            } else {
                                $num = $p->phone;
                            }
                            $to = $num;

                            // Disable SMS 
                            // // try sending sms to contact phone
                            // try {
                            //     Http::get("https://api.sms.to/sms/send?api_key=gHdD8WP3soGaTjDsWTIp9yjgP1egtzIa&bypass_optout=true&to=+" . $to . "&message=" . $msg . "&sender_id=GLS");
                            // } catch (\Throwable $th) {
                            //     return back()->with('success', 'Package Has been Delivery confirmed to ' . $p->to . ', But Receipt is sent to only contact Email and Not to contact Phone');
                            // }

                            $res = [
                                'status' => true,
                                'data' => "Package delivery has confirmed And notification is sent to both client & GLS customer, Thank you.",
                            ];
                            return response()->json($res);
                            // return back()->with(['success' => "Package delivery has confirmed And notification is sent on both ends, Thank you for using this service."]);
                        }
                    } else {
                        $res = [
                            'status' => false,
                            'data' => 'Package current location is not the same as the destination location.'
                        ];
                        return response()->json($res);
                    }
                } else {
                    $res = [
                        'status' => false,
                        'data' => 'Package delivery is already confirmed.'
                    ];
                    return response()->json($res);
                }

                // 
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
