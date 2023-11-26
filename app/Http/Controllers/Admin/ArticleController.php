<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Resources\Admin\ArticleResource;
use App\Models\Article;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maize\Markable\Models\Like;
use Maize\Markable\Models\Reaction;
use Plank\Mediable\Facades\MediaUploader;
use Plank\Mediable\Media;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $result = Article::where('author_id' , auth()->id())->with(['tags' , 'media'])->paginate(15);


        return ArticleResource::collection($result);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArticleRequest $request)
    {
       $user=auth()->user();
        try {
            $article = Article::create([
                'title'=>$request->title,
                'content'=>$request->content,
                'author_id'=>$user->id,
                'category_id'=>$request->category_id,
                'meta_title'=>$request->meta_title,
                'meta_description'=>$request->meta_description,
                'shortlink'=>url("/Articles",\Str::Random(8)),
                'show_at_popular'=>$request->show_at_popular,
                'archive'=>$request->archive,
            ]);

            if($request->filled('thumb_id') &&  $media = Media::inDirectory('public' , 'blog/thumbnails')->find(request('thumb_id'), 'id') ){
                $article->syncMedia($media, 'thumbnail');
            }
            $article->tag($request->tags);

            if($request->like){
                Like::toggle($article,  $user );
            }

        }catch (\Exception $exception) {
            abort(500, 'we have problem');
        }

        return new ArticleResource($article);
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        return new ArticleResource($article);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArticleRequest $request, Article $article)
    {
        $user=auth()->user();
        if($article->author_id != auth()->user()->id)
        {
            return response()->json([
                'message'=>'error you dont have permision',
            ]);
        }
        try {
            $article->title = $request->title;
            $article->content= $request->content;
            $article->category_id =$request->category_id;
            $article->meta_title = $request->meta_title;
            $article->meta_description=$request->meta_description;
            $article->show_at_popular=$request->show_at_popular;
            $article->archive=$request->archive;
            $article->save();

            if($request->filled('thumb_id') &&  $media = Media::inDirectory('public' , 'blog/thumbnails')->find(request('thumb_id'), 'id') ){
                $article->syncMedia($media, 'thumbnail');
            }
            $article->retag($request->tags);

           if($request->like ){
                Like::toggle($article,  $user);
            }

        }catch (\Exception $exception) {
            abort(500, 'we have problem');
        }

        return new ArticleResource($article);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        $article->delete();
        return response()->json([
            'status'=>True,
            'message' => 'Article Deleted',
        ]);
    }
}
