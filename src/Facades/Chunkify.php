<?php

namespace CodeCreeper\Chunkify\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \CodeCreeper\Chunkify\Chunkify
 */
class Chunkify extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \CodeCreeper\Chunkify\Chunkify::class;
    }
}
