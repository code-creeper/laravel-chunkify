<?php

namespace CodeCreeper\Chunkify\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @property string $name
 * @property int $chunkify_file_id
 * @property int $index
 * @property string $disk
 */
class ChunkifyChunk extends Model
{
    protected $table = 'chunkify_chunks';

    protected $guarded = ['id'];

    public function getFilePath(): string
    {
        return Storage::disk(Config::get('chunkify.chunk_disk'))->path("chunkify/{$this->chunkify_file_id}/{$this->name}.blob");
    }

    public function getFileContent(): string
    {
        return Storage::disk(Config::get('chunkify.chunk_disk'))->get("chunkify/{$this->chunkify_file_id}/{$this->name}.blob");
    }
}
