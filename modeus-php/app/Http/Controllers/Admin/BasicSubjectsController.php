<?php

namespace App\Http\Controllers\Admin;

use App\BasicSubjects;
use App\Curriculum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BasicSubjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("admin.subjects.manage_basic_subjects")->with('basic_subjects',BasicSubjects::all()->sortBy("id"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("admin.subjects.create_basic_subjects");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->name==NULL)
            return redirect()->route("admin.basic_subjects.index")->with("app_warning", "Дисциплину без имени нельзя создать");

        if($request->creditUnits==NULL)
            $credit_Units = 3;
        else
            $credit_Units = $request->creditUnits;

        $basic_subjects_data = new BasicSubjects([
            'name'     => $request->name,
            'description'  => $request->description,
            'creditUnits'  => $credit_Units,
            'formOfControl'  => $request->formOfControl
        ]);

        // проверка на дубликаты
        $count = BasicSubjects::query()->where('name', $request->name)
            ->where('creditUnits',  $credit_Units)
            ->where('formOfControl',  $request->formOfControl)
            ->count();

        if ($count==0){
            $basic_subjects_data->save();
        }
        else return redirect()->route("admin.basic_subjects.index")->with("app_warning", "Дисциплина уже существует");

        return redirect()->route("admin.basic_subjects.index")->with("success", "Дисциплина успешно добавлена");
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
        return view("admin.subjects.edit_basic_subjects")->with(['basic_subject'=> BasicSubjects::find($id)]);
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
        $basic_subject=BasicSubjects::find($id);

        if($request->name != NULL)
            $basic_subject->name=$request->name;

        if($request->description != NULL)
            $basic_subject->description=$request->description;

        if($request->creditUnits != NULL)
            $basic_subject->creditUnits=$request->creditUnits;

        if($request->formOfControl != NULL)
            $basic_subject->formOfControl=$request->formOfControl;

        $basic_subject->save();
        return redirect()->route("admin.basic_subjects.index")->with("success", "Дисциплина успешно изменена");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // удаление ссылок на элементы учебного плана
        for ($i=1; $i<9;$i++) {
            $curriculumRows = Curriculum::query()->whereNotNull("basic_subjects_ids_{$i}")->get();
            foreach ($curriculumRows as $curriculumRow) {
                $ids = $curriculumRow->getAttribute("basic_subjects_ids_{$i}");
                $columnName="basic_subjects_ids_{$i}";
                if ($ids != NULL) {
                    $basicSubjectsIds = explode(",", $ids);
                    if (in_array($id, $basicSubjectsIds)) {
                        $basicSubjectsIds = array_diff($basicSubjectsIds, [$id]);
                        $basicSubjectsIds = implode(",", $basicSubjectsIds);
                        $curriculumRow->$columnName = $basicSubjectsIds;
                        $curriculumRow->save();
                    }
                }
            }
        }

        BasicSubjects::destroy($id);
        return redirect()->route("admin.basic_subjects.index")->with("app_warning", "Дисциплина была успешно удалена");
    }
}
