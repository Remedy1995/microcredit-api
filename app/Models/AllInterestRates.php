<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllInterestRates extends Model
{
    use HasFactory;

    protected $table = 'all_interest_rates';

    protected $fillable =[
        'application_type_name',
        'interest_duration',
        'interest_rates'
    ];
}
