<?php
namespace App\Traits;

use App\Models\Media;

trait HasMedia
{
    public static function bootHasMedia()
    {
        static::forceDeleted(function ($model) {
            if ($model->media()->withTrashed()->count() > 0) {
                foreach ($model->media()->withTrashed()->get() as $media) {
                    $media->forceDelete();
                }
            }
        });
    }
    public function media()
    {
        return $this->morphMany(Media::class, 'mediable')->withTrashed();
    }
}
