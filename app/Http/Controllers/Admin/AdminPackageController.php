<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Region;
use App\Models\Tracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
                        $status = "<span class='btn btn-warning'>Delivered</span>";
                    }

                    // output
                    $output .= '<tr> <td>' . $i . '</td> <td>' . $row->tracking_id .
                        '</td> <td>' . $items_s . '</td><td>' . $row->total_amount / 100 .
                        '</td> <td>' . $status .
                        '</td> <td> <a href="" class="btn btn-primary">Open Package</a> </td> </tr>';

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
            $package = Package::where('id', '=', $id)->update([
                'status' => 1
            ]);

            if ($package) {
                // $r = Region::where('code', '=', $p->from)->get();
                $customer = $p->customer;
                $tracking = Tracking::create([
                    'package_id' => $id,
                    'current_location' => $p->from,
                    'a_d' => 1,
                ]);
                if ($tracking) {
                    $data = [
                        'subject' => 'Package Receipt',
                        'email' => $customer->email,
                        'content' => 'Your shipments has been Activated successfully and your tracking number is ' . $tracking->package->tracking_id . '',
                    ];

                    try {
                        Mail::send('main.email.receipt', $data, function ($message) use ($data) {
                            $message->from('info@gls.com', 'GLS');
                            $message->sender('info@gls.com', 'GLS');
                            $message->to($data['email']);
                            $message->subject($data['subject']);
                        });
                    } catch (\Throwable $th) {
                        return back()->with('success', 'Package Has been Activated, But receipt is not sent to customers email');
                    }

                    return back()->with('success', 'Package Has been Activated, Receipt is sent to Email');
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
