<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\TestContract;
use App\Contracts\TestaContract;
use App\Services\TestService;
use App\Services\TestaService;
use App\Http\Requests;

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
        $this->test->wtf(__FUNCTION__);
    }
}
