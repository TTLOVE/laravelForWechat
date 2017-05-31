<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Article;
use Event;
use App\Events\ArticleSaved;

class ArticleController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ArticleModel = new Article();
        $ArticleModel->getTitle(2); 
    }

    public function edit($id)
    {
        $article = Article::find($id);
        return view('articleEdit')->withArticle($article);
    }

    public function show($id)
    {
        $article = Article::find($id);
        return view('articleShow')->withArticle($article);
    }

    /**
        * 更新数据
        *
        * @param $request 请求数据
        * @param $id 文章id
        *
        * @return redirect
     */
    public function update(Request $request, $id)
    {
        $article = Article::find($id);
        if(!$article) {
            exit('指定文章不存在！');
        }

        $title = $request->input('title');
        $body = $request->input('body');

        $article->title = $title;
        $article->body = $body;
        $article->save();

        Event::fire(new ArticleSaved($article));

        return redirect()->route('article.show',['id'=>$id]);
    }
}
