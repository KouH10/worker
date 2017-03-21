<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');                   // グループ名
            $table->time('workingstart_st');          // 所定労働時間開始
            $table->time('workingend_st');            // 所定労働時間終了
            $table->time('reststart_st');             // 休憩時間開始
            $table->time('restend_st');               // 休憩時間終了
            $table->time('nightstart_st');            // 深夜時間開始
            $table->time('nightend_st');              // 深夜時間終了
            $table->string('legalholiday');           // 法定休日
            $table->String('notlegalholiday');        // 法定外休日
            $table->string('weekstart');              // 週開始
            $table->integer('monthstart');            // 月開始日
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
        Schema::dropIfExists('groups');
    }
}
