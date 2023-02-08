<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChapterViewerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chapter_viewer', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('manga_id');
            $table->unsignedBigInteger('chapter_id');
            $table->integer('count_viewer');
            $table->integer('created_at');
            $table->integer('updated_at');
            $table->index(['manga_id', 'chapter_id'], 'idx_manga_chapter_viewer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chapter_viewer');
    }
}
