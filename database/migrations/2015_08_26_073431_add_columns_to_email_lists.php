<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToEmailLists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('email_lists', function (Blueprint $table) {
            $table->string('url', 200);
            $table->string('subject', 100);
            $table->string('signature', 1000);
            $table->boolean('active');
            $table->softDeletes();
            $table->timestamps();
        });


        Schema::create('site_user', function(Blueprint $table) {
            $table->integer('site_id')->index();
            $table->foreign('site_id')->references('listid')->on('email_lists')->onDelete('cascade');
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
        Schema::table('email_lists', function (Blueprint $table) {
            $table->dropColumn([
                'url',
                'subject',
                'signature',
                'active',
                'deleted_at',
                'created_at',
                'updated_at'
            ]);
        });

        Schema::drop('site_user');
    }
}
