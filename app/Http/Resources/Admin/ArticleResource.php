<?php

namespace App\Http\Resources\Admin;

use App\Models\Article;
use Cviebrock\EloquentTaggable\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Maize\Markable\Models\Like;

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
            'category'=>$this->category,
             'author_id'=>$this->author_id,
             'title'=>$this->title,
             'content'=>$this->content,
             'slug'=>$this->slug,
             'meta_title'=>$this->meta_title,
             'meta_description'=>$this->meta_description,
             'shortlink'=>$this->shortlink,

             'created_at'=>$this->created_at,
             'updated_at'=>$this->updated_at,
             'thumb'=>$this->when($this->hasMedia('thumbnail'), function() {
                 return $this->firstMedia('thumbnail')?->getUrl();
             }, null),

            'likes' => $this->likes_count,
            'bookmark'=>$this->bookmarks ?? [],
             'tags' =>$this->when($this->tagArray ,function(){
                 return $this->tagArray;
             }, null) ,
             'show_at_popular'=>$this->show_at_popular,
             'archive'=>$this->archive,
        ];

    }
}
