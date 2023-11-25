<?php

namespace App\Http\Resources\Admin;

use Cviebrock\EloquentTaggable\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [

            'id'=>$this->id,
            'category_id'=>$this->category_id,
            'author_id'=>$this->author_id,
            'attributes'=>[
                'title'=>$this->title,
                'content'=>$this->content,
                'slug'=>$this->slug,
                'meta_title'=>$this->meta_title,
                'meta_description'=>$this->meta_description,
                'shortlink'=>$this->shortlink,
                'created_at'=>$this->created_at,
                'updated_at'=>$this->updated_at,
            ],

            'tags' =>$this->when($this->tagArray ,function(){
                return $this->tagArray;
            }, null) ,
            'thumb'=>$this->when($this->hasMedia('thumbnail'), function() {
                return $this->firstMedia('thumbnail')?->getUrl();
            }, null),

            'show_at_popular'=>$this->show_at_popular,
            'archive'=>$this->archive,
        ];

    }
}
