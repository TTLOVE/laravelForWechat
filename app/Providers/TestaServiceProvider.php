<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\TestaService;

class TestaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //使用singleton绑定单例
        $this->app->singleton('aaa',function(){
            return new TestaService();
        });

        //使用bind绑定实例到接口以便依赖注入
        $this->app->bind('App\Contracts\TestaContract',function(){
            return new TestaService();
        });
    }
}
