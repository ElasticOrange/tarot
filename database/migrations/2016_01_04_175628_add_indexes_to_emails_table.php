<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexesToEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('ALTER TABLE `emails` ADD INDEX(`to_email`)');
        \DB::statement('ALTER TABLE `emails` ADD INDEX(`from_email`)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement('ALTER TABLE `emails` DROP INDEX `to_email` ');
        \DB::statement('ALTER TABLE `emails` DROP INDEX `from_email` ');
    }
}
