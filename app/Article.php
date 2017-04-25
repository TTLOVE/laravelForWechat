<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    public function getTitle($id)
    {
        $article = Article::find($id);

        echo $article->title;
    }
}
