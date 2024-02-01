<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EarlySettlement extends Model
{
    use HasFactory;
    protected $table = 'early_settlement_form';
    protected $fillable = [
        'user_id',
        'school_fees_detail_id',
        'happy_birthday_detail_id',
        'loan_detail_id',
        'founders_day_detail_id',
        'christmas_detail_id',
        'car_detail_id',
        'easter_detail_id',
        'type_of_loan_taken'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function HappyBirthdayLoan()
    {
        return $this->belongsTo(HappyBirthdayLoan::class, 'happy_birthday_detail_id', 'id');
    }

    public function LoanApplication()
    {
        return $this->belongsTo(LoanApplication::class, 'loan_detail_id', 'id');
    }


    public function SchoolFeesLoan()
    {
        return $this->belongsTo(SchoolFeesLoan::class, 'school_fees_detail_id', 'id');
    }


    public function CarLoan()
    {
        return $this->belongsTo(CarLoans::class, 'car_detail_id', 'id');
    }

    public function FoundersDayLoan()
    {
        return $this->belongsTo(FoundersDayLoan::class, 'founders_day_detail_id', 'id');
    }
    public function ChristmasLoan()
    {
        return $this->belongsTo(ChristmasLoan::class, 'christmas_detail_id', 'id');
    }
    public function EasterLoan()
    {
        return $this->belongsTo(EasterLoans::class, 'easter_detail_id', 'id');
    }
}
