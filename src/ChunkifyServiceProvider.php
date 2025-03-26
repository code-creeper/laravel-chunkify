<?php

namespace CodeCreeper\Chunkify;

use CodeCreeper\Chunkify\Commands\ChunkifyCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

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
