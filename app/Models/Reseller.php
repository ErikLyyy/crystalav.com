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
        'image_id',
        'user_id'
    ];


    function User()
    {
        return $this->belongsTo(User::class);
    }
}
