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
use Plank\Mediable\Facades\MediaUploader;


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

    public function mediaUploader(FormRequest $request , Article $article)
    {

        $media = MediaUploader::fromSource($request->file('thumb'))
        ->toDestination('public', 'blog/thumbnails')
        ->upload();
        $article = Article::findOrfail($request->id);

        $article->attachMedia($media, ['thumbnail']);

        if($media){
            return response()->json([
                'msg' => 'your media uploaded',
                'media' => $media
            ]);
        }
    }


    public function store(StoreArticleRequest $request)
    {
        $request->safe()->all();

        $article = Article::create([
            'title'=>$request->title,
            'content'=>$request->content,
            'author_id'=>Auth::user()->id,
            'category_id'=>$request->category_id,
            'meta_title'=>$request->meta_title,
            'meta_description'=>$request->meta_description,
            'shortlink'=>url("/Articles",\Str::Random(8)),
            'show_at_popular'=>$request->show_at_popular,
            'archive'=>$request->archive,
        ]);
        $article->tag($request->tags);

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
        $request->safe()->all();

        if($article->author_id != auth()->user()->id)
        {
            return response()->json([
                'message'=>'error you dont have permision',
            ]);
        }
        $request->safe()->all();
        $article->title = $request->title;
        $article->content= $request->content;
        $article->category_id =$request->category_id;
        $article->meta_title = $request->meta_title;
        $article->meta_description=$request->meta_description;
        $article->show_at_popular=$request->show_at_popular;
        $article->archive=$request->archive;
        $article->save();

        $article->retag($request->tags);

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
