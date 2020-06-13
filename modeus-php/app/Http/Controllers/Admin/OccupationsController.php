<?php

namespace App\Http\Controllers\Admin;

use App\Curriculum;
use App\ExtraSubjects;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CurriculumController;
use App\Major;
use App\Occupation;
use App\OccupationMajor;
use App\Salary;
use DB;
use Illuminate\Http\Request;

class OccupationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("admin.subjects.manage_occupations")->with('occupations',Occupation::all()->sortBy("id"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("admin.subjects.create_occupations")->with('majors', Major::all()->sortBy("name"));
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
            return redirect()->route("admin.occupations.index")->with("app_warning", "Профессию без имени нельзя создать");

        if($request->extras!=NULL)
            $extraSubjectsIds=implode(",", array_slice($request->extras,0,12));
        else
            $extraSubjectsIds='';

        // проверка на дубликаты
        $count = Occupation::query()->where('name', $request->name)
            ->where('occupation_extra_subjects_preset',  $extraSubjectsIds)
            ->count();

        if ($count==0) {
            $chosenMajorsIds=$request->majors;
            if ($chosenMajorsIds==NULL)
                return redirect()->route("admin.occupations.index")->with("app_warning", "Не выбраны направления обучения");

            $occupations_data = new Occupation([
                'name' => $request->name,
                'description' => $request->description,
                'occupation_extra_subjects_preset' => $extraSubjectsIds
            ]);

            $occupations_data->save();
            $occupationId=Occupation::query()->where('name', $request->name)
                ->where('occupation_extra_subjects_preset',  $extraSubjectsIds)->first()->getAttribute("id");

            $average=(($request->average!=NULL) ? $request->average : 50000);
            $highest=(($request->highest!=NULL) ? $request->highest : 70000);
            $year_of_experience=(($request->year_of_experience!=NULL) ? $request->year_of_experience : 40000);
            $without_experience=(($request->without_experience!=NULL) ? $request->without_experience : 20000);
            $stats=(($request->stats!=NULL) ? $request->stats : 'Не удалось загрузить график');

            $occupation_salary=new Salary([
                'occupation_id'=> $occupationId,
                'average'=> $average,
                'highest' => $highest,
                'year_of_experience' => $year_of_experience,
                'without_experience'=>$without_experience,
                'stats'=> $stats
            ]);
            $occupation_salary->save();

            foreach ($chosenMajorsIds as $majorId) {
                DB::table('occupation_major')->insert([
                    'major_id' => $majorId,
                    'occupation_id'=>$occupationId
                ]);
            }
        }
        else return redirect()->route("admin.occupations.index")->with("app_warning", "Профессия уже существует");

        return redirect()->route("admin.occupations.index")->with("success", "Профессия успешно добавлена");
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
        return view("admin.subjects.edit_occupations")->with(['occupation'=> Occupation::find($id)])
            ->with('majors', Major::all()->sortBy("name"))
            ->with('salary', Salary::query()->where('occupation_id', '=', $id)->first());
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
        $occupation=Occupation::find($id);

        if($request->name != NULL)
            $occupation->name=$request->name;

        if($request->description != NULL)
            $occupation->description=$request->description;

        if($request->extras != NULL){
            $extraSubjectsIds=implode(",", array_slice($request->extras,0,12));
            $occupation->occupation_extra_subjects_preset=$extraSubjectsIds;
        }
        $occupation->save();

        $current_salary=Salary::query()->where('occupation_id', '=', $id)->first();

        $average=(($request->average!=NULL) ? $request->average : $current_salary->average);
        $highest=(($request->highest!=NULL) ? $request->highest : $current_salary->highest);
        $year_of_experience=(($request->year_of_experience!=NULL) ? $request->year_of_experience : $current_salary->year_of_experience);
        $without_experience=(($request->without_experience!=NULL) ? $request->without_experience : $current_salary->without_experience);
        $stats=(($request->stats!=NULL) ? $request->stats : $current_salary->stats);

        $occupation_salary=new Salary([
            'occupation_id'=> $id,
            'average'=> $average,
            'highest' => $highest,
            'year_of_experience' => $year_of_experience,
            'without_experience'=>$without_experience,
            'stats'=> $stats
        ]);

        // обновление связей направлений с профессиями
        Salary::query()->where('occupation_id', '=', $id)->delete();
        OccupationMajor::query()->where('occupation_id', '=', $id)->delete();

        $occupation_salary->save();

        $chosenMajorsIds=$request->majors;
        if ($chosenMajorsIds!=NULL) {
            foreach ($chosenMajorsIds as $majorId) {
                DB::table('occupation_major')->insert([
                    'major_id' => $majorId,
                    'occupation_id' => $id
                ]);
            }
        }

        return redirect()->route("admin.occupations.index")->with("success", "Профессия успешно изменена");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Salary::query()->where('occupation_id', '=', $id)->delete();
        OccupationMajor::query()->where('occupation_id', '=', $id)->delete();
        Occupation::destroy($id);
        return redirect()->route("admin.occupations.index")->with("app_warning", "Профессия была успешно удалена");
    }

    /**
     * Получение названий рекомендуемых курсов по их id
     * @param $extra_subjects_ids - id рекомендуемых курсов среди дополниетльных
     * @return string - список в виде строки
     */
    public static function getExtraSubjectsNames($extra_subjects_ids){
        if ($extra_subjects_ids == NULL)
            return "";

        $extraSubjectsIds = explode(",", $extra_subjects_ids);

        $extraSubjects = array();
        foreach ($extraSubjectsIds as $subjectId) {
            array_push($extraSubjects, ExtraSubjects::query()->where('id', '=', $subjectId)->first()->getAttribute("name"));
        }
        return implode(', ', $extraSubjects);
    }

    /**
     * Получение информации о дополнительных курсах, которые являются общими для заданных направлений
     * @param $majors_array - направления обучения
     * @return array - информация о курсах (JSON)
     */
    public function getExtraSubjectForChosenMajors($majors_array)
    {
        $majors_array=explode(",", $majors_array);
        $extraSubjectsIds=(string) NULL;

        // получение всех дополнительных дисциплин на направлениях
        foreach ($majors_array as $major_id) {
            $curriculumId = Major::query()->where('id', '=', $major_id)->get(['curriculum_id'])->first()->getAttribute('curriculum_id');
            $curriculum = Curriculum::query()->where('curriculum_id', '=', $curriculumId)->get();
            $extra_subjects_ids_for_major = $curriculum->first()->getAttribute("extra_subjects_ids");

            if ($extra_subjects_ids_for_major != NULL) {
                if ($extraSubjectsIds == NULL)
                    $extraSubjectsIds = $extra_subjects_ids_for_major;
                else
                    $extraSubjectsIds = $extraSubjectsIds . "," . $extra_subjects_ids_for_major;
            }
        }

        // фильтрация (поиск пересечений) дополнительных дисциплин
        $extraSubjectsIds=explode(",", $extraSubjectsIds);
        $extraSubjectsIds = array_keys(array_filter(array_count_values($extraSubjectsIds), function($v) use ($majors_array) {
            if(count($majors_array)>1)return $v > 1;
            else return $v > 0;
        }));

        // получение данных о дисциплинах, где есть пересечение для их дальнейшей отправки
        $extraSubjects = array();
        foreach ($extraSubjectsIds as $subjectId) {
            array_push($extraSubjects, ExtraSubjects::query()->where('id', '=', $subjectId)->first());
        }

        return $extraSubjects;
    }
}
