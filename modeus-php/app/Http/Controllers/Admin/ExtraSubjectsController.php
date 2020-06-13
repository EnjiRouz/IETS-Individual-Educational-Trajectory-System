<?php

namespace App\Http\Controllers\Admin;

use App\Curriculum;
use App\ExtraSubjects;
use App\Http\Controllers\Controller;
use App\Occupation;
use DB;
use Illuminate\Http\Request;

class ExtraSubjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // TODO показывать существующие категории либо давать возможность написать свою
        return view("admin.subjects.manage_extra_subjects")->with('extra_subjects',ExtraSubjects::all()->sortBy("id"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("admin.subjects.create_extra_subjects");
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
            return redirect()->route("admin.extra_subjects.index")->with("app_warning", "Дисциплину без имени нельзя создать");

        if($request->category==NULL)
            $category= "Программирование";
        else
            $category = $request->category;

        if($request->creditUnits==NULL)
            $credit_Units = 3;
        else
            $credit_Units = $request->creditUnits;

        $extra_subjects_data = new ExtraSubjects([
            'name'     => $request->name,
            'description'  => $request->description,
            'creditUnits'  => $credit_Units,
            'category'  => $category,
            'formOfControl'  => $request->formOfControl
        ]);

        // проверка на дубликаты
        $count = ExtraSubjects::query()->where('name', $request->name)
            ->where('category',  $category)
            ->where('creditUnits',  $credit_Units)
            ->where('formOfControl',  $request->formOfControl)
            ->count();

        if ($count==0){
            $extra_subjects_data->save();
        }
        else return redirect()->route("admin.extra_subjects.index")->with("app_warning", "Дисциплина уже существует");

        return redirect()->route("admin.extra_subjects.index")->with("success", "Дисциплина успешно добавлена");
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
        return view("admin.subjects.edit_extra_subjects")->with(['extra_subject'=> ExtraSubjects::find($id)]);
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
        $extra_subject=ExtraSubjects::find($id);

        if($request->name != NULL)
            $extra_subject->name=$request->name;

        if($request->description != NULL)
            $extra_subject->description=$request->description;

        if($request->creditUnits != NULL)
            $extra_subject->creditUnits=$request->creditUnits;

        if($request->category != NULL)
            $extra_subject->category=$request->category;

        if($request->formOfControl != NULL)
            $extra_subject->formOfControl=$request->formOfControl;

        $extra_subject->save();
        return redirect()->route("admin.extra_subjects.index")->with("success", "Дисциплина успешно изменена");
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
        $curriculumRows=Curriculum::query()->whereNotNull("extra_subjects_ids")->get();
        foreach ($curriculumRows as $curriculumRow){
            $ids=$curriculumRow->getAttribute("extra_subjects_ids");
            if($ids!=NULL) {
                $extraSubjectsIds = explode(",", $ids);
                if (in_array($id,$extraSubjectsIds)) {
                    $extraSubjectsIds=array_diff($extraSubjectsIds,[$id]);
                    $extraSubjectsIds = implode(",", $extraSubjectsIds);
                    $curriculumRow->extra_subjects_ids = $extraSubjectsIds;
                    $curriculumRow->save();
                }
            }
        }

        // удаление ссылок на элементы пресетов профессий
        $occupationRows=Occupation::query()->whereNotNull("occupation_extra_subjects_preset")->get();
        foreach ($occupationRows as $occupationRow){
            $ids=$occupationRow->getAttribute("occupation_extra_subjects_preset");
            if($ids!=NULL) {
                $extraSubjectsIds = explode(",", $ids);
                if (in_array($id,$extraSubjectsIds)) {
                    $extraSubjectsIds=array_diff($extraSubjectsIds,[$id]);
                    $extraSubjectsIds = implode(",", $extraSubjectsIds);
                    $occupationRow->occupation_extra_subjects_preset = $extraSubjectsIds;
                    $occupationRow->save();
                }
            }
        }

        ExtraSubjects::destroy($id);
        return redirect()->route("admin.extra_subjects.index")->with("app_warning", "Дисциплина была успешно удалена");
    }
}
