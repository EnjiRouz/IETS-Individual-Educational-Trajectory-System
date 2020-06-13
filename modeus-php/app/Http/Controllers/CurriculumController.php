<?php

namespace App\Http\Controllers;

use App\BasicSubjects;
use App\Curriculum;
use App\ExtraSubjects;
use App\Major;
use Illuminate\Http\Request;
use App\Trajectory;

class CurriculumController extends Controller
{
    /**
     * Получение дисциплин учебного плана для создания таблицы с базовыми дисциплинами и дополнительными курсами
     * @param $id - id направления обчуения
     * @return array - список предметов учебного плана, разделенный по категориям
     */
    public function getCurriculumByMajor($id)
    {
        // получение id  курсов и дисциплин учебного плана
        $curriculumId= Major::query()->where('id', '=', $id)->get(['curriculum_id'])->first()->getAttribute('curriculum_id');
        $curriculum= Curriculum::query()->where('curriculum_id', '=', $curriculumId)->get();

        // разбиение базовых дисциплин на семестры
        $basicSubjects=array();
        for($i=1; $i<9;$i++){
            // для получения полной информации о базовых дисциплинах можно раскомментировать строку ниже и закомментировать активную
            //$basicSubjects[$i.'semester']=$this->getBasicSubjectsForSemester($curriculum, $i);
            array_push($basicSubjects,$this->getBasicSubjectsForSemesterShortForm($curriculum, $i));
        }

        // получение дополнительных курсов
        $extraSubjects=$this->getExtraSubjectsForSemester($curriculum);

        // создание структуры json
        $curriculumSubjects=array();
        $curriculumSubjects['basics']=$basicSubjects;
        $curriculumSubjects['extras']=$extraSubjects;

        return $curriculumSubjects;
    }

    /**
     * Получение списка базовых дисциплин для каждого семестра в упрощённой форме
     * @param $curriculum - данные учебного плана
     * @param $i - номер семестра
     * @return array - массив с информацией о базовых предметах семестра
     */
    public function getBasicSubjectsForSemesterShortForm($curriculum, $i): array
    {
        $basicSubjectsIds = explode(",", ($curriculum->first()->getAttribute("basic_subjects_ids_{$i}")));

        $subjectsNames="";
        foreach ($basicSubjectsIds as $subjectId) {
            $subjectsNames=$subjectsNames."• ".BasicSubjects::query()->where('id', '=', $subjectId)->first()->getAttribute("name")."\n";
        }

        $basicSubjectsForSemester = array();
        $basicSubjectsForSemester['subjects']=$subjectsNames;
        return $basicSubjectsForSemester;
    }

    /**
     * Получение списка базовых дисциплин для каждого семестра
     * @param $curriculum - данные учебного плана
     * @param $i - номер семестра
     * @return array - массив с информацией о базовых предметах семестра
     */
    public function getBasicSubjectsForSemester($curriculum, $i): array
    {
        $ids=$curriculum->first()->getAttribute("basic_subjects_ids_{$i}");
        if($ids==NULL) return array();

        $basicSubjectsIds = explode(",", $ids);

        $basicSubjectsForSemester = array();
        foreach ($basicSubjectsIds as $subjectId) {
            array_push($basicSubjectsForSemester, BasicSubjects::query()->where('id', '=', $subjectId)->firstOrFail());
        }
        return $basicSubjectsForSemester;
    }

    /**
     * Получение списка базовых дисциплин для каждого семестра
     * @param $curriculum - данные учебного плана
     * @return array - массив с информацией о базовых предметах семестра
     */
    public function getExtraSubjectsForSemester($curriculum): array
    {
        $ids=$curriculum->first()->getAttribute("extra_subjects_ids");
        if($ids==NULL) return array();

        $extraSubjectsIds = explode(",",$ids);

        $extraSubjectsForSemester = array();
        foreach ($extraSubjectsIds as $subjectId) {
            array_push($extraSubjectsForSemester, ExtraSubjects::query()->where('id', '=', $subjectId)->first());
        }
        return $extraSubjectsForSemester;
    }
}
