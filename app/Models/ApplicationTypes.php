<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationTypes extends Model
{
    use HasFactory;

    protected $table = 'application_types';


    protected $fillable = [
        'id',
        'application_type_name',
        'application_type_slug',
        'application_category'
    ];

  
  

  
}
