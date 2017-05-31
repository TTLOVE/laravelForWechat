<?php

namespace App\Listeners;

use App\Events\ArticleSaved;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Cache;
use Log;

class SaveDataToCache
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ArticleSaved  $event
     * @return void
     */
    public function handle(ArticleSaved $event)
    {
        $article = $event->article;
        $key = 'article_' . $article->id;
        Cache::put($key, $article, 60);
        Log::info('保存文章到缓存成功！',['id'=>$article->id,'title'=>$article->title]);
    }
}
