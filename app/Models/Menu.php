<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'title',
        'desc',
        'slug',
        'layout',
        'parent_id',
        'user_id'
    ];


    function user()
    {
        return $this->belongsTo(User::class);
    }
    function categories()
    {
        return $this->hasMany(Category::class);
    }
}
