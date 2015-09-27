<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('email_list_subscribers', function (Blueprint $table) {
            $table->string('partnerName');
            $table->string('interest');
            $table->boolean('questionAnswered');
            $table->boolean('ignore');
            $table->boolean('problem');
            $table->string('comment');
            $table->softDeletes();
            $table->dateTime('opened_at');
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
        Schema::table('email_list_subscribers', function (Blueprint $table) {
            $table->dropColumn([
                'partnerName',
                'interest',
                'questionAnswered',
                'ignore',
                'problem',
                'comment',
                'opened_at',
                'deleted_at',
                'created_at',
                'updated_at'
            ]);
        });
    }
}
