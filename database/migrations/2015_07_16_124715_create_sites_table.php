<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('url', 200);
            $table->string('email', 100);
            $table->string('sender', 100);
            $table->string('subject', 100);
            $table->string('signature', 1000);
            $table->softDeletes();
            $table->boolean('active');
            $table->timestamps();
        });

        Schema::create('site_user', function(Blueprint $table) {
            $table->integer('site_id')->unsigned()->index();
            $table->foreign('site_id')->references('id')->on('sites')->onDelete('cascade');
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::drop('site_user');
        Schema::drop('sites');
    }
}
