<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkOtherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_other', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('work_id')->unsigned();        // 勤務情報ID
            $table->foreign('work_id')
                ->references('id')->on('works')
                ->onDelete('cascade');
            $table->string('kbn');                         // 区分
            $table->time('start_at')->nullable();          // 開始時間
            $table->time('end_at')->nullable();            // 終了時間
            $table->string('memo')->nullable();            // メモ

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
        Schema::dropIfExists('work_other');
    }
}
