<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;
use Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        if(config('app.debug') === true){
            DB::listen(function($sql) {
                $tmp = str_replace('?', '"'.'%s'.'"',$sql);
                $tmp = str_replace("\\","",$tmp);
                Log::info($tmp."\n\t");
          });
        }
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
