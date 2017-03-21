<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupHolidaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_holidays', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_id')->unsigned();       // グループID
            $table->foreign('group_id')
                  ->references('id')->on('groups')
                  ->onDelete('cascade');
            $table->date('date_at');                       // 休日日付    
            $table->string('name');                        // 休日名
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
        Schema::dropIfExists('group_holidays');
    }
}
