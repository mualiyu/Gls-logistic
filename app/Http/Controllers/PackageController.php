<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Journey;
use App\Models\Package;
use App\Models\Region;
use App\Models\Tracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class PackageController extends Controller
{

    public function index()
    {
        $customer = session('customer');

        $packages = Package::where('customer_id', '=', $customer->id)->get();

        return view('main.package.index', compact('packages'));
    }

    public function show()
    {
        return view('main.package.add');
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from' => ['required'],
            'to' => ['required'],
            'from_address' => ['required'],
            'to_address' => ['required'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // check if Customer login session is active
        if (session()->has('customer')) {
            $customer = session('customer');
            $tracking_id = rand(100000000000, 999999999999);

            $package = Package::create([
                'customer_id' => $customer->id,
                'from' => $request->from,
                'to' => $request->to,
                'address_from' => $request->from_address,
                'address_to' => $request->to_address,
                'tracking_id' => $tracking_id,
                'adjusted_amount' => 0,
                'total_amount' => 100000,
                'status' => 0,
            ]);
            if ($package) {
                return redirect()->route('main_show_add_item', ['id' => $package->id]);
            } else {
                return back()->with('error', 'Package not created, try again');
            }
        } else {
            return redirect()->route('main_signup');
        }

        return $request->all();
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
                    $output .= '<option value="' . $row->region->code . '">' . $row->region->state . '</option>';
                    $i++;
                }

                $output .= '';
            } else {

                $output .= '<option value="">No arrival region found in system</option>';
            }

            return $output;
        }
    }

    public function show_add_item($id)
    {
        $package = Package::find($id);
        $items = Item::where('package_id', '=', $id)->get();

        return view('main.package.add_item', compact('package', 'items'));
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
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

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
            return redirect()->route('main_show_add_item', ['id' => $request->p_id]);
        } else {
            return back()->with('error', 'Item not created, try again');
        }
    }

    public function activate_package($id)
    {
        $p = Package::find($id);

        if (count($p->items) > 0) {
            $package = Package::where('id', '=', $id)->update([
                'status' => 1
            ]);

            if ($package) {
                $r = Region::where('code', '=', $p->from)->get();
                $customer = session('customer');
                $tracking = Tracking::create([
                    'package_id' => $id,
                    'current_location' => $r[0]->capital,
                ]);
                if ($tracking) {
                    $data = [
                        'subject' => 'Package Receipt',
                        'email' => $customer->email,
                        'content' => 'Your Package has been Activated successfully \n And your tracking number is ' . $tracking->package->tracking_id . '',
                    ];

                    Mail::send('main.email.receipt', $data, function ($message) use ($data) {
                        $message->from('info@gls.com', 'GLS');
                        $message->sender('info@gls.com', 'GLS');
                        $message->to($data['email']);
                        $message->subject($data['subject']);
                    });

                    return back()->with('success', 'Package Has been Activated, Receipt is sent to your Email');
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
