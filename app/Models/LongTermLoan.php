<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LongTermLoan extends Model
{
    use HasFactory;

    protected $table = 'long_term';
    protected $fillable = [
        'application_id',
        'application_name',
        'principal_amount',
        'principal_interest',
        'monthly_repayment_amount',
        'number_of_months',
        'effective_date_of_payment',
        'phoneNumber',
        'dob',
        'w_f_no',
        'application_status',
        'approval_status',
        'comment',
        'loan_settlement_status',
        'total_loan_amount_payable',
        'settled_loan_amount',
        'oustanding_loan_balance'
    ];


    public function applicationDetails(){
        return $this->hasMany(ApplicationDetails::class,'long_detail_id','id');
    }

    public function applicationTypes(){
        return $this->belongsTo(ApplicationTypes::class,'application_id','id');
    }


    public function earlySettlement(){
        return $this->belongsTo(EarlySettlement::class,'id');
    }
}
