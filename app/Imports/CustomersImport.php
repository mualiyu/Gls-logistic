<?php

namespace App\Imports;

use App\Models\Customer;
use App\Models\Location;
use App\Models\Charge;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomersImport implements ToModel, WithHeadingRow
{
    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {

        $location = Location::create([
            'region' => "Littoral",
            'city' => $row['city'],
            'zone' => "a",
            'location' => $row['location'],
            'type' => 2,
        ]);
        Location::where('id', '=',  $location->id)->update([
            'type' => 2,
        ]);

        $charge = Charge::create([
            'location_id' => $location->id,
            'amount' => 1000,
        ]);

        // if ($charge) {
        //     return redirect()->route('admin_show_location', ['id' => $location->id])->with('success', 'Location created successfully');
        // } else {
        //     $location->destroy();
        //     return back()->withInput()->with('error', 'Failed to create Location, try again');
        // }

        return $location;
    }

    public function headingRow(): int
    {
        return 1;
    }
}
