<?php

namespace App\Http\Controllers;

use App\Occupation;
use App\OccupationMajor;
use Illuminate\Http\Request;
use App\Trajectory;

class OccupationController extends Controller
{
    public function getOccupations($trajectoryName)
    {
        $trajectoryId = Trajectory::query()->where('name', '=', str_replace('+', ' ', $trajectoryName))->first()->getAttribute('id');
        return Occupation::query()->where('trajectory_id', '=', $trajectoryId)->get();
    }

    public function getByIdWithMajorsAndSalary($id)
    {
        return Occupation::query()->where('id', '=', $id)->with('majors', 'salary')->first();
    }

    public function getOccupationsByMajorsId($id)
    {
        $occupations=array();
        $occupationIDs = OccupationMajor::query()->where('major_id', '=', $id)->get(['occupation_id']);

        foreach ($occupationIDs as $occupationID) {
            array_push($occupations, Occupation::query()->where('id', '=',  $occupationID->occupation_id)->firstOrFail());
        }

        return $occupations;
    }
}
