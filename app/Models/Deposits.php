<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposits extends Model
{
    use HasFactory;

    protected $table = 'deposit_form';

    protected $fillable = [
        'paymentAmount',
        'reciept_url',
        'paymentType',
        'user_id',
        'application_status',
        'approval_status',
        'employee_code',
        'comment',
         'reciept_number'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
