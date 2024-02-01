<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrentInterestRates extends Model
{
    use HasFactory;
    protected $table ='current_interest_rates';

    protected $fillable =[
        'application_type_name',
        'interest_duration',
        'interest_rates'
    ];
}
