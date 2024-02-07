<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationDetails extends Model
{
    use HasFactory;
    protected $table = 'application_details';

    protected $fillable = [
        'user_id',
        'application_type_id',
        'school_fees_detail_id',
        'happy_birthday_detail_id',
        'loan_detail_id',
        'car_detail_id',
        'founders_day_detail_id',
        'christmas_detail_id',
        'easter_detail_id',
        'emergency_detail_id'
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

    public function EmergencyLoan(){
        return $this->belongsTo(EmergencyLoans::class,'emergency_detail_id','id');
    }
}
