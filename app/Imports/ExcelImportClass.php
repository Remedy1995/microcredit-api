<?php

// app/Imports/ExcelImportClass.php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ExcelImportClass implements ToCollection, WithHeadingRow {
    public function collection(Collection $rows) {

        $employeeData = [];

        foreach($rows as $row) {
            $employeeCode = $row['employee_code'] ?? null;
            $employeeAmount = $row['monthly_amount_contribution'] ?? null;

            if($employeeCode && $employeeAmount !== null) {
                $employeeData[] = $employeeCode;
                $employeeData[] = $employeeAmount;

            }
        }

        return $employeeData;
    }
}
