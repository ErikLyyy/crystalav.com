<?php

namespace App\Models;

use App\Traits\HasMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes, HasMedia;
    protected $fillable = [
        'name',
        'short_desc',
        'detail',
        'slug',
        'warehouse_status',
        'privacy',
        'category_id',
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
    function keysearches()
    {
        return $this->belongsToMany(Keysearch::class);
    }
    function sidebars()
    {
        return $this->belongsToMany(Sidebar::class);
    }
}
