<?php

namespace CodeCreeper\Chunkify;

use Illuminate\Contracts\Foundation\CachesRoutes;
use Illuminate\Support\Facades\Route;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use CodeCreeper\Chunkify\Commands\ChunkifyCommand;

class ChunkifyServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-chunkify')
            ->hasConfigFile()
            ->hasRoute('web')
            ->hasMigration('create_chunkify_table')
            ->runsMigrations()
            ->hasCommand(ChunkifyCommand::class);
    }
}
