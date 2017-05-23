<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('results', function (Blueprint $table) {
            $table->increments('id');
			$table->string('tang',30);
			$table->string('tod',30);
			$table->string('uptwo',30);
			$table->string('todtwo',30);
			$table->string('upwing',30);
			$table->string('downtwo',30);
			$table->string('downtree',30);
			$table->string('downwing',30);
			$table->integer('period_id');
			$table->date('period');
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
        Schema::drop('results');
    }
}
