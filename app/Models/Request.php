<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Request extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'first_name',
        'last_name',
        'company_name',
        'phone_number',
        'email',
        'approximate_date',
        'approximate_return',
        'message'
    ];
    function Cart()
    {
        return $this->hasMany(Cart::class, 'request_id');
    }
}
