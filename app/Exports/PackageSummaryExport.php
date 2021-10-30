<?php

namespace App\Exports;

use App\Models\Package;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;

class PackageSummaryExport implements FromQuery
{
    use Exportable;

    public $from;
    public $to;
    public $c_id;

    public function __construct($customer, $from, $to)
    {
        $this->from = $from;
        $this->to = $to;
        $this->c_id = $customer;
    }

    public function query()
    {
        return Package::all();
        // return Package::where(['customer_id' => $this->c_id])->whereBetween('created_at', [$this->from . ' 00:00:00', $this->to . ' 23:59:59'])->get();
    }
}
