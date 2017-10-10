<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTienQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tien_questions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('answer')->unique();
            $table->string('wh');
            $table->string('yes_no');
            $table->string('or');
            $table->string('tag');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tien_questions');
    }
}
