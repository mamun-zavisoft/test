<?php

namespace App\Models;

use App\Media\HasMedia;
use App\Media\Mediable;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model implements Mediable
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
        $img_url =  $this->getFirstUrl('image');
        return $img_url;
    }
}
