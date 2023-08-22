<?php

namespace App\Facades;

use Cerbos\Sdk\CerbosClient;
use Illuminate\Support\Facades\Facade;

class Cerbos extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return CerbosClient::class;
    }
}
