<?php

namespace CodeCreeper\Chunkify;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use CodeCreeper\Chunkify\Commands\ChunkifyCommand;

class ChunkifyServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-chunkify')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel_chunkify_table')
            ->hasCommand(ChunkifyCommand::class);
    }
}
