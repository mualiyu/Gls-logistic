<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ebulksms;
use App\Models\Package;
use App\Models\Region;
use App\Models\Tracking;
use App\Models\User;
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


        if (count($package) > 0 && !$package[0]->status == 0) {
            $package = $package[0];
            # code...
            $tracking = Tracking::where('package_id', '=', $package->id)->orderBy('created_at', 'desc')->get();

            return view('admin.track.info', compact('package', 'tracking'));
        } else {
            return back()->with('error', 'Package Not found or Not activated');
        }
    }

    public function confirm_tracking(Request $request)
    {
        // return $request->all();
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


        if ($tracking) {

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

            // If package arrived at a location
            if ($request->a_d == '1') {
                $customer_data = [
                    'subject' => 'Package Receipt',
                    'email' => $package->customer->email,
                    // 'content' => "Bonjour Mr / Mme " . $tracking->package->name . ", votre commande est maintenant disponible. Vous serez contacté par un agent de liaison GLS.  \nVotre numéro tracking est " . $tracking->package->tracking_id . "\nMerci de suivile tracking de votre colis sur " . url('/') . ". \nRestant à votre disposition.",
                    'content' => 'Your Shipment has arrived at ' . $request->au_location . ' And your tracking number is ' . $tracking->package->tracking_id . '',
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
                // email data to client notice (mail)
                $data = [
                    'subject' => 'Package Receipt',
                    'email' => $package->email,
                    'content' => "Bonjour " . $package->name . ", votre commande Orange n° " . $package->tracking_id . " vient d'arriver à " . $request->au_location . ". Un agent GLS vous contacte dans un instant. Merci de votre disponibilité.",
                    // 'content' => "Bonjour Mr/Mme " . $package->name . ", Votre commande " . $tracking->package->tracking_id . " vient d'arriver à " . $request->au_location . " et confirmée par un agent GLS. Vous serez contacté chaque fois qu'il y aura une nouvelle mise à jour. \n Suivez votre commande ici : " . route('main_get_track_info_get', ['t_id' => $tracking->package->tracking_id]) . ". \n merci pour votre disponibilité",
                    // 'content' => 'Your Shipment has arrived at ' . $request->au_location . ' And your tracking number is ' . $tracking->package->tracking_id . '',
                ];
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


                // Phone data to client notice (sms)
                // $msg = "Bonjour Mr/Mme " . $package->name . ", \nVotre commande " . $tracking->package->tracking_id . " vient d'arriver à " . $request->au_location . " et confirmée par un agent GLS. Vous serez contacté chaque fois qu'il y aura une nouvelle mise à jour. \n Suivez votre commande ici : " . route('main_get_track_info_get', ['t_id' => $tracking->package->tracking_id]) . ". \nmerci pour votre disponibilité";
                $msg = $data['content'];
                $msg = strval($msg);

                // Disable SMS 
                try {
                    Http::get("https://api.sms.to/sms/send?api_key=gHdD8WP3soGaTjDsWTIp9yjgP1egtzIa&bypass_optout=true&to=+" . $to . "&message=" . $msg . "&sender_id=GLS");
                } catch (\Throwable $th) {
                    return back()->with('success', 'Package Has been Activated, But Receipt is sent to only contact Email and Not to contact Phone');
                }

                return back()->with('success', 'Package with ' . $package->tracking_id . ' tracking number Has been confirm at ' . $request->au_location . ', Update is sent to contact Email and phone');
                // $ff = "arrived at";



                // If package departed from location
            } elseif ($request->a_d == '2') {

                // Email to customer for change of location
                $customer_data = [
                    'subject' => 'Package Receipt',
                    'email' => $package->customer->email,
                    'content' => 'Your Shipment has departed from  ' . $request->au_location . ' And your tracking number is ' . $tracking->package->tracking_id . '',
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
                // email data to client notice for change in location
                $data = [
                    'subject' => 'Package Receipt',
                    'email' => $package->email,
                    'content' => "Bonjour " . $package->name . ", votre commande Orange n° " . $tracking->package->tracking_id . " vient de partir de " . $request->au_location . ". Vous serez contactez à la prochaine étape. Merci de votre confiance.",
                    // 'content' => "Bonjour Mr/Mme " . $package->name . ", \nVotre commande " . $tracking->package->tracking_id . " vient de Partir de" . $request->au_location . " et confirmée par un agent GLS. Vous serez contacté chaque fois qu'il y aura une nouvelle mise à jour.  \n Suivez votre commande ici : " . route('main_get_track_info_get', ['t_id' => $tracking->package->tracking_id]) . ". \nmerci pour votre disponibilité",

                ];
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

                // Phone data notice (sms) to client
                $msg = $data['content'];
                $msg = strval($msg);

                // Disable SMS 
                try {
                    Http::get("https://api.sms.to/sms/send?api_key=gHdD8WP3soGaTjDsWTIp9yjgP1egtzIa&bypass_optout=true&to=+" . $to . "&message=" . $msg . "&sender_id=GLS");
                } catch (\Throwable $th) {
                    return back()->with('success', 'Package Has been Activated, But Receipt is sent to only contact Email and Not to contact Phone');
                }

                return back()->with('success', 'Package with ' . $package->tracking_id . ' tracking number Has been confirm at ' . $request->au_location . ', Update is sent to contact Email and phone');
                // $ff = "detapted";


                // If package Dispatched to customer
            } elseif ($request->a_d == '3') {

                $customer_data = [
                    'subject' => 'Package Receipt',
                    'email' => $package->customer->email,
                    'content' => "Your Package with tracking number of " . $tracking->package->tracking_id . " is at destination location and has been dispatched to  " . $package->address_to . ". \nAnd your shipment tracking number is " . $tracking->package->tracking_id . "",
                    // 'content' => 'Your Shipment has arrived at ' . $request->au_location . ' And your tracking number is ' . $tracking->package->tracking_id . '',
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
                // email data notice (mail)
                $data = [
                    'subject' => 'Package Receipt',
                    'email' => $package->email,
                    'content' => "Bonjour Mr/Mme " . $package->name . " \nVotre commande N " . $package->tracking_id . " est arrivée à destination (" . $package->to . ") \nMerci de confirmer la livraison dès réception de votre colis.  \n Suivez votre commande ici : " . route('main_get_track_info_get', ['t_id' => $tracking->package->tracking_id]) . ".",
                    // 'content' => "Your Package with tracking number of " . $tracking->package->tracking_id . " is at destination location and has been dispatched to  " . $package->address_to . ". \nAnd your shipment tracking number is " . $tracking->package->tracking_id . "",
                ];
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

                // Phone data notice (sms)
                $msg = "Bonjour Mr/Mme " . $package->name . " \nVotre commande N " . $package->tracking_id . " est arrivée à destination (" . $package->to . ") \nMerci de confirmer la livraison dès réception de votre colis. \n Suivez votre commande ici : " . route('main_get_track_info_get', ['t_id' => $tracking->package->tracking_id]) . ". ";
                // $msg = "Dear " . $package->phone . ". \n\nYour Package with tracking number of " . $tracking->package->tracking_id . " is at destination location and has been dispatched to  " . $package->address_to . ". \n  \nTo track your package current location follow this link: {" . url('/track') . "} ";
                $msg = strval($msg);

                // Disable SMS 
                try {
                    Http::get("https://api.sms.to/sms/send?api_key=gHdD8WP3soGaTjDsWTIp9yjgP1egtzIa&bypass_optout=true&to=+" . $to . "&message=" . $msg . "&sender_id=GLS");
                } catch (\Throwable $th) {
                    return back()->with('success', 'Package Has been Activated, But Receipt is sent to only contact Email and Not to contact Phone');
                }

                return back()->with('success', 'Package with ' . $package->tracking_id . ' tracking number Has been confirm at ' . $request->au_location . ', Update is sent to contact Email and phone');
                // $ff = "arrived at";
            }

            return back()->with('success', 'Package with ' . $package->tracking_id . ' tracking number Has been confirm at ' . $request->au_location . ', Update is sent to contact Email and phone');
        } else {
            return back()->with('error', 'Fail to Add Tracker.');
        }

        return $request->all();
    }


    // Delivery confirmation
    public function confirm_delivery(Request $request)
    {
        // dd($user[0]->picture);
        $validator = Validator::make($request->all(), [
            "p_id" => "required",
            "delivery_image" => "image|mimes:jpeg,jpg,png,gif|max:9000|required",
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors());
        }

        $package = Package::find($request->p_id);

        if ($request->hasFile("delivery_image")) {
            $imageNameWExt = $request->file("delivery_image")->getClientOriginalName();
            $imageName = pathinfo($imageNameWExt, PATHINFO_FILENAME);
            $imageExt = $request->file("delivery_image")->getClientOriginalExtension();

            $imageNameToStore = $package->tracking_id . "_" . time() . "." . $imageExt;


            $request->file("delivery_image")->storeAs("public/package/delivery", $imageNameToStore);
        } else {
            // $imageNameToStore = $user[0]->picture;
            return back()->with('error', 'Delivery file not Confirmed, Try Again.');
        }

        $imageNameToStore = url("/storage/package/delivery/$imageNameToStore");

        if ($package->c_way_bill == null) {
            $validator = Validator::make($request->all(), [
                "way_bill" => "required",
                // "s_by" => "nullable",
            ]);

            if ($validator->fails()) {
                return back()->with('error', $validator->errors());
            }

            if ($package->delivery_image == null) {
                $validator = Validator::make($request->all(), [
                    // "way_bill" => "required",
                    "s_by" => "required",
                ]);

                if ($validator->fails()) {
                    return back()->with('error', $validator->errors());
                }
                $imageNameToStore = $imageNameToStore;

                $arrayToUpdate = [
                    "delivery_image" => $imageNameToStore,
                    "way_bill" => $request->way_bill,
                    "s_by" => $request->s_by,
                    "status" => '2',
                ];

                $package_update = Package::where('id', '=', $package->id)->update($arrayToUpdate);

                if ($package_update) {
                    $p = Package::find($package->id);

                    $customer_data = [
                        'subject' => 'Package Receipt',
                        'email' => $p->customer->email,
                        'content' => "Your package has been delivered successfully to " . $p->address_to . "" . $p->to . " \nAnd its been confirmed by Agent [" . Auth::user()->name . "] And received by [" . $request->s_by . "]\n\nAnd your tracking number is " . $p->tracking_id . "",
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
                        'content' => "Bonjour " . $p->name . ", votre commande Orange n° " . $p->tracking_id . " a été livrée avec succès à " . $p->address_to . "" . $p->to . " comme confirmée par l'agent GLS " . Auth::user()->name . " et reçue par " . $p->s_by . ". Merci de votre confiance.",
                        // 'content' => "Bonjour " . $p->name . "\nVotre colis a été livré avec succès à " . $p->address_to . ", " . $p->to . " Et il a été confirmé par l'agent [" . Auth::user()->name . "] Et reçu par [" . $p->s_by . "] Et votre numéro de commande est " . $p->tracking_id . " \nPour suivre votre envoi, suivez ce lien : {" . route('main_get_track_info_get', ['t_id' => $p->tracking_id]) . "} ",
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


                    // $msg = "Bonjour " . $p->name . "\nVotre colis a été livré avec succès à " . $p->address_to . ", " . $p->to . " Et il a été confirmé par l'agent [" . Auth::user()->name . "] Et reçu par [" . $p->name . "] Et votre numéro de commande est " . $p->tracking_id . " \nPour suivre votre envoi, suivez ce lien : {" . route('main_get_track_info_get', ['t_id' => $p->tracking_id]) . "} ";
                    $msg = $data['content'];
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
                    // try sending sms to contact phone
                    try {
                        Http::get("https://api.sms.to/sms/send?api_key=gHdD8WP3soGaTjDsWTIp9yjgP1egtzIa&bypass_optout=true&to=+" . $to . "&message=" . $msg . "&sender_id=GLS");
                    } catch (\Throwable $th) {
                        return back()->with('success', 'Package Has been Delivery confirmed to ' . $p->to . ', But Receipt is sent to only contact Email and Not to contact Phone');
                    }

                    // sending email to Admins (Gls)
                    $admins = User::where('p', '=', 1)->get();
                    foreach ($admins as $admin) {
                        $mss = "Package delivery is confirmed by  " . Auth::user()->name . "(" . Auth::user()->email . ") and it's signed by " . $request->s_by . ". \n\nThe tracking number is " . $p->tracking_id . ". \n\n Thank you! ";
                        // $mss = "Package is Created by " . $p->customer->name . "(" . $p->customer->email . ") and you need to take action. \n\nThe tracking number is " . $p->tracking_id . ". \n\n" . route('main_get_track_info_get', ['t_id' => $p->tracking_id]) . " Follow this link to make action. ";

                        $new_a = substr($admin->phone, 0, 1);
                        if ($new_a == 0) {
                            $d_a = substr($admin->phone, -10);
                            $num_a = '234' . $d_a;
                        } elseif ($new_a == 6) {
                            $d_a = substr($admin->phone, -9);
                            $num_a = '237' . $d_a;
                        } else {
                            $num_a = $admin->phone;
                        }
                        $to_a = $num_a;

                        $data_a = [
                            'subject' => 'Admin Notification',
                            'email' => $admin->email,
                            // 'c_email' => $p->customer->email,
                            'content' => $mss,
                        ];

                        try {
                            Http::get("https://api.sms.to/sms/send?api_key=gHdD8WP3soGaTjDsWTIp9yjgP1egtzIa&bypass_optout=true&to=+" . $to_a . "&message=" . $mss . "&sender_id=GLS");

                            Mail::send('main.email.receipt', $data_a, function ($message) use ($data_a) {
                                $message->from('info@gls.com', 'GLS');
                                $message->sender('info@gls.com', 'GLS');
                                $message->to($data_a['email']);
                                $message->subject($data_a['subject']);
                            });
                        } catch (\Throwable $th) {
                            return back()->with('success', 'Package Has been Activated, Receipt is sent to contact Email but not Phone');
                        }
                    }

                    return back()->with(['success' => "Package delivery has confirmed And notification is sent on both ends, Thank you for using this service."]);
                }

                // else
            } else {
                $imageNameToStore = $package->delivery_image . ', ' . $imageNameToStore;

                $arrayToUpdate = [
                    "delivery_image" => $imageNameToStore,
                    "c_way_bill" => $request->way_bill,
                    "status" => '2',
                ];

                $package_update = Package::where('id', '=', $package->id)->update($arrayToUpdate);

                if ($package_update) {
                    $p = Package::find($package->id);

                    return back()->with(['success' => "Package delivery has updated updated, Thank you for using this service."]);
                }
            }
        } else {
            $imageNameToStore = $package->delivery_image . ', ' . $imageNameToStore;

            $arrayToUpdate = [
                "delivery_image" => $imageNameToStore,
            ];

            $package_update = Package::where('id', '=', $package->id)->update($arrayToUpdate);

            if ($package_update) {
                $p = Package::find($package->id);

                return back()->with(['success' => "Document is uploaded successful, Thank you for using this service."]);
            }
        }
    }
}
