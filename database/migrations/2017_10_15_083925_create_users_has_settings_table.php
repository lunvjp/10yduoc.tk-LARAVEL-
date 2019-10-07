<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersHasSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_has_settings', function (Blueprint $table) {
            $table->integer('settings_id')->unsigned();
            $table->integer('users_id')->unsigned();
            $table->primary(['settings_id','users_id']);

            $table->timestamps();
        });

        Schema::table('users_has_settings',function($table){
            $table->foreign('users_id')->references('id')->on('users');
            $table->foreign('settings_id')->references('id')->on('settings');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users_has_settings');
    }
}
