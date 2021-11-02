<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use App\Models\Ebulksms;
use App\Models\Item;
use App\Models\Journey;
use App\Models\Location;
use App\Models\Package;
use App\Models\Region;
use App\Models\Tracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class PackageController extends Controller
{

    public function index()
    {
        $customer = session('customer');

        $packages = Package::where('customer_id', '=', $customer->id)->orderBy('created_at', 'desc')->get();

        return view('main.package.index', compact('packages'));
    }

    public function show()
    {
        return view('main.package.add');
    }

    public function create(Request $request)
    {
        // return $request->all();

        $validator = Validator::make($request->all(), [
            'from' => ['required'],
            'to' => ['required'],
            'to_address' => ['required'],
            // 'c_info' => ['required'],
            'item' => ['required'],
            'description' => ['required'],
            'weight' => ['nullable'],
            'quantity' => ['required'],
            'name' => ['required'],
            'email' => ['required'],
            'phone' => ['required'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }


        // check if Customer login session is active
        if (session()->has('customer')) {

            $customer = session('customer');
            $tracking_id = rand(100000000000, 999999999999);

            // if ($request->c_info == 0) {

            //     $package = Package::create([
            //         'customer_id' => $customer->id,
            //         'from' => $request->from,
            //         'to' => $request->to,
            //         'phone' => $customer->phone,
            //         'email' => $customer->email,
            //         'address_to' => $request->to_address,
            //         'tracking_id' => $tracking_id,
            //         'adjusted_amount' => 0,
            //         'total_amount' => 0,
            //         'status' => 0,
            //         // 'item_type' => $request->item,
            //     ]);
            //     Package::where('id', '=', $package->id)->update([
            //         'status' => 0,
            //     ]);
            // } else {
            //     $val = Validator::make($request->all(), [
            //         'phone' => ['required'],
            //         'email' => ['required'],
            //     ]);

            //     if ($val->fails()) {
            //         return back()->withErrors($val)->withInput();
            //     }

            $package = Package::create([
                'customer_id' => $customer->id,
                'from' => $request->from,
                'to' => $request->to,
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'address_to' => $request->to_address,
                'tracking_id' => $tracking_id,
                'adjusted_amount' => 0,
                'total_amount' => 0,
                'status' => 0,
                // 'item_type' => $request->item,
            ]);
            Package::where('id', '=', $package->id)->update([
                'status' => 0,
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
            ]);
            // }


            if ($package) {
                $item = Item::create([
                    'package_id' => $package->id,
                    'name' => $request->item,
                    'description' => $request->description,
                    'weight' => $request->weight ?? '',
                    'quantity' => $request->quantity,
                ]);
                Item::where('id', '=', $item->id)->update([
                    'weight' => $request->weight ?? '0',
                    'quantity' => $request->quantity,
                ]);
                if ($item) {
                    return redirect()->route('main_show_activate_package', ['id' => $package->id]);
                } else {
                    $package->destroy();
                    return back()->with('error', 'Package Item not created, try again');
                }
            } else {
                return back()->with('error', 'Package not created, try again');
            }
        } else {
            return redirect()->route('main_signup');
        }

        // return $request->all();
    }

    public function get_to_region(Request $request)
    {
        if ($request->ajax()) {

            $d = $request->dept;

            $journey = Journey::where('departure', $d)->get();

            $output = '';

            if (count($journey) > 0) {

                // $output = '<ul class="list-group" style="display: block;">';
                $i = 0;
                foreach ($journey as $row) {
                    // $output .= '<li class="list-group-item"><div class="form-check"><input class="form-check-input" type="checkbox" name="cus[]" value="'
                    //     . $row->id . '" id="cus[' . $i . ']"><label class="form-check-label" for="cus[' . $i . ']">'
                    //     . $row->name . ' - ' . $row->email . ' - ' . $row->phone .
                    //     '</label></div></li>';
                    $output .= '<option value="' . $row->region->state . '">' . $row->region->state . '</option>';
                    $i++;
                }

                $output .= '';
            } else {

                $output .= '<option value="">No arrival region found in system</option>';
            }

            return $output;
        }
    }

    public function show_activate_package($id)
    {
        $package = Package::find($id);

        return view('main.package.activate_package', compact('package'));
    }

    public function add_item(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'description' => ['required'],
            'length' => ['required'],
            'height' => ['required'],
            'width' => ['required'],
            'weight' => ['required'],
            'amount' => ['required'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $p = Package::find($request->p_id);

        $item = Item::create([
            'package_id' => $request->p_id,
            'name' => $request->name,
            'description' => $request->description,
            'length' => $request->length,
            'height' => $request->height,
            'width' => $request->width,
            'weight' => $request->weight,
        ]);

        if ($item) {
            $a = $request->amount;
            $amount = $p->total_amount + $a;
            $p->update([
                "total_amount" => $amount,
            ]);
            return redirect()->route('main_show_add_item', ['id' => $request->p_id]);
        } else {
            return back()->with('error', 'Item not created, try again');
        }
    }

    public function activate_package($id)
    {
        $p = Package::find($id);

        if ($p) {
            $aa = $p->to_location->charges[0]->amount;
            $package = Package::where('id', '=', $id)->update([
                'status' => 1,
                'total_amount' => $aa,
            ]);

            if ($package) {
                $customer = session('customer');

                $tracking = Tracking::create([
                    'package_id' => $id,
                    'current_location' => $p->from,
                    'a_d' => 2,
                ]);
                Tracking::where('id', '=', $tracking->id)->update([
                    'a_d' => 2
                ]);

                // "Bonjour Mr / Mme " . $tracking->package->name . ", votre commande est maintenant disponible. Vous serez contacté par un agent de liaison GLS.  \nVotre numéro tracking est " . $tracking->package->tracking_id . "\nMerci de suivile tracking de votre colis sur " . url('/') . ". \nRestant à votre disposition.";

                if ($tracking) {

                    $data2 = [
                        'subject' => 'Customer Package Receipt',
                        'email' => $p->customer->email,
                        // 'c_email' => $p->customer->email,
                        'content' => 'Your shipments has been Activated successfully and your tracking number is ' . $tracking->package->tracking_id . '',
                        // 'content' => "Bonjour Mr / Mme " . $tracking->package->name . ", votre commande est maintenant disponible. Vous serez contacté par un agent de liaison GLS.  \nVotre numéro tracking est " . $tracking->package->tracking_id . "\nMerci de suivile tracking de votre colis sur " . url('/') . ". \nRestant à votre disposition.",
                    ];

                    $data = [
                        'subject' => 'Package Receipt',
                        'email' => $p->email,
                        // 'c_email' => $p->customer->email,
                        'content' => "Bonjour Mr / Mme " . $tracking->package->name . ", votre commande est maintenant disponible. Vous serez contacté par un agent de liaison GLS.  \nVotre numéro tracking est " . $tracking->package->tracking_id . "\nMerci de suivile tracking de votre colis sur " . url('/') . ". \nRestant à votre disposition.",
                    ];


                    // sms End
                    $msg = "Bonjour Mr / Mme " . $tracking->package->name . ", votre commande est maintenant disponible. Vous serez contacté par un agent de liaison GLS.  \nVotre numéro tracking est " . $tracking->package->tracking_id . "\nMerci de suivile tracking de votre colis sur " . url('/') . ". \nRestant à votre disposition.";
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

                    // try sending email to customer email
                    try {
                        Mail::send('main.email.c_receipt', $data2, function ($message) use ($data2) {
                            $message->from('info@gls.com', 'GLS');
                            $message->sender('info@gls.com', 'GLS');
                            $message->to($data2['email']);
                            $message->subject($data2['subject']);
                        });
                    } catch (\Throwable $th) {
                        // return back()->with('success', 'Package Has been Activated, Receipt is Not sent to contact Email');
                    }

                    // try sending email to contact email
                    try {
                        Mail::send('main.email.receipt', $data, function ($message) use ($data) {
                            $message->from('info@gls.com', 'GLS');
                            $message->sender('info@gls.com', 'GLS');
                            $message->to($data['email']);
                            $message->subject($data['subject']);
                        });
                    } catch (\Throwable $th) {
                        return back()->with('success', 'Package Has been Activated, Receipt is Not sent to contact Email');
                    }

                    try {
                        Http::get("https://api.sms.to/sms/send?api_key=gHdD8WP3soGaTjDsWTIp9yjgP1egtzIa&bypass_optout=true&to=+" . $to . "&message=" . $msg . "&sender_id=GLS");
                    } catch (\Throwable $th) {

                        return back()->with('success', 'Package Has been Activated, Receipt is sent to contact Email but not Phone');
                    }


                    // // try sending sms to contact phone
                    // try {
                    //     $ebulk->useJSON($from, $ss, $to);
                    // } catch (Throwable $th) {
                    //     return back()->with('success', 'Package Has been Activated, Receipt is Not sent to contact Phone');
                    // }

                    // if Success is on every this
                    return back()->with('success', 'Package Has been Activated, Receipt is sent to contact Email And Phone');
                } else {
                    return back()->with('error', 'Fail to Add Tracker.');
                }
            } else {
                return back()->with('error', 'Package activation failed.');
            }
        } else {
            return back()->with('error', 'You cannot activate Non existing package.');
        }
    }

    public function search_package(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date_range' => ['required'],
            'type' => ['required'],
            'l_type' => ['required'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        // return $request->all();

        $customer = session('customer');

        $d = explode(' - ', $request->date_range);

        $f = explode('/', $d[0]);
        $from = $f[2] . '-' . $f[0] . '-' . $f[1];
        $t = explode('/', $d[1]);
        $to = $t[2] . '-' . $t[0] . '-' . $t[1];

        $type = $request->type;

        $months = array(
            '',
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July ',
            'August',
            'September',
            'October',
            'November',
            'December',
        );


        // if location type is set to All
        if ($request->l_type == 'all') {
            // When All is selected
            if ($request->type == 'all') {
                $packages = Package::where('customer_id', '=', $customer->id)->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])->get();

                if ($packages) {

                    if (count($packages) > 0) {
                        return view('main.package.searched', compact('packages', 'from', 'to', 'months', 'type'));
                    } else {
                        return back()->with('error', 'No Shipments Within selected range or in All. Try Again!');
                    }
                }
            }
            // When Not shipped is selected
            if ($request->type == 'not_shipped') {
                $packages = Package::where(['customer_id' => $customer->id, 'status' => '0'])->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])->get();

                if ($packages) {

                    if (count($packages) > 0) {
                        return view('main.package.searched', compact('packages', 'from', 'to', 'months', 'type'));
                    } else {
                        return back()->with('error', 'No Shipments Within selected range or In Not shipped category. Try Again!');
                    }
                }
            }
            // When Shipped is selected
            if ($request->type == 'shipped') {
                $packages = Package::where(['customer_id' => $customer->id, 'status' => '1'])->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])->get();

                if ($packages) {

                    if (count($packages) > 0) {
                        return view('main.package.searched', compact('packages', 'from', 'to', 'months', 'type'));
                    } else {
                        return back()->with('error', 'No Shipments Within selected range or In shipped category. Try Again!');
                    }
                }
            }
            // When Delivered is selected
            if ($request->type == 'delivered') {
                $packages = Package::where(['customer_id' => $customer->id, 'status' => '2'])->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])->get();

                if ($packages) {

                    if (count($packages) > 0) {
                        return view('main.package.searched', compact('packages', 'from', 'to', 'months', 'type'));
                    } else {
                        return back()->with('error', 'No Shipments Within selected range or In delivered category. Try Again!');
                    }
                }
            }
        } else {    //Else condition for Location_type
            // $location = Location::where('city', '=', $request->l_type)->get();
            // When All is selected
            if ($request->type == 'all') {
                $packages = Package::where(['customer_id' => $customer->id, 'from' => $request->l_type])->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])->get();

                if ($packages) {

                    if (count($packages) > 0) {
                        return view('main.package.searched', compact('packages', 'from', 'to', 'months', 'type'));
                    } else {
                        return back()->with('error', 'No Shipments Within selected range or in All. Try Again!');
                    }
                }
            }

            // When Not shipped is selected
            if ($request->type == 'not_shipped') {
                $packages = Package::where(['customer_id' => $customer->id, 'from' => $request->l_type, 'status' => '0'])->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])->get();

                if ($packages) {

                    if (count($packages) > 0) {
                        return view('main.package.searched', compact('packages', 'from', 'to', 'months', 'type'));
                    } else {
                        return back()->with('error', 'No Shipments Within selected range or In Not shipped category. Try Again!');
                    }
                }
            }
            // When Shipped is selected
            if ($request->type == 'shipped') {
                $packages = Package::where(['customer_id' => $customer->id, 'from' => $request->l_type, 'status' => '1'])->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])->get();

                if ($packages) {

                    if (count($packages) > 0) {
                        return view('main.package.searched', compact('packages', 'from', 'to', 'months', 'type'));
                    } else {
                        return back()->with('error', 'No Shipments Within selected range or In shipped category. Try Again!');
                    }
                }
            }
            // When Delivered is selected
            if ($request->type == 'delivered') {
                $packages = Package::where(['customer_id' => $customer->id, 'from' => $request->l_type, 'status' => '2'])->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])->get();

                if ($packages) {

                    if (count($packages) > 0) {
                        return view('main.package.searched', compact('packages', 'from', 'to', 'months', 'type'));
                    } else {
                        return back()->with('error', 'No Shipments Within selected range or In delivered category. Try Again!');
                    }
                }
            }
        }


        return back()->with('error', 'No Shipments found for this category. Try Again!');
    }


    public function export_summary_in_excel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from' => ['required'],
            'to' => ['required'],
            'type' => ['required'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $customer = session('customer');
        # code...
        return Excel::download(new UsersExport($customer->id, $request->from, $request->to, $request->type), 'packages_' . time() . '.xlsx');
    }
}
