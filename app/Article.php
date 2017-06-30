<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    public $table = 'articles';
    public $primaryKey = 'id';
    protected $guarded = ['user_id'];

    public function getTitle($id)
    {
        $article = Article::find($id);

        echo $article->title;
    }

    public function scopeUserOverZero($query)
    {
        return $query->where('user_id', '>', 0);
    }

    public function scopeIdOverNum($query, $setNum)
    {
        return $query->where('id', '>', $setNum);
    }
}
