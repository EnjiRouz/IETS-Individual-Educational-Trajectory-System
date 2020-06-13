<?php

use Illuminate\Http\Request;
use App\Trajectory;
use App\Occupation;
use App\Major;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('occupation/{trajectoryName}', 'OccupationController@getOccupations');

Route::get('occupation_id={id}', 'OccupationController@getByIdWithMajorsAndSalary');

Route::get('major/all','MajorsController@getMajors');

Route::get('major_id={id}', 'MajorsController@getById');

Route::get("occupation_by_major_id={id}", "OccupationController@getOccupationsByMajorsId");

Route::get("curriculum_by_major_id={id}","CurriculumController@getCurriculumByMajor");

Route::get("get_statistic", "StatisticController@getStatisticData");

Route::post("send_statistic","StatisticController@store")-> middleware('cors');

Route::get("get_extra_subjects_for_chosen_majors={majors_array}", "Admin\OccupationsController@getExtraSubjectForChosenMajors");
