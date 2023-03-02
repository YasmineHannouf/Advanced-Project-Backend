<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Category extends Model
{
    use HasFactory;

    protected $fillable= [
        'id',
        'name',
    ];
    public function ReccuringCategory()
    {
        // return $this->hasMany(RecurringModel::class);
    }    public function FixedCategory()
    {
        return $this->hasMany(FixedModel::class);
    }
}
