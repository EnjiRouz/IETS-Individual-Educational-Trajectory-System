<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOccupationMajorTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('occupation_major', function(Blueprint $table)
		{
			$table->bigInteger('occupation_id');
			$table->bigInteger('major_id');
			$table->primary(['occupation_id','major_id'], 'occupation_major_pkey');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('occupation_major');
	}

}
