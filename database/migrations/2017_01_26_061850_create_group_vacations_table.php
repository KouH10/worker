<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupVacationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_vacations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_id')->unsigned();       // グループID
            $table->foreign('group_id')
                  ->references('id')->on('groups')
                  ->onDelete('cascade');
            $table->string('name');                        // 休暇名
            $table->integer('vacationtime');               // 休暇時間
            $table->boolean('wage_flg');                   // 賃金有無
            $table->boolean('specifytime_flg');            // 時間指定有無
            $table->boolean('specifyday_flg');             // 日付指定有無
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
        Schema::dropIfExists('group_vacations');
    }
}
