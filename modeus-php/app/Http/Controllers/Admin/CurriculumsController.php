<?php

namespace App\Http\Controllers\Admin;

use App\BasicSubjects;
use App\Curriculum;
use App\ExtraSubjects;
use App\Http\Controllers\Controller;
use App\Major;
use Illuminate\Http\Request;

class CurriculumsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("admin.subjects.manage_curriculums")->with('majors',Major::all()->sortBy("name"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view("admin.subjects.edit_curriculum")->with(['curriculum'=> Curriculum::find($id)])
            ->with('basic_subjects',BasicSubjects::all()->sortBy("name"))
            ->with('extra_subjects',ExtraSubjects::all()->sortBy("name"))
            ->with(['major'=> Major::query()->where('curriculum_id', '=', $id)->first()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $curriculum=Curriculum::find($id);

        // добавление информации о базовых дисциплинах
        $curriculum->basic_subjects_ids_1=(($request->basic_subjects_1!=NULL) ? implode(",", $request->basic_subjects_1) : '');
        $curriculum->basic_subjects_ids_2=(($request->basic_subjects_2!=NULL) ? implode(",", $request->basic_subjects_2) : '');
        $curriculum->basic_subjects_ids_3=(($request->basic_subjects_3!=NULL) ? implode(",", $request->basic_subjects_3) : '');
        $curriculum->basic_subjects_ids_4=(($request->basic_subjects_4!=NULL) ? implode(",", $request->basic_subjects_4) : '');
        $curriculum->basic_subjects_ids_5=(($request->basic_subjects_5!=NULL) ? implode(",", $request->basic_subjects_5) : '');
        $curriculum->basic_subjects_ids_6=(($request->basic_subjects_6!=NULL) ? implode(",", $request->basic_subjects_6) : '');
        $curriculum->basic_subjects_ids_7=(($request->basic_subjects_7!=NULL) ? implode(",", $request->basic_subjects_7) : '');
        $curriculum->basic_subjects_ids_8=(($request->basic_subjects_8!=NULL) ? implode(",", $request->basic_subjects_8) : '');

        // добавление информации о дополнительных дисциплинах
        $curriculum->extra_subjects_ids=(($request->extra_subjects!=NULL) ? implode(",", $request->extra_subjects) : '');

        $curriculum->save();

        return redirect()->route("admin.curriculum.index")->with("success", "Программа направления обучения успешно изменена");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
