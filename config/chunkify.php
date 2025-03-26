<?php

return [
    'chunk_disk' => env('CHUNKIFY_CHUNK_DISK', 'public'),
    'file_disk' => env('CHUNKIFY_FILE_DISK', 'public'),

    'files_model' => CodeCreeper\Chunkify\Models\ChunkifyFile::class,
    'chunks_model' => CodeCreeper\Chunkify\Models\ChunkifyChunk::class,

    'path_prefix' => env('CHUNKIFY_PATH_PREFIX', 'chunkify'),

    'path_generator' => \CodeCreeper\Chunkify\ChunkifyPathGenerator::class,

    'request' => [
        'chunk_reference' => 'chunk_reference',
        'chunk_extension' => 'chunk_extension',
        'chunk_index' => 'chunk_index',
        'chunk_total_count' => 'chunk_total_count',
        'chunk_data' => 'chunk_data',
    ],
];
