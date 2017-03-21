<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupMonthliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_monthlies', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('group_id')->unsigned();       // グループID
            $table->foreign('group_id')
                  ->references('id')->on('groups')
                  ->onDelete('cascade');
            $table->date('confirm_at');                    // 月次確定日
            $table->integer('user_id')->unsigned();        // ユーザID
            $table->foreign('user_id')
                  ->references('id')->on('users')
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
        Schema::dropIfExists('group_monthlies');
    }
}
