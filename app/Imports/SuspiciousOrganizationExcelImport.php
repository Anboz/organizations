<?php

namespace App\Imports;

use App\Modules\Customers\Models\SuspiciousOrganizations;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithStartRow;

class SuspiciousOrganizationExcelImport implements ToArray, WithStartRow
{
    public function startRow(): int
    {
        return 3;
    }
    public function array(Array  $rows)
    {
        foreach ($rows as $row)
        {
            foreach($row as $r){
                if(!empty($r) && preg_match('/[a-zA-Z]/', $r)) {
                    $concatenated_name = preg_replace('/[^A-Za-z]/', '', $r);
                    $concatenated_name = mb_strtolower($concatenated_name);
                    $suspiciousOrganizations = new SuspiciousOrganizations([
                            'concatenated_name' => $concatenated_name,
                            'organization_name' => $r,
                            'other' => 'Suspicious Organizations LIST OF OUR NATIONAL BANK',
                        ]);
                        $suspiciousOrganizations->save();
                    }
                    if(!empty($r) && !preg_match('/[a-zA-Z]/', $r)) {
                        $concatenated_name = mb_ereg_replace('[^А-Яа-я]', '', $r);
                        $concatenated_name = mb_strtolower($concatenated_name);
                        $suspiciousOrganizations = new SuspiciousOrganizations([
                            'concatenated_name' => $concatenated_name,
                            'organization_name' => $r,
                            'other' => 'Suspicious Organizations LIST OF OUR NATIONAL BANK',
                        ]);
                        $suspiciousOrganizations->save();
                    }
                }
        }
    }

}
