<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('numbers', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('user_id');
			$table->integer('customer_id');
			$table->integer('number');
			$table->integer('tang');
			$table->integer('tod');
			$table->integer('uptwo');
			$table->integer('todtwo');
			$table->integer('upwing');
			$table->integer('downtwo');
			$table->integer('downtree');
			$table->integer('downwing');
			$table->integer('amount');
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
        Schema::drop('numbers');
    }
}
