<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailboxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emailboxes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('smtpServer', 100);
            $table->integer('smtpPort');
            $table->string('smtpEncription', 100);
            $table->string('smtpUsername', 100);
            $table->string('smtpPassword', 100);
            $table->string('imapServer', 100);
            $table->integer('imapPort');
            $table->string('imapProtocol', 100);
            $table->string('imapEncription', 100);
            $table->string('imapFolder', 100);
            $table->string('imapUsername', 100);
            $table->string('imapPassword', 100);
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
        Schema::drop('emailboxes');
    }
}
