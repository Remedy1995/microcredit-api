<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanRepayments extends Model
{
    use HasFactory;
    protected $table = 'loan_repayments';
    protected $fillable =[
        'monthly_repayment_amount',
        'employee_code',
        'Principal_amount',
         'type_of_loan_taken',
         'loan_payment_type'
    ];
}
