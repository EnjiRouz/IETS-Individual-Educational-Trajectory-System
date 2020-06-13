<?php

namespace App\Http\Controllers;

use App\Statistic;
use DB;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        if($request->profession==="Выберите профессию")
            $chosen_profession = '';
        else
            $chosen_profession = $request->profession;

        $statistic = new Statistic([
        'course'            => $request->course,
        'chosen_major'      => $request->chosen_major,
        'chosen_profession' => $chosen_profession,
        'user_choices'      => $request->user_choices,
        ]);

        // проверка на дубликаты
        $count = Statistic::query()->where('chosen_major', $request->chosen_major)
            ->where('chosen_profession',  $chosen_profession)
            ->where('user_choices',  $request->user_choices)
            ->where('course',  $request->course)
            ->count();

        if ($count==0){
            $statistic->save();
        }
        return response()->noContent();
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
        //
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
        //
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

    /**
     * Получение JSON, содержащего статистику по выбранным дополнительным дисциплинам, направлениям и профессиям
     * @return array - JSON
     */
    public function getStatisticData()
    {
        // получение статистики выборов дополнительных дисциплин
        $chosen_extra_subjects_statistic = $this->getStatisticByColumnName('user_choices');
        $chosen_extra_subjects_statistic_entrant = $this->getStatisticByColumnNameForCourse('user_choices',"Абитуриент");
        $chosen_extra_subjects_statistic_1_course = $this->getStatisticByColumnNameForCourse('user_choices',"1 курс");
        $chosen_extra_subjects_statistic_2_course = $this->getStatisticByColumnNameForCourse('user_choices',"2 курс");
        $chosen_extra_subjects_statistic_3_course = $this->getStatisticByColumnNameForCourse('user_choices',"3 курс");
        $chosen_extra_subjects_statistic_4_course = $this->getStatisticByColumnNameForCourse('user_choices',"4 курс");


        // получение статистики выборов направлений обучения
        $chosen_majors_statistic = $this->getStatisticByColumnName('chosen_major');
        $chosen_majors_statistic_entrant = $this->getStatisticByColumnNameForCourse('chosen_major',"Абитуриент");
        $chosen_majors_statistic_1_course = $this->getStatisticByColumnNameForCourse('chosen_major',"1 курс");
        $chosen_majors_statistic_2_course = $this->getStatisticByColumnNameForCourse('chosen_major',"2 курс");
        $chosen_majors_statistic_3_course = $this->getStatisticByColumnNameForCourse('chosen_major',"3 курс");
        $chosen_majors_statistic_4_course = $this->getStatisticByColumnNameForCourse('chosen_major',"4 курс");


        // получение статистики выборов профессий
        $chosen_professions_statistic = $this->getStatisticByColumnName('chosen_profession');
        $chosen_professions_statistic_entrant = $this->getStatisticByColumnNameForCourse('chosen_profession',"Абитуриент");
        $chosen_professions_statistic_1_course = $this->getStatisticByColumnNameForCourse('chosen_profession',"1 курс");
        $chosen_professions_statistic_2_course = $this->getStatisticByColumnNameForCourse('chosen_profession',"2 курс");
        $chosen_professions_statistic_3_course = $this->getStatisticByColumnNameForCourse('chosen_profession',"3 курс");
        $chosen_professions_statistic_4_course = $this->getStatisticByColumnNameForCourse('chosen_profession',"4 курс");

        // создание структуры json
        $statistic_to_send=array();
        $statistic_to_send['chosen_extra_subjects']=$chosen_extra_subjects_statistic;
        $statistic_to_send['chosen_extra_subjects_entrant']=$chosen_extra_subjects_statistic_entrant;
        $statistic_to_send['chosen_extra_subjects_1_course']=$chosen_extra_subjects_statistic_1_course;
        $statistic_to_send['chosen_extra_subjects_2_course']=$chosen_extra_subjects_statistic_2_course;
        $statistic_to_send['chosen_extra_subjects_3_course']=$chosen_extra_subjects_statistic_3_course;
        $statistic_to_send['chosen_extra_subjects_4_course']=$chosen_extra_subjects_statistic_4_course;

        $statistic_to_send['chosen_majors']=$chosen_majors_statistic;
        $statistic_to_send['chosen_majors_entrant']=$chosen_majors_statistic_entrant;
        $statistic_to_send['chosen_majors_1_course']=$chosen_majors_statistic_1_course;
        $statistic_to_send['chosen_majors_2_course']=$chosen_majors_statistic_2_course;
        $statistic_to_send['chosen_majors_3_course']=$chosen_majors_statistic_3_course;
        $statistic_to_send['chosen_majors_4_course']=$chosen_majors_statistic_4_course;

        $statistic_to_send['chosen_professions']=$chosen_professions_statistic;
        $statistic_to_send['chosen_professions_entrant']=$chosen_professions_statistic_entrant;
        $statistic_to_send['chosen_professions_1_course']=$chosen_professions_statistic_1_course;
        $statistic_to_send['chosen_professions_2_course']=$chosen_professions_statistic_2_course;
        $statistic_to_send['chosen_professions_3_course']=$chosen_professions_statistic_3_course;
        $statistic_to_send['chosen_professions_4_course']=$chosen_professions_statistic_4_course;

        return $statistic_to_send;
    }

    /**
     * Возвращает топ-5 самых популярных элементов заданного столбца
     * @param $column_name - название столбца
     * @param int $top_length - количество элементов в топе
     * @return array - сортированный лист статистики
     */
    public function getStatisticByColumnName($column_name, $top_length=5): array
    {
        $chosen_things= Statistic::query()->pluck($column_name);
        $chosen_things = $chosen_things->map(function ($item) {
            return explode(',', $item);
        })->flatten()->all();
        $chosen_things_statistic=array_count_values($chosen_things);
        arsort($chosen_things_statistic);
        $chosen_things_statistic = array_slice($chosen_things_statistic, 0, $top_length, true);
        $chosen_things_statistic["Прочие"] = 1;

        return $chosen_things_statistic;
    }

    /**
     * Возвращает топ-5 самых популярных элементов заданного столбца
     * @param $column_name - название столбца
     * @param $course_name - номер курса, пометка абитуриента
     * @param int $top_length - количество элементов в топе
     * @return array - сортированный лист статистики
     */
    public function getStatisticByColumnNameForCourse($column_name, $course_name, $top_length=5): array
    {
        $chosen_things= Statistic::query()->where('course', '=', $course_name)->pluck($column_name);
        $chosen_things = $chosen_things->map(function ($item) {
            return explode(',', $item);
        })->flatten()->all();
        $chosen_things_statistic=array_count_values($chosen_things);
        arsort($chosen_things_statistic);
        $chosen_things_statistic = array_slice($chosen_things_statistic, 0, $top_length, true);
        $chosen_things_statistic["Прочие"] = 1;

        return $chosen_things_statistic;
    }

}
