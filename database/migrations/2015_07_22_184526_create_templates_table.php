<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('templates', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('site_id')->index();
            $table->string('category', 20);
            $table->string('name', 100);
            $table->string('type', 100);
            $table->mediumtext('content');
            $table->boolean('active');
            $table->softDeletes();
            $table->timestamps();

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
        Schema::drop('templates');
    }
}
