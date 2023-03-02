<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fixedkey extends Model
{    protected $table = 'fixedKeys';

    use HasFactory;
    protected $fillable= [
        'id',
        'name',
        'is_active'
    ];
     public function fixedCategory()
    {
        return $this->hasMany(FixedModel::class);
    }
}
