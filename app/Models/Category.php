<?php

namespace App\Models;

use App\Media\HasMedia;
use App\Media\Mediable;

use Illuminate\Database\Eloquent\Model;

class Category extends Model implements Mediable
{
    use HasMedia;

    protected $guarded = [];

    protected $appends = ['image'];

    public function setImageAttribute($file)
    {
        if ($file) {
            $this->deleteMedia();

            $this->addMedia($file, 'image', []);
        }
    }

    public function getImageAttribute()
    {
        return $this->getFirstUrl('image');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
