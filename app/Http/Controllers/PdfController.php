<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PDF;


class PdfController extends Controller
{
    public function __construct()
    {
        $this->middleware('customerAuth');
    }

    public function main_shipments_search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from' => ['required'],
            'to' => ['required'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $customer = session('customer');

        // $d = explode(' - ', $request->date_range);

        // $f = explode('/', $d[0]);
        // $from = $f[2] . '-' . $f[0] . '-' . $f[1];
        // $t = explode('/', $d[1]);
        // $to = $t[2] . '-' . $t[0] . '-' . $t[1];
        // // return $from;

        $from = $request->from;
        $to = $request->to;

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

        // return $request->from;

        $packages = Package::where('customer_id', '=', $customer->id)->whereBetween('created_at', [$request->from . ' 00:00:00', $request->to . ' 23:59:59'])->get();

        if ($packages) {

            if (count($packages) > 0) {
                $pdf = PDF::loadView('main.pdf.search_package', compact('packages', 'months', 'from', 'to'))->setPaper('a4');

                return $pdf->stream('main.pdf.search_package');
                // return view('main.package.searched', compact('packages', 'from', 'to', 'months'));
            } else {
                return back()->with('error', 'No Shipments Within selected range. Try Again!');
            }
        }
    }
}
