<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalariedHolidaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salaried_holidays', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();        // ユーザID
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
            $table->datetime('applystart_at');             // 適用開始日
            $table->datetime('applyend_at');               // 適用終了日
            $table->integer('grantdays');                  // 付与日数
            $table->integer('usedays');                    // 使用日数
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
        Schema::dropIfExists('salaried_holidays');
    }
}
