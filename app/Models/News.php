<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = 'news';
    use HasFactory;

    protected $fillable = [
        'postTitle',
        'postDescription'
    ];
}
