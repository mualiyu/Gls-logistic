<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ebulksms;
use App\Models\Package;
use App\Models\Region;
use App\Models\Tracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Throwable;

class AdminPackageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $packages = Package::orderBy('created_at', 'desc')->get();

        return view('admin.package.index', compact('packages'));
    }

    public function search_package(Request $request)
    {
        if ($request->ajax()) {

            $search = $request->search;

            $package = Package::orderBy('created_at', 'desc');

            if (is_string($search) && strlen($search) > 0) {
                // Search in users project
                $package = $package->where(function ($q) use ($search) {
                    $q->where('tracking_id', 'LIKE', '%' . $search . '%')
                        ->orWhere('address_from', 'LIKE', '%' . $search . '%')
                        ->orWhere('address_to', 'LIKE', '%' . $search . '%');
                });
            }

            $data = $package->get();


            $output = '';

            if (count($data) > 0) {

                // $output = '<ul class="list-group" style="display: block;">';
                $i = count($data);
                foreach ($data as $row) {

                    //Package items 
                    $items_s = '';
                    if (count($row->items) > 2) {
                        $items_s = $row->items[0]->name . ", " . $row->items[1]->name . ", and others";
                    } else {
                        foreach ($row->items as $item) {
                            $items_s .= $item->name . ", ";
                        }
                    }

                    // status
                    if ($row->status == 0) {
                        $status = "<span class='btn btn-warning'>Inactive</span>";
                    }
                    if ($row->status == 1) {
                        $status = "<span class='btn btn-success'>Active</span>";
                    }
                    if ($row->status == 2) {
                        $status = "<span class='btn btn-secondary'>Delivered</span>";
                    }

                    // output
                    $output .= '<tr> <td>' . $i . '</td> <td>' . $row->tracking_id .
                        '</td> <td>' . $row->items[0]->name . '</td><td>' . $row->total_amount .
                        '</td> <td>' . $status .
                        '</td> <td> <a href="' . route("admin_package_info", ["id" => $row->id]) . '" class="btn btn-primary">Open Package</a> </td> </tr>';

                    $i--;
                }

                // $output .= '</u>';
            } else {

                $output .= '<tr><td colspan="6" style="text-align:center;">No Data Found</td></tr>';
            }

            return $output;
        }
    }

    public function show_info($id)
    {
        $package = Package::find($id);

        return view('admin.package.info', compact('package'));
    }

    public function activate_package($id)
    {
        $p = Package::find($id);

        if (count($p->items) > 0) {
            $aa = $p->to_location->charges[0]->amount;
            $package = Package::where('id', '=', $id)->update([
                'status' => 1,
                'total_amount' => $aa,
            ]);

            if ($package) {
                // $r = Region::where('code', '=', $p->from)->get();
                $customer = $p->customer;
                $tracking = Tracking::create([
                    'package_id' => $id,
                    'current_location' => $p->from,
                    'a_d' => 2,
                ]);
                Tracking::where('id', '=', $tracking->id)->update([
                    'a_d' => 2,
                ]);
                if ($tracking) {
                    $customer_data = [
                        'subject' => 'Package Receipt',
                        'email' => $p->customer->email,
                        // 'content' => "Bonjour Mr / Mme " . $tracking->package->name . ", votre commande est maintenant disponible. Vous serez contacté par un agent de liaison GLS.  \nVotre numéro tracking est " . $tracking->package->tracking_id . "\nMerci de suivile tracking de votre colis sur " . url('/') . ". \nRestant à votre disposition.",
                        'content' => 'Your shipments has been Activated successfully and your tracking number is ' . $tracking->package->tracking_id . '',
                    ];
                    $data = [
                        'subject' => 'Package Receipt',
                        'email' => $p->email,
                        'content' => "Bonjour " . $tracking->package->name . "(" . $tracking->package->phone . "), votre commande Orange n° " . $tracking->package->tracking_id . " est maintenant disponible! Vous serez contacté par un agent de liaison GLS. Vous pouvez suivre votre colis sur " . route('main_get_track_info_get', ['t_id' => $tracking->package->tracking_id]) . ". Restant à votre disposition.",
                        // 'content' => "Bonjour Mr / Mme " . $tracking->package->name . ", votre commande est maintenant disponible. Vous serez contacté par un agent de liaison GLS.  \nVotre numéro tracking est " . $tracking->package->tracking_id . "\nMerci de suivile tracking de votre colis sur " . url('/track') . ". \nRestant à votre disposition.",
                        // 'content' => 'Your shipments has been Activated successfully and your tracking number is ' . $tracking->package->tracking_id . '',
                    ];

                    // $ebulk = new Ebulksms();

                    // $msg = "Bonjour Mr / Mme " . $tracking->package->name . ", votre commande est maintenant disponible. Vous serez contacté par un agent de liaison GLS.  \nVotre numéro tracking est " . $tracking->package->tracking_id . "\nMerci de suivile tracking de votre colis sur " . url('/track') . ". \nRestant à votre disposition.";
                    $msg = $data['content'];
                    $msg = strval($msg);

                    $new = substr($p->phone, 0, 1);

                    if ($new == 0) {
                        $d = substr($p->phone, -10);
                        $num = '234' . $d;
                    } else {
                        $d = substr($p->phone, -9);
                        $num = '237' . $d;
                    }
                    $to = $num;

                    // try sending email to customer
                    try {
                        Mail::send('main.email.c_receipt', $customer_data, function ($message) use ($customer_data) {
                            $message->from('no-reply@glscam.com', 'GLS');
                            $message->sender('no-reply@glscam.com', 'GLS');
                            $message->to($customer_data['email']);
                            $message->subject($customer_data['subject']);
                        });
                    } catch (\Throwable $th) {
                        // return back()->with('success', 'Package Has been Activated, But receipt is not sent to Contact email');
                    }

                    // try sending email to client
                    try {
                        Mail::send('main.email.receipt', $data, function ($message) use ($data) {
                            $message->from('no-reply@glscam.com', 'GLS');
                            $message->sender('no-reply@glscam.com', 'GLS');
                            $message->to($data['email']);
                            $message->subject($data['subject']);
                        });
                    } catch (\Throwable $th) {
                        return back()->with('success', 'Package Has been Activated, But receipt is not sent to Contact email');
                    }

                    // Disable SMS 
                    // try sending sms to contact phone
                    try {
                        // Http::get("http://nitrosms.cm/api_v1?sub_account=081_glsdelivery1&sub_account_pass=123456789&action=send_sms&sender_id=Gls_Delivery&message=" . $msg . "&recipients=" . $to);
                        Http::get("https://api.sms.to/sms/send?api_key=gHdD8WP3soGaTjDsWTIp9yjgP1egtzIa&bypass_optout=true&to=+" . $to . "&message=" . $msg . "&sender_id=GLS");
                    } catch (\Throwable $th) {

                        return back()->with('success', 'Package Has been Activated, But Receipt is sent to only contact Email and Not to contact Phone');
                    }

                    return back()->with('success', 'Package with ' . $p->tracking_id . ' tracking number Has been Activated, Receipt is sent to both Email and Phone');
                } else {
                    return back()->with('error', 'Fail to Add Tracker.');
                }
            } else {
                return back()->with('error', 'Package activation failed.');
            }
        } else {
            return back()->with('error', 'You cannot activate empty package, Make sure you add Item to the package before activating.');
        }
    }
}
