<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('works', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
            $table->date('date_at');
            $table->dateTime('attendance_at')->nullable();
            $table->dateTime('leaving_at')->nullable();
            $table->dateTime('attendance_stamp_at')->nullable();
            $table->dateTime('leaving_stamp_at')->nullable();
            $table->integer('worktime')->nullable();
            $table->integer('predeterminedtime')->nullable();
            $table->integer('overtime')->nullable();
            $table->integer('nighttime')->nullable();
            $table->integer('holidaytime')->nullable();
            $table->text('content')->nullable();
            $table->string('type')->nullable();

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
        Schema::dropIfExists('works');
    }
}
