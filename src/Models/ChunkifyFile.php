<?php

namespace CodeCreeper\Chunkify\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property int $uploaded_chunks_count
 * @property int $expected_chunks_count
 * @property bool $completed
 * @property string $extension
 * @property string $name
 */
class ChunkifyFile extends Model
{
    protected $table = 'chunkify_files';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'completed' => 'boolean',
        ];
    }

    public function chunks(): HasMany
    {
        return $this->hasMany(Config::get('chunkify.chunks_model'))->orderBy('index');
    }

    public function getFilePath(): string
    {
        return Storage::disk(Config::get('chunkify.file_disk'))->path("chunkify/{$this->id}/{$this->name}.{$this->extension}");
    }

    public function getDiskPath(): string
    {
        return "chunkify/{$this->id}/{$this->name}.{$this->extension}";
    }
}
