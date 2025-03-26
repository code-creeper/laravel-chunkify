<?php

use CodeCreeper\Chunkify\Controllers\ChunkifyController;
use Illuminate\Support\Facades\Route;

Route::prefix('/chunkify')->group(function () {
    Route::post('/chunkify', ChunkifyController::class)->name('chunkify');
});
