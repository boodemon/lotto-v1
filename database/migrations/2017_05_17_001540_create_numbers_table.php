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
			$table->integer('period_id');
			$table->integer('user_id');
			$table->integer('customer_id');
			$table->string('number');
			$table->integer('tang');
			$table->integer('tod');
			$table->enum('wingup',['N','Y']);
			$table->enum('wingdown',['N','Y']);
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
