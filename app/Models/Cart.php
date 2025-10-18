<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'qty',
        'thumbnail',
        'url',
        'product_id',
        'request_id'
    ];
    function Product()
    {
        return $this->belongsTo(Product::class);
    }

}
