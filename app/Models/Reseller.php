<?php

namespace App\Models;

use App\Traits\HasImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reseller extends Model
{
    use HasFactory, SoftDeletes, HasImage;
    protected $fillable = [
        'url',
        'user_id'
    ];


    function user()
    {
        return $this->belongsTo(User::class);
    }
}
