<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refunds extends Model
{
    use HasFactory;
    protected $table = 'refunds';
    protected $fillable = [
        'type_of_loan_refunds',
        'employee_code',
        'refund_amount',
        'application_id',
         'refund_status',
         'user_id'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
