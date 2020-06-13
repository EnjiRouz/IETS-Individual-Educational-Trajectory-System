<?php

namespace App\Http\Controllers;

use App\Major;
use App\OccupationMajor;
use App\Occupation;
use Illuminate\Http\Request;
use App\Trajectory;

class OccupationMajorController extends Controller
{
    public function getOccupationMajors()
    {
        return OccupationMajor::all();
    }

    public function getOccupationsByMajorId($id)
    {
        return OccupationMajor::query()->where('major_id', '=', $id)->get(['occupation_id']);
    }
}
