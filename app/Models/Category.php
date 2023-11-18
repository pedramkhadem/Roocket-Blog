<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Relations\MorphTo;


class Category extends Model
{
    use HasFactory;
    use Sluggable;

    protected $fillable =['name' , 'slug' ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
                'onUpdate' => true
            ]
        ];
    }


    public function categoyable(): MorphTo
    {
        return $this->morphTo();
    }


}
