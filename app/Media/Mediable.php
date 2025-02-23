<?php

namespace App\Media;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface Mediable
{
    public function media(): MorphMany;

    public function addMedia($file, string $collectionName, array $attributes = []): void;

    public function getMedia(string $collectionName = null): array;
}