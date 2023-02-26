<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'amount',
        'currency',
        'date_time',
        'category_id',
        'is_recurring',
        'start_date',
        'end_date',
    ];

}