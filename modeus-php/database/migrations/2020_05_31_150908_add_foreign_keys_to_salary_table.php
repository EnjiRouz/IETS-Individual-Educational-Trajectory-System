<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToSalaryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('salary', function(Blueprint $table)
		{
			$table->foreign('occupation_id', 'fkah9v3ehilab9id768kwi7092x')->references('id')->on('occupation')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('salary', function(Blueprint $table)
		{
			$table->dropForeign('fkah9v3ehilab9id768kwi7092x');
		});
	}

}
