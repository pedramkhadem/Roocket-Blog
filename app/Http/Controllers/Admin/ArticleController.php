<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Resources\Admin\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ArticleResource::collection(Article::paginate(15));
    }

    /**
     * Store a newly created resource in storage.
     */
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
