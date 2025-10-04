<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sidebar extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'title',
        'slug',
        'type',
        'category_id',
        'parent_id',
        'user_id'
    ];


    function user()
    {
        return $this->belongsTo(User::class);
    }
    function category()
    {
        return $this->belongsTo(Category::class)->withTrashed();
    }
    function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
