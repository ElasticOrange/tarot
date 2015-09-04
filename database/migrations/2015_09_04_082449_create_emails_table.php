<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emails', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('sent'); //type
            $table->string('from_email');
            $table->string('from_name');
            $table->string('to_email');
            $table->string('to_name');
            $table->string('subject');
            $table->timestamp('sent_at');
            $table->integer('bounce');
            $table->string('html_content', 30000);
            $table->string('text_content', 30000);
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
        Schema::drop('emails');
    }
}
