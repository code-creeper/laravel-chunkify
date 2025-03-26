<?php

namespace CodeCreeper\Chunkify\Controllers;

use CodeCreeper\Chunkify\Models\ChunkifyChunk;
use CodeCreeper\Chunkify\Models\ChunkifyFile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ChunkifyController
{
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            Config::get('chunkify.request.chunk_reference') => 'required',
            Config::get('chunkify.request.chunk_extension') => 'required',
            Config::get('chunkify.request.chunk_index') => 'required',
            Config::get('chunkify.request.chunk_total_count') => 'required',
            Config::get('chunkify.request.chunk_data') => 'required',
        ]);

        $extension = $request->get(Config::get('chunkify.request.chunk_extension'));
        $index = $request->get(Config::get('chunkify.request.chunk_index'));
        $reference = $request->get(Config::get('chunkify.request.chunk_reference'));
        $totalCount = $request->get(Config::get('chunkify.request.chunk_total_count'));
        $data = $request->file(Config::get('chunkify.request.chunk_data'))
            ?? $request->input(Config::get('chunkify.request.chunk_data'));
        $fileDisk = Config::get('chunkify.file_disk');
        $chunkDisk = Config::get('chunkify.chunk_disk');

        DB::beginTransaction();

        try {
            /** @var Model $chunkifyFileClass */
            $chunkifyFileClass = App::make(Config::get('chunkify.files_model'));

            /** @var ChunkifyFile $chunkifyFile */
            $chunkifyFile = $chunkifyFileClass::firstOrCreate(
                ['reference' => $reference],
                [
                    'extension' => $extension,
                    'disk' => $fileDisk,
                    'expected_chunks_count' => $totalCount,
                    'uploaded_chunks_count' => 0,
                    'name' => Str::random(),
                ]
            );

            /**
             * @var ChunkifyChunk $chunkifyChunk
             */
            $chunkifyChunk = $chunkifyFile->chunks()->create([
                'index' => $index,
                'disk' => $chunkDisk,
                'name' => $index.'_'.Str::random(),
            ]);

            if ($data instanceof UploadedFile) {
                $content = $data->getContent();
            } else {
                $content = $data;
            }

            $directory = "chunkify/{$chunkifyFile->id}";

            Storage::disk($chunkDisk)->put("$directory/{$chunkifyChunk->name}.blob", $content);

            $chunkifyFile->increment('uploaded_chunks_count');
            $chunkifyFile->refresh();

            if ($chunkifyFile->uploaded_chunks_count == $chunkifyFile->expected_chunks_count) {
                $mergeFilePath = "$directory/{$chunkifyFile->name}.{$extension}";

                $outputStream = fopen(Storage::disk($chunkDisk)->path($mergeFilePath), 'w');

                foreach ($chunkifyFile->chunks()->cursor() as $chunk) {
                    $chunkFilePath = $chunk->getFilePath();
                    $chunkFileStream = fopen($chunkFilePath, 'r');

                    stream_copy_to_stream($chunkFileStream, $outputStream);

                    fclose($chunkFileStream);
                }

                fclose($outputStream);

                $chunkifyFile->update(['completed' => true]);
            }
        } catch (\Throwable $throwable) {
            DB::rollBack();

            return Response::json([
                'error' => $throwable->getMessage(),
                'completed' => false,
            ], 500);
        }

        DB::commit();

        return Response::json([
            'completed' => $chunkifyFile->completed,
        ]);
    }
}
