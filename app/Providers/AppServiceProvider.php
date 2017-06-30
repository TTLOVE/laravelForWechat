<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //视图间共享数据
        view()->share('siteName','ll.com');

        //视图Composer
        view()->composer('home',function($view){
            $view->with('user',array('name'=>'test','avatar'=>'/path/to/test.jpg'));
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
