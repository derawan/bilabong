<?php

namespace Irfansjah\Gtx;

use Exception;
use Illuminate\Support\ServiceProvider;
use Irfansjah\Gtx\Helper\Gtx;
use Irfansjah\Gtx\Models\SystemConfig;

class GtxServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Gtx',function(){

            return new Gtx();

        });
    }



    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        try {
            config([
            'global' => SystemConfig::all(['name','value','group'])
                ->groupBy('group')
                ->transform(
                    function ($setting) {
                        $cl = collect($setting->all(['name','value']))
                            ->keyBy('name')
                            ->transform(
                                function ($st) {
                                    return $st->value;
                                    })
                                ->toArray();
                        return $cl;
                    })
                ->toArray()
        ]);
        }
        catch (Exception $e){
            // do nothing when we unable to load application configurations from database
        }

        $this->loadViewsFrom(__DIR__.'/Views', 'gtx');
        /* publish config*/
        $this->publishes([
            __DIR__.'/Config/gtx.php' => config_path('gtx.php'),
        ],'gtx:config');
        /* publish config*/
        $this->publishes([
            __DIR__.'/Database/Migrations' => base_path('database/migrations'),
            __DIR__.'/Database/Seeds' => base_path('database/seeds'),
        ],'gtx:migrations');
    }
}
