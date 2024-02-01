<?php


namespace App\Utilities;

use App\Models\TotalCumulativeSavings;
use App\Models\TotalLoansProfit;
use Carbon\Carbon;

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
        //simulate new monthYear
        //$joinCurrentMonthYear = '12-2025';
        $allPaidDates = [];
        foreach ($checkMonths as $months) {
            $parseMonth = Carbon::parse($months->created_at)->month;
            $parseYear = Carbon::parse($months->created_at)->year;
           $finalMonth = strlen($parseMonth < 2) ? '0' .$parseMonth : $parseMonth;
            $allPaidDates[] = $finalMonth . '-' . $parseYear;
        }
        $checkPaidDates = in_array($joinCurrentMonthYear, $allPaidDates);

        if ($checkPaidDates) {
            return 0;
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

     public static function FormatDate(){
        //this function returns an array of date in the format month/year eg 2020-10
        $currentDate = Carbon::now()->toDateString();
        $splitCurrentMonth = explode("-", $currentDate)[1];
        $splitCurrentYear = explode("-", $currentDate)[0];
        $currentMonthYear = [];
        $currentMonthYear[] = $splitCurrentYear;
        $currentMonthYear[] = $splitCurrentMonth;
        //simulate monthYear
        //  $currentMonthYear[0]='2024';
        //  $currentMonthYear[1]='2';
        return $currentMonthYear;
        //return an array containing the year as the first item and month as second item
     }

}
