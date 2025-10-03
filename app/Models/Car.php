<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = ['name', 'model', 'year', 'color'];
    // --- IGNORE ---

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
