<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TotalLoansProfit extends Model
{
    use HasFactory;
    protected $table = 'total_loans_profit';
    protected $fillable=[
        'total_loans_profit'
    ];
}
