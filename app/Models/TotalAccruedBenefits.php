<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TotalAccruedBenefits extends Model
{
    use HasFactory;
    protected $table = 'total_accrued_benefits';

    protected $fillable =[
        'employee_code',
        'total_accrued_benefits_amount'
    ];
}
