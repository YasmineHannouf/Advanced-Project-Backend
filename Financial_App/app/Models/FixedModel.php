<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FixedModel extends Model
{
    use HasFactory;
 protected $table = 'fixes';

    protected $fillable = [
        'title',
        'description',
        'amount',
        'date_time',
        'category_id',
        'key_id',
        'is_paid',
        'type',
        'scheduled_date',
    ];  

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function fixedkey()
    {
        return $this->belongsTo(Fixedkey::class,'key_id');
    }

}