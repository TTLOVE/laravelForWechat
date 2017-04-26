<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Cache;

class PostController extends Controller
{
    public function index()
    {
        $posts = Cache::get('posts',[]);
        return view('postIndex')->withPosts($posts);
    }

    public function create()
    {
        return view('postCreate');        
    }

    public function store(Request $request)
    {
        $title = $request->input('title');
        $content = $request->input('body');
        $post = ['title'=>trim($title),'content'=>trim($content)];

        $posts = Cache::get('posts',[]);

        if(!Cache::get('post_id')){
            Cache::add('post_id',1,60);
        }else{
            Cache::increment('post_id',1); 
        }
        $post['post_id'] = Cache::get('post_id');
        $posts[Cache::get('post_id')] = $post;

        Cache::put('posts',$posts,60);
        return redirect()->route('post.show',['post'=>Cache::get('post_id')]);
    }

    public function show($id)
    {
        $posts = Cache::get('posts',[]);
        if(!$posts || !$posts[$id]) {
            exit('Nothing Found！');
        }
        $post = $posts[$id];    
        return view('postShow')->withPost($post);
    }

    public function edit($id)
    {
        $posts = Cache::get('posts',[]);
        if(!$posts || !$posts[$id]) {
            exit('Nothing Found！');
        }
        $post = $posts[$id];    
        return view('postEdit')->withPost($post);
    }

    public function update(Request $request, $postId)
    {
        $title = $request->input('title');
        $content = $request->input('body');

        $post = ['title'=>trim($title),'content'=>trim($content),'post_id'=>$postId];

        $posts = Cache::get('posts',[]);
        if(!$posts || !$posts[$postId]) {
            redirect()->back()->withInput()->withErrors('找不到对应的内容');
        }
        $posts[$postId] = $post;
        Cache::put('posts',$posts,60);
        return redirect()->route('post.show',['post'=>$postId]);
    }

    public function destroy($id)
    {
        $posts = Cache::get('posts',[]);
        if(!$posts || !$posts[$id]) {
            exit('Nothing Deleted！');
        }

        unset($posts[$id]);
        Cache::decrement('post_id',1);
        Cache::put('posts',$posts,60);

        return redirect()->route('post.index');

    }
}
