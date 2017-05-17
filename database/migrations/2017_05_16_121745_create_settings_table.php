<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('tang');
			$table->integer('tod');
			$table->integer('uptwo');
			$table->integer('todtwo');
			$table->integer('upwing');
			$table->integer('downtwo');
			$table->integer('downtree');
			$table->integer('downwing');
			$table->integer('comission');
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
        Schema::drop('settings');
    }
}
