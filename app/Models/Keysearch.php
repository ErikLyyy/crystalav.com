<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keysearch extends Model
{
    protected $fillable = [
        'keyword',
        'search_count',
    ];
    function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
