<?php

namespace App\Imports;

use App\Modules\Customers\Models\Suspect;
use Exception;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ExcelImport implements ToModel, WithStartRow
{
    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        $concatenated_names = $row[1] . $row[2] . $row[3];
        $concatenated_names = preg_replace('/[^А-Яа-я0-9]/', '', $concatenated_names);
        $concatenated_names = mb_strtolower($concatenated_names);

        try {
            $date = (Date::excelToDateTimeObject($row[4]))->format('Y-m-d');
        } catch (Exception $e) {

        }

        $suspect = new Suspect([
            'concatenated_names' => ($concatenated_names),
            'second_name' => $row[1],
            'first_name' => $row[2],
            'third_name' => $row[3],
            //  'fourth_name' => $row[4],
            'organization' => 'MIA',
            'birth_date' => $date,
            // 'other'  => $row[7],
        ]);
        return $suspect;
    }
}
