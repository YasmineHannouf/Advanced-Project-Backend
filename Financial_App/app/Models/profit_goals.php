<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class profit_goals extends Model
{
    protected $fillable = [
        'title',
        'goal',
        'start_date',
        'end_date',
    ];
    use HasFactory;
}
