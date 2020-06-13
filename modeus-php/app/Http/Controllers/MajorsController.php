<?php

namespace App\Http\Controllers;

use App\Major;
use App\Occupation;
use Illuminate\Http\Request;
use App\Trajectory;

class MajorsController extends Controller
{
    public function getMajors()
    {
        return Major::all();
    }

    public function getById($id)
    {
        return Major::query()->where('id', '=', $id)->first();
    }
}
