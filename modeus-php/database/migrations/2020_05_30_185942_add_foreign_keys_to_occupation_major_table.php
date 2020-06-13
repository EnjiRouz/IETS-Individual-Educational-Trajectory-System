<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToOccupationMajorTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('occupation_major', function(Blueprint $table)
		{
			$table->foreign('major_id', 'fk5i5nslo1rly6ouvnm51113g4t')->references('id')->on('major')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('occupation_id', 'fknvjv7592uab15ou4r7cp0oxot')->references('id')->on('occupation')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('occupation_major', function(Blueprint $table)
		{
			$table->dropForeign('fk5i5nslo1rly6ouvnm51113g4t');
			$table->dropForeign('fknvjv7592uab15ou4r7cp0oxot');
		});
	}

}
