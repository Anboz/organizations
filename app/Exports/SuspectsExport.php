<?php

namespace App\Exports;

use App\Modules\Customers\Models\SuspiciousCustomers;
use Maatwebsite\Excel\Concerns\FromCollection;

class SuspectsExport implements FromCollection
{

    public function collection()
    {
        return SuspiciousCustomers::all();
    }
}
