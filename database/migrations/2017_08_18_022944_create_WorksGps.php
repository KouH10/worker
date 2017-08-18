<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorksGps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('works_gps', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('work_id')->unsigned();        // 勤務情報ID
          $table->foreign('work_id')
                ->references('id')->on('works')
                ->onDelete('cascade');
          $table->string('kbn');                         // 区分
          $table->string('latitude')->nullable();;       // 緯度
          $table->string('longitude')->nullable();;      // 経度
          $table->string('altitude')->nullable();;       // 高度
          $table->string('accuracy')->nullable();;       // 緯度・経度の誤差
          $table->string('altitudeAccuracy')->nullable();; // 高度の誤差
          $table->string('heading')->nullable();;        // 方角
          $table->string('speed')->nullable();;          // 速度

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
        Schema::dropIfExists('works_gps');
    }
}
