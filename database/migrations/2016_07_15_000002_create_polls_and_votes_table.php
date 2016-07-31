<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePollsAndVotesTable extends Migration
{
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('presentation_id')->unsigned();
            $table->integer('slide_index')->default(0);
            $table->string('question_text');
            $table->boolean('is_active')->default(false);
            $table->timestamps();

            $table->foreign('presentation_id')->references('id')->on('presentations')->onDelete('cascade');
        });

        Schema::create('options', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('question_id')->unsigned();
            $table->string('option_text');
            $table->integer('vote_count')->default(0);
            $table->timestamps();

            $table->foreign('question_id')->references('id')->on('questions')->onDelete('cascade');
        });

        Schema::create('votes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('question_id')->unsigned();
            $table->integer('option_id')->unsigned();
            $table->string('voter_device_id');
            $table->timestamps();

            $table->unique(['question_id', 'voter_device_id']);
        });
    }

    public function down()
    {
        Schema::drop('votes');
        Schema::drop('options');
        Schema::drop('questions');
    }
}
