<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentTaggable\Taggable;

class Article extends Model
{

    use HasFactory;
    use Sluggable;
    use Taggable;


    protected  $fillable =[
        'title',
        'content',
        'slug',
        'author_id',
        'category_id',
        'meta_title',
        'meta_description',
        'shortlink',
        'show_at_popular',
        'archive'
    ];

/**get and store the article slug  */

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
                'onUpdate' => true
            ]
        ];
    }


/**get the articel category */


    public function category()
    {
        return $this->belongsTo(Category::class);
    }


/**get the author of article */

public function user()
{
    return $this->belongsTo(User::class);
}


}
