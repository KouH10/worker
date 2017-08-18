<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCoumnGpsUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      // 現在地取得有無
      Schema::table('users', function (Blueprint $table) {
        $table->boolean('gps')->default('1');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //現在地取得有無
        Schema::table('users', function (Blueprint $table) {
          $table->dropColumn('gps');
        });
    }
}
