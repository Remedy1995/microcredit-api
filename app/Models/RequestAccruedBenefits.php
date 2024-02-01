<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestAccruedBenefits extends Model
{
    use HasFactory;
    protected $table = 'requests_withdraw_accrued_benefits';
    protected $fillable = [
        'employee_code',
        'amount_to_withdraw',
        'amount_in_words',
        'application_status',
        'approval_status',
        'user_id',
        'comment'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
