<?php
/**
 * Created by PhpStorm.
 * User: vikasroy
 * Date: 13/09/17
 * Time: 5:10 PM
 */

namespace RoySailor\Restify;

use Illuminate\Support\ServiceProvider;
use RoySailor\Restify\GenerateRestModel;

class RestifyServiceProvider extends ServiceProvider
{

    public function boot(){

        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateRestModel::class
            ]);
        }

    }

    public function register(){



    }

}