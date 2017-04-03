<?php
namespace Mpociot\BotManTinker;

use Illuminate\Support\ServiceProvider;
use Mpociot\BotManTinker\Commands\BotManTinker;

class BotManTinkerServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->commands([
            BotManTinker::class
        ]);
    }

}