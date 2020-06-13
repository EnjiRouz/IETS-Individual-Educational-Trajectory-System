<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOccupationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('occupation', function(Blueprint $table)
		{
			$table->bigInteger('id')->primary('occupation_pkey');
			$table->string('description', 5000)->nullable();
			$table->string('name')->nullable();
			$table->bigInteger('trajectory_id')->nullable();
			$table->string('occupation_extra_subjects_preset', 1023)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('occupation');
	}

}
