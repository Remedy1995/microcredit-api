<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyContributions extends Model
{
    use HasFactory;
     protected $table= 'monthly_contributions';
    protected $fillable =[
        'monthly_amount_contribution',
        'employee_code'
    ];
}
