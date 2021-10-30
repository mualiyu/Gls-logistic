<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Package;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class UsersExport implements FromView //FromCollection
{
    public $from;
    public $to;
    public $c_id;
    public $type;

    public function __construct($customer, $from, $to, $type)
    {
        $this->from = $from;
        $this->to = $to;
        $this->c_id = $customer;
        $this->type = $type;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {

        // return Package::all();

        if ($this->type == 'all') {
            $packages = Package::where(['customer_id' => $this->c_id])->whereBetween('created_at', [$this->from . ' 00:00:00', $this->to . ' 23:59:59'])->get();

            if ($packages) {
                return view('main.exports.package_summary', [
                    "packages" => $packages,
                ]);
            }
        }

        if ($this->type == 'not_shipped') {
            $packages = Package::where(['customer_id' => $this->c_id, 'status' => '0'])->whereBetween('created_at', [$this->from . ' 00:00:00', $this->to . ' 23:59:59'])->get();

            if ($packages) {
                return view('main.exports.package_summary', [
                    "packages" => $packages,
                ]);
            }
        }

        if ($this->type == 'shipped') {
            $packages = Package::where(['customer_id' => $this->c_id, 'status' => '1'])->whereBetween('created_at', [$this->from . ' 00:00:00', $this->to . ' 23:59:59'])->get();

            if ($packages) {
                return view('main.exports.package_summary', [
                    "packages" => $packages,
                ]);
            }
        }

        if ($this->type == 'delivered') {
            $packages = Package::where(['customer_id' => $this->c_id, 'status' => '2'])->whereBetween('created_at', [$this->from . ' 00:00:00', $this->to . ' 23:59:59'])->get();

            if ($packages) {
                return view('main.exports.package_summary', [
                    "packages" => $packages,
                ]);
            }
        }


        // $packages = Package::where(['customer_id' => $this->c_id, 'status' => '0'])->whereBetween('created_at', [$this->from . ' 00:00:00', $this->to . ' 23:59:59'])->get();
        // return view('main.exports.package_summary', [
        //     "packages" => $packages,
        // ]);
    }
}
