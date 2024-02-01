<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChangeInMemberContributions extends Model
{
    use HasFactory;

    protected $table = 'change_in_member_contributions';
    protected $fillable = [
        'user_id',
        'application_status',
        'approval_status',
        'monthly_amount_contribution',
        'effective_date_of_contribution',
        'employee_code',
        'comment'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}



