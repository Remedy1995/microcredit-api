<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TotalCumulativeSavings extends Model
{
    use HasFactory;
    protected $table = 'total_cumulative_savings';

    protected $fillable = [
        'total_cumulative_savings'
    ];

}
