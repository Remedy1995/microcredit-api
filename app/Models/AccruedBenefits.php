<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccruedBenefits extends Model {
    use HasFactory;

    protected $table = 'accrued_benefits';
    protected $fillable = [
        'employee_code',
         'Principal_amount',
        'interest_rate',
        'interest_amount',
        'sub_total_amount',
    ];
}
