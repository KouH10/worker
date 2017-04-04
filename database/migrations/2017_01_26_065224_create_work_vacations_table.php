<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkVacationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_vacations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();            // ユーザID
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
            $table->date('date_at');                           // 勤務日付
            $table->integer('groupvacation_id')->nullable();   // 休暇時間開始
            $table->time('start_at')->nullable();              // 休暇時間開始
            $table->time('end_at')->nullable();                // 休暇時間終了
            $table->date('change_at')->nullable();             // 振替日付
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
        Schema::dropIfExists('work_vacations');
    }
}
