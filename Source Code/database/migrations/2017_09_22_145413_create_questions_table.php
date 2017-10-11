<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',1000)->unique();
            $table->string('a',1000);
            $table->string('b',1000);
            $table->string('c',1000);
            $table->string('d',1000)->nullable();
            $table->string('e',1000)->nullable();
            $table->string('f',1000)->nullable();
            $table->string('g',1000)->nullable();
            $table->string('answer');
            $table->string('detail_answer',1000)->default('Chưa có lời giải');
            $table->timestamps();
            $table->integer('units_id')->unsigned();
            $table->foreign('units_id')->references('id')->on('units');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('questions');
    }
}
