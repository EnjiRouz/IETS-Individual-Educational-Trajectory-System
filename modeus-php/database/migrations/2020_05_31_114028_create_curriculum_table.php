<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCurriculumTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('curriculum', function(Blueprint $table)
		{
			$table->bigInteger('curriculum_id')->primary('curriculum_pkey');
			$table->string('basic_subjects_ids_1', 1023)->nullable();
			$table->string('basic_subjects_ids_2', 1023)->nullable();
			$table->string('basic_subjects_ids_3', 1023)->nullable();
			$table->string('basic_subjects_ids_4', 1023)->nullable();
			$table->string('basic_subjects_ids_5', 1023)->nullable();
			$table->string('basic_subjects_ids_6', 1023)->nullable();
			$table->string('basic_subjects_ids_7', 1023)->nullable();
			$table->string('basic_subjects_ids_8', 1023)->nullable();
			$table->string('extra_subjects_ids', 1023)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('curriculum');
	}

}
