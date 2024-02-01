<?php


namespace App\Utilities;

use App\Models\TotalCumulativeSavings;
use App\Models\TotalLoansProfit;
use Carbon\Carbon;

class AccruedInterests
{

    /**
     * @param float $principalAmount
     * @param float $interestRate
     * @param float $interestAmount
     * @param float $loans_from_profit
     * @param float $total_cumulative_savings
     * @param  float $totalInterestShared
     * @param float $individualSavings
     *  @return float
     *
     *
     */

    // public static function CalculateInterest($principalAmount, $interestRate)
    // {
    //     return $principalAmount * $interestRate / 100;
    // }

    public static function CalculateInterest($interestFromloans, $individualSavings, $totalContributions)
    {
        return (($interestFromloans / $totalContributions) * $individualSavings);
    }

    public static function UserTotalAccruedBenefits($interestAmount, $totalContributions)
    {
        return ($interestAmount + $totalContributions);
    }

    public static function CalculateSubTotal($principalAmount, $interestAmount)
    {
        return $principalAmount + $interestAmount;
    }

    public static function InterestShared($loans_from_profit, $total_cumulative_savings)
    {
        return ($loans_from_profit / $total_cumulative_savings);
    }

    public static function InterestCapitalised($totalInterestShared, $individualSavings)
    {
        return $totalInterestShared * $individualSavings;
    }

    public static function TotalAccumulation($totalBalance)
    {
        //create an initial record

        $findRecord = TotalCumulativeSavings::find(1);
        return $findRecord;
        if ($findRecord) {
            $findRecord->total_cumulative_savings += $totalBalance;
            $findRecord->save();
        } else {
            TotalCumulativeSavings::create([
                'id' => 1,
                'total_cumulative_savings' => $totalBalance
            ]);
        }
    }


    public static function TotalProfitLoans($totalloansProfit)
    {
        //check total loans profit if same month update initial loans profit else if new month create a new record
        $formatCurrentDate = RecordTransactions::FormatDate(); //returns current date array

        $recordTotalProfitLoans = TotalLoansProfit::whereYear('created_at', '=', $formatCurrentDate[0])
            ->whereMonth('created_at', '=', $formatCurrentDate[1])->first();
        //return $recordTotalProfitLoans;
        if (!$recordTotalProfitLoans) {
            //create a new record for that particlar month
            TotalLoansProfit::create([
                'id' => 1,
                'total_loans_profit' => $totalloansProfit
            ]);
        } else {
            //update
            $recordTotalProfitLoans->total_loans_profit += $totalloansProfit;
            $recordTotalProfitLoans->save();
        }
    }
}
