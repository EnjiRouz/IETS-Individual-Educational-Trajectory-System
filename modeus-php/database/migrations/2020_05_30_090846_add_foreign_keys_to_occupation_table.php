<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToOccupationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('occupation', function(Blueprint $table)
		{
			$table->foreign('trajectory_id', 'fkrcjduxap3dnym7813b6xved7y')->references('id')->on('trajectory')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('occupation', function(Blueprint $table)
		{
			$table->dropForeign('fkrcjduxap3dnym7813b6xved7y');
		});
	}

}
