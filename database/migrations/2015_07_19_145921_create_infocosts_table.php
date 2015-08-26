<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInfocostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('infocosts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('site_id');
            $table->string('country', 30);
            $table->string('telephone', 20);
            $table->string('infocost', 1000);
            $table->boolean('active');
            $table->boolean('default');
            $table->timestamps();

            $table->index('site_id');

            $table  ->foreign('site_id')
                    ->references('listid')
                    ->on('email_lists')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('infocosts', function (Blueprint $table) {
            $table->dropForeign('infocosts_site_id_foreign');
        });

        Schema::drop('infocosts');
    }
}
