<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAffiliationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('affiliations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_id')->unsigned();       // グループID
            $table->foreign('group_id')
                  ->references('id')->on('groups')
                  ->onDelete('cascade');
            $table->integer('user_id')->unsigned();         // ユーザID
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
            $table->datetime('applystart_at');             // 適用開始日
            $table->datetime('applyend_at');               // 適用終了日
            $table->datetime('entry_at');                  // 入社日
            $table->integer('admin');                      // 権限
            $table->string('employee_no');                 // 社員番号
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
        Schema::dropIfExists('affiliations');
    }
}
