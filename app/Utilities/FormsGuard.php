<?php


namespace App\Utilities;

use App\Models\ApplicationDetails;


class FormsGuard
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

    public static function CheckExistingApplicationInProgress($user_id, $appname)
    {
        $userapplications = ApplicationDetails::where(['user_id' => $user_id])->with([
            'happybirthdayloan' => function ($query) use ($appname) {
                $query->where('application_status', 'IN-PROGRESS')
                    ->where('application_name', $appname);
            },
            'schoolfeesloan' => function ($query) use ($appname) {
                $query->where('application_status', 'IN-PROGRESS')
                    ->where('application_name', $appname);
            },
            'loanapplication' => function ($query)  use ($appname) {
                $query->where('application_status', 'IN-PROGRESS')
                    ->where('application_name', $appname);
            },
            'carloan'  => function ($query) use ($appname) {
                $query->where('application_status', 'IN-PROGRESS')
                    ->where('application_name', $appname);
            },
            'foundersdayloan' => function ($query) {
                $query->where('application_status', 'IN-PROGRESS');
            },
            'christmasloan' => function ($query) use ($appname) {
                $query->where('application_status', 'IN-PROGRESS')
                    ->where('application_name', $appname);;
            },
            'easterloan' => function ($query) use ($appname) {
                $query->where('application_status', 'IN-PROGRESS')
                    ->where('application_name', $appname);;
            },
            'emergencyloan' => function ($query) use ($appname) {
                $query->where('application_status', 'IN-PROGRESS')
                    ->where('application_name', $appname);;
            },
            'otherloan' => function ($query) use ($appname) {
                $query->where('application_status', 'IN-PROGRESS')
                    ->where('application_name', $appname);
            },
            'longtermloan' => function ($query) use ($appname) {
                $query->where('application_status', 'IN-PROGRESS')
                    ->where('application_name', $appname);
            },
        ])->get();

        $trimApplications = $userapplications->filter(function ($application) use ($appname) {
            return $application->longtermloan !== null && $application->longtermloan->application_name === $appname
                || $application->schoolfeesloan !== null  && $application->schoolfeesloan->application_name === $appname
                || $application->happybirthdayloan !== null  && $application->happybirthdayloan->application_name === $appname
                || $application->loanapplication !== null && $application->loanapplication->application_name === $appname
                || $application->carloan !== null  && $application->carloan->application_name === $appname
                || $application->foundersdayloan !== null  && $application->foundersdayloan->application_name === $appname
                || $application->christmasloan !== null  && $application->christmasloan->application_name === $appname
                || $application->easterloan !== null  && $application->easterloan->application_name === $appname
                || $application->emergencyloan !== null && $application->emergencyloan->application_name === $appname
                || $application->otherloan !== null && $application->otherloan->application_name === $appname;
        });
        if ($trimApplications->isNotEmpty()) {
            return true;
        }
        return false;
    }
}
