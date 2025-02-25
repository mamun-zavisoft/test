<?php

namespace App\Models;

use App\Media\HasMedia;
use App\Media\Mediable;


use Illuminate\Database\Eloquent\Model;

class Product extends Model implements Mediable
{
    use HasMedia;
    
    protected $guarded = [];

    protected $appends = ['thumbnail', 'images'];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }    

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function setThumbnailAttribute($file)
    {
        if ($file) {
            $existingMedia = $this->media()->where('collection_name', 'thumbnail')->first();
            if ($existingMedia) {
                $this->deleteMedia($existingMedia->id);
            }

            $this->addMedia($file, 'thumbnail', []);
        }
    }

    public function getThumbnailAttribute()
    {
        return $this->getFirstUrl('thumbnail');
    }

    public function setImagesAttribute($file)
    {
        if ($file) {

            $this->addMedia($file, 'images', []);
        }
    }

    public function getImagesAttribute()
    {
        return $this->getUrl('images');
    }
}
