<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePresentationsTable extends Migration
{
    public function up()
    {
        Schema::create('presentations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('speaker_name');
            $table->string('access_code', 10)->unique();
            $table->integer('current_slide_index')->default(0);
            $table->enum('status', ['draft', 'live', 'ended'])->default('draft');
            $table->timestamps();
        });

        Schema::create('slides', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('presentation_id')->unsigned();
            $table->string('title');
            $table->text('content')->nullable();
            $table->string('image_url')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->foreign('presentation_id')->references('id')->on('presentations')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::drop('slides');
        Schema::drop('presentations');
    }
}
