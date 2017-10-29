<?php

namespace BotMan\Tinker;

use BotMan\Tinker\Commands\Tinker;
use Illuminate\Support\ServiceProvider;

class TinkerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands([
            Tinker::class,
        ]);
    }
}
