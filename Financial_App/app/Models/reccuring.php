<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class reccuringModel extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $fillable = [
        'title',
        'description',
        'amount',
        'date_time',
        'category_id',
        'start_date',
        'end_date',
        'type',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }


}
