<?php

namespace App\Imports;

use App\Modules\Customers\Models\Suspect;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithStartRow;

class TwoColumnExcelImport implements ToArray, WithStartRow
{
    public function startRow(): int
    {
        return 2;
    }

    public function array(Array $rows)
    {
        foreach ($rows as $row) {
            $splited = explode(" ", trim($row[0]));
            $arr[] = ['-', '-', '-', '-', '-', '-', '-', '-'];
            for ($i = 0; $i < 8; $i++) {
                if ($i < sizeof($splited)) {
                    $arr[$i] = $splited[$i];
                } else {
                    $arr[$i] = '';
                }
            }
            $concatenated_names = $arr[0] . $arr[1] . $arr[2] . $arr[3];
            $concatenated_names = preg_replace('/[^А-Яа-я0-9]/', '', $concatenated_names);
            $concatenated_names = mb_strtolower($concatenated_names);
            $suspect = new Suspect([
                'concatenated_names' => $concatenated_names,
                'second_name' => $arr[0],
                'first_name' => $arr[1],
                'third_name' => $arr[2],
                'fourth_name' => $arr[3] . ' ' . $arr[4] . ' ' . $arr[5] . ' ' . $arr[6] . ' ' . $arr[7],
                'organization' => 'IP',
                'birth_date' => Carbon::parse($row[1]),
                //'other'  => $row[7],
            ]);
            $suspect->save();
            $arr[3] = '';
            $arr[4] = '';
            $arr[5] = '';
            $arr[6] = '';
            $arr[7] = '';
        }
    }

}
