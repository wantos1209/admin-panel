<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PengirimanDetailExport implements FromView
{
    protected $details;

    public function __construct($details)
    {
        $this->details = $details;
    }

    public function view(): View
    {
        return view('exports.pengiriman_detail', [
            'details' => $this->details
        ]);
    }
}
