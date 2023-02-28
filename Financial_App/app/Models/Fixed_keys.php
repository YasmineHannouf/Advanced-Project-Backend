<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fixed_keys extends Model
{
    use HasFactory;
    protected $fillable= [
        'id',
        'name',
        'is_active'
    ];
     public function FixedCategory()
    {
        return $this->hasMany(FixedModel::class);
    }
}
