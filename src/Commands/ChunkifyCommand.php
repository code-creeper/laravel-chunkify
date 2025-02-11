<?php

namespace CodeCreeper\Chunkify\Commands;

use Illuminate\Console\Command;

class ChunkifyCommand extends Command
{
    public $signature = 'laravel-chunkify';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
