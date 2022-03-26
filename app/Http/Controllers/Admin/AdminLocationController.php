<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\CustomersImport;
use App\Models\Charge;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class AdminLocationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $locations = Location::orderBy('created_at', 'desc')->get();

        return view('admin.location.index', compact('locations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.location.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'region' => ['required'],
            'city' => ['required'],
            'zone' => ['nullable'],
            'location' => ['required'],
            'amount' => ['required', 'integer'],
            'desig' => ['required'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $location = Location::create([
            'region' => $request->region,
            'city' => $request->city,
            'zone' => $request->zone,
            'location' => $request->location,
            'type' => $request->desig,
        ]);
        Location::where('id', '=',  $location->id)->update([
            'type' => $request->desig,
        ]);

        if ($location) {
            $charge = Charge::create([
                'location_id' => $location->id,
                'amount' => $request->amount,
            ]);

            if ($charge) {
                return redirect()->route('admin_show_location', ['id' => $location->id])->with('success', 'Location created successfully');
            } else {
                $location->destroy();
                return back()->withInput()->with('error', 'Failed to create Location, try again');
            }
        } else {
            $location->destroy();
            return back()->withInput()->with('error', 'Failed to create Location, try again.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $location = Location::find($id);

        return view('admin.location.info', compact('location'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'region' => ['required'],
            'city' => ['required'],
            'zone' => ['nullable'],
            'location' => ['required'],
            'desig' => ['required'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $location = Location::where('id', '=', $id)->update([
            'region' => $request->region,
            'city' => $request->city,
            // 'zone' => $request->zone,
            'location' => $request->location,
            'type' => $request->desig
        ]);
        // Location::where('id', '=', $location->id)->update([
        //     'type' => $request->desig,
        // ]);


        if ($location) {
            return back()->with('success', 'Location has been updated');
        } else {
            return back()->with('error', 'Location not updated. Try again.');
        }
    }

    public function update_charge(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'amount' => ['required', 'integer'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $charge = Charge::where('id', '=', $id)->update([
            'amount' => $request->amount,
        ]);

        if ($charge) {
            return back()->with('success', 'Location has been updated');
        } else {
            return back()->with('error', 'Location not updated. Try again.');
        }
    }


    public function import_locations(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "file" => "required",
        ]);

        if ($validator->fails()) {
            return back()->with(['error' => 'Make sure you upload a csv file'])->withInput();
        }

        $id = $request->agent;
        Excel::import(new CustomersImport($id), $request->file('file'));

        return back()->with(['success' => "Customers uploaded Successful"]);
    }

    public function destroy($id)
    {
        $m = Location::where('id', '=', $id)->delete();

        if ($m) {
            return back()->with('success', 'One location has been deleted');
        } else {
            return back()->with('error', 'Failed to delete location, Try again.');
        }
    }
}
