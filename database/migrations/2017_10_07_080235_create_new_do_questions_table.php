<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewDoQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('do_questions', function (Blueprint $table) {
            $table->integer('users_id')->unsigned();
            $table->integer('questions_id')->unsigned();
            $table->integer('tests_id')->unsigned();
            $table->tinyInteger('check');
            $table->string('answerofuser',45);
            $table->primary(['users_id','questions_id','tests_id']);
            $table->foreign('users_id')->references('id')->on('users');
            $table->foreign('questions_id')->references('id')->on('questions');
            $table->foreign('tests_id')->references('id')->on('tests');

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
        //
        Schema::dropIfExists('do_questions');
    }
}
