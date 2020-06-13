<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSalaryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('salary', function(Blueprint $table)
		{
			$table->bigInteger('id')->primary('salary_pkey');
			$table->integer('average');
			$table->integer('highest');
			$table->string('stats', 2000)->nullable();
			$table->integer('without_experience');
			$table->integer('year_of_experience');
			$table->bigInteger('occupation_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('salary');
	}

}
