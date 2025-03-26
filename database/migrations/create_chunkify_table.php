<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chunkify_files', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('extension')->nullable();
            $table->string('name')->nullable();
            $table->string('disk');
            $table->unsignedSmallInteger('expected_chunks_count');
            $table->unsignedSmallInteger('uploaded_chunks_count');
            $table->boolean('completed')->default(false);
            $table->timestamps();

            $table->index(['reference']);
            $table->index(['reference', 'completed']);
        });

        Schema::create('chunkify_chunks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chunkify_file_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('name');
            $table->unsignedMediumInteger('index');
            $table->string('disk');
            $table->timestamps();

            $table->unique(['chunkify_file_id', 'index']);

            $table->index(['chunkify_file_id']);
            $table->index(['chunkify_file_id', 'index']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chunkify_chunks');
        Schema::dropIfExists('chunkify_files');
    }
};
