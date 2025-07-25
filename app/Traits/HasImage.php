<?php
namespace App\Traits;

use App\Models\Media;

trait HasImage
{
    public static function bootHasImage()
    {
        // Only delete image when force delete
        static::forceDeleted(function ($model) {
            if ($model->image) {
                $model->image->forceDelete();
            }
        });
    }

    public function image()
    {
        return $this->morphOne(Media::class, 'mediable')->withTrashed();
    }
}
