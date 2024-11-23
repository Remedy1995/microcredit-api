<?php


namespace App\Utilities;

use App\Models\TotalCumulativeSavings;
use App\Models\TotalLoansProfit;
use Carbon\Carbon;
use Exception;

class RecordTransactions
{

    /**
    //  * @param float $principalAmount
    //  * @param float $interestRate
    //  * @param float $interestAmount
    //  * @param float $loans_from_profit
    //  * @param float $total_cumulative_savings
    //  * @param  float $totalInterestShared
    //  * @param float $individualSavings
    //  *  @return float
     *
     *
     */

    public static function CheckPaidContributions($checkMonths)
    {
        $currentDate = Carbon::now()->toDateString();
        $splitCurrentMonth = explode("-", $currentDate)[1];
        $splitCurrentYear = explode("-", $currentDate)[0];
        $currentMonthYear = [];
        $currentMonthYear[] = $splitCurrentMonth;
        $currentMonthYear[] = $splitCurrentYear;
        $joinCurrentMonthYear = implode("-", $currentMonthYear);
        //return $joinCurrentMonthYear;
        //simulate new monthYear
        //$joinCurrentMonthYear = '12-2025';
        $allPaidDates = [];
        foreach ($checkMonths as $months) {
            $parseMonth = Carbon::parse($months->created_at)->month;
            $parseYear = Carbon::parse($months->created_at)->year;
            $finalMonth = str_pad($parseMonth, 2, '0', STR_PAD_LEFT);
            $allPaidDates[] = $finalMonth . '-' . $parseYear;
        }
        $checkPaidDates = in_array($joinCurrentMonthYear, $allPaidDates);
        if ($checkPaidDates) {
            return 1;
        } else {
            return 0;
        }
    }



    public static function CheckDates($checkMonths)
    {
        $currentDate = Carbon::now()->toDateString();
        $splitCurrentMonth = explode("-", $currentDate)[1];
        $splitCurrentYear = explode("-", $currentDate)[0];
        $currentMonthYear = [];
        $currentMonthYear[] = $splitCurrentMonth;
        $currentMonthYear[] = $splitCurrentYear;
        $joinCurrentMonthYear = implode("-", $currentMonthYear);
        //simulate new monthYear
        //$joinCurrentMonthYear = '12-2025';
        $allLoanDates = [];
        foreach ($checkMonths as $months) {
            $parseMonth = Carbon::parse($months->created_at)->month;
            $parseYear = Carbon::parse($months->created_at)->year;
            $allLoanDates[] = $parseMonth . '-' . $parseYear;
        }
        $checkDatesExist = in_array($joinCurrentMonthYear, $allLoanDates);

        if ($checkDatesExist) {
            return 1;
        } else {
            return 0;
        }
    }

    public static function FormatDate()
    {
        //this function returns an array of date in the format month/year eg 2020-10
        $currentDate = Carbon::now()->toDateString();
        $splitCurrentMonth = explode("-", $currentDate)[1];
        $splitCurrentYear = explode("-", $currentDate)[0];
        $currentMonthYear = [];
        $currentMonthYear[] = $splitCurrentYear;
        $currentMonthYear[] = $splitCurrentMonth;
        //simulate monthYear
        // $currentMonthYear[0] = '2024';
        // $currentMonthYear[1] = '1';
        return $currentMonthYear;
        //return an array containing the year as the first item and month as second item
    }


    public  static function FormatExcelData($data)
    {
        set_time_limit(300);
        $data = array_filter($data, function ($row) {
            return !empty(array_filter($row, function ($cell) {
                return !is_null($cell) && is_numeric($cell);
            }));
        });

        // Check if the last row contains a null value
        $lastRow = end($data);
        if (in_array('Akatua by S.O.F.T.', $lastRow)) {
            // Remove the last row if it contains a null value
            array_pop($data);
        }
        array_pop($data);
        //pop the data again
        // Extract columns "employee_code" and "monthly_amount_contribution"
        $processedData = array_map(function ($row) {
            return [
                'employee_code' => $row['employee_code'] ?? null,
                // 'Principal_amount' => $row[2],
                'monthly_amount_contribution' => $row['amount'] ?? null
            ];
        }, $data);
        // Remove the first row (headers)
        // $headers = array_shift($processedData);
        // Output the processed data
        return $processedData;
        //return json_encode($processedData, JSON_PRETTY_PRINT);
    }




    public  static function FormatExcelLoanData($data)
    {

        // $data = array_filter($data, function ($row) {
        //     return !empty(array_filter($row, function ($cell) {
        //         return !is_null($cell) && is_numeric($cell);
        //     }));
        // });

        // Check if the last row contains a null value
        //  $lastRow = end($data);

        //   if (in_array('Total :', $lastRow)) {
        // Remove the last row if it contains a null value
        //    array_pop($data);
        //   }

        // Extract columns "employee_code" and "monthly_amount_contribution"
          try {
        $processedData = array_map(function ($row) {
            if (
                !is_null(isset($row['employee_code']) && $row['employee_code'])
                && !is_null(isset($row['principal']) && $row['principal'])
                && !is_null(isset($row['monthly_repayment'])) && ($row['monthly_repayment'])
            ) {
                return [
                    'employee_code' => $row['employee_code'],
                    'Principal_amount' => $row['principal'],
                    'monthly_repayment_amount' => $row['monthly_repayment'],
                ];

            }
        }, $data);

        $processedData = array_filter($processedData, function ($row) {
            if (!empty($row)) {
                return $row;
            }
        });
        // Remove the first row (headers)
        // $headers = array_shift($processedData);
        // Output the processed data
        return json_encode($processedData, JSON_PRETTY_PRINT);
    }
    catch(Exception $exception){
        $data =  [
            'employee_code' => null,
            'Principal_amount' => null,
            'monthly_repayment_amount' =>null,
        ];
    }
    return json_encode($data,JSON_PRETTY_PRINT);
    }


    //this function checks if there is a duplicates in the excel sheet data
    public static function CheckDuplicates($employeeCodeArray, $employeeObject)
    {
        foreach ($employeeCodeArray as $key => $value) {
            if (!isset($employeeObject[$value])) {
                $employeeObject[$value] = 1;
            } else {
                $employeeObject[$value]++;
            }
        }
        //now loop thorugh employee object and return the employee who appears more than 1
        //that is has been assigned 2 or more
        foreach ($employeeObject as $key => $value) {
            if ($value > 1) {
                return $key;
            }
        }

        return null;
    }
}
