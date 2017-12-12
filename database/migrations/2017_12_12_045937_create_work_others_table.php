<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkOthersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_others', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('work_id')->unsigned();        // �Ζ����ID
            $table->foreign('work_id')
                ->references('id')->on('works')
                ->onDelete('cascade');
            $table->string('kbn');                         // �敪
            $table->time('start_at')->nullable();          // �J�n����
            $table->time('end_at')->nullable();            // �I������
            $table->string('memo')->nullable();            // ����

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
        Schema::dropIfExists('work_others');
    }
}
