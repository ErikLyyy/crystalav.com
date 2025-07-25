<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'file_path',
        'media_type',
        'user_id'
    ];

    public function mediable()
    {
        return $this->morphTo();
    }

    function User()
    {
        return $this->belongsTo(User::class);
    }
    protected static function booted()
    {
        static::forceDeleted(function ($media) {
            $filePath = public_path($media->getRawOriginal('file_path'));
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        });
    }

}
