<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManageTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manage_tests', function (Blueprint $table) {
            $table->integer('tests_id')->unsigned();
            $table->integer('questions_id')->unsigned();
            $table->integer('index');

            $table->primary(['tests_id','questions_id']);
            $table->foreign('tests_id')->references('id')->on('tests');
            $table->foreign('questions_id')->references('id')->on('questions');

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
        Schema::drop('manage_tests');
    }
}
