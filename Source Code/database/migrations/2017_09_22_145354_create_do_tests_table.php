<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDoTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('do_tests', function (Blueprint $table) {
            $table->integer('users_id')->unsigned();
            $table->integer('tests_id')->unsigned();
            $table->integer('time');
            $table->primary(['users_id','tests_id']);
            $table->foreign('users_id')->references('id')->on('users');
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
        Schema::drop('do_tests');
    }
}
