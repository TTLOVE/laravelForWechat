<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\TestContract;
use App\Contracts\TestaContract;
use App\Services\TestService;
use App\Services\TestaService;
use App\Http\Requests;
use App\Article;

use App;
use TTest;
use App\Facades\TestClass;

class TestController extends Controller
{
    //依赖注入
    //public function __construct(TestContract $test){
    //public function __construct(TestaContract $test){
    //public function __construct(TestService $test){
    public function __construct(TestaService $test){
        $this->test = $test;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     * @author LaravelAcademy.org
     */
    public function index()
    {
        //$articles = Article::where('id', '>', 2)->max('id');
        //dd($articles);

        //$this->test->wtf(__FUNCTION__);

        //TTest::saySomething(); 
        //echo "\n";
        //TestClass::saySomething();

        //$articleModel = new Article();
        //$articleModel->title = 'the title';
        //$articleModel->body = 'the body';
        //$articleModel->user_id = 9;
        //if ( $articleModel->save() ) {
            //echo "ok";
        //} else {
            //echo "no";
        //}

        //$articleData = [
            //'title' => 'my title1',
            //'body' => 'my body1',
            //'user_id' => 3
        //];
        //$article = Article::firstOrCreate($articleData);
        //$article->user_id = 3;
        //$article->save();
        //dd($article);

        //$article = Article::find(12);
        //$article->body = 'oooo body';
        //if ( $article->save() ) {
            //echo 'ok';
        //} else {
            //echo 'no';
        //}

        //$articleData = [
            //'title' => 'oo title1',
            //'body' => 'oo body1',
            //'user_id' => 3
        //];
        //$article = Article::find(12);
        //if ( $article->update($articleData) ) {
            //echo 'ok';
        //} else {
            //echo 'no';
        //}

        $articles = Article::userOverZero()->idOverNum(3)->orderBy('id', 'desc')->get();
        foreach ($articles as $article) {
            echo $article->id . ' ' . $article->title . ' ' . $article->body;
            echo '<br>';
        }
        
    }
}
