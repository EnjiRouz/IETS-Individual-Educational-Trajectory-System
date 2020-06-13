<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BasicSubjects extends Model
{
    protected $table = 'basic_subjects';
    protected $fillable = ['id', 'name','description','formOfControl', 'creditUnits'];
    protected $hidden = ['pivot'];
    public $timestamps=false;
    protected $primaryKey = 'id';
    public $incrementing = true;

    public function basicSubjects()
    {
        return $this->belongsToMany('App\BasicSubjects', 'basic_subjects');
    }

    public function curriculums()
    {
        return $this->belongsToMany('App\Curriculum', 'curriculum');
    }

    /**
     * Проверка на наличие дисциплины в учебном плане
     * @param $curriculum_id - id учебного плана
     * @param $subjectId - id дисциплины
     * @param $semester - номер семестра
     * @return bool - true, если дисциплина находится в учебном плане
     */
    public function isInCurriculum($curriculum_id, $subjectId, $semester){
        $curriculum= Curriculum::query()->where('curriculum_id', '=', $curriculum_id)->get();
        $ids=$curriculum->first()->getAttribute("basic_subjects_ids_{$semester}");
        if($ids==NULL) return null;
        $basicSubjectsIds = explode(",", ($ids));
        return (in_array($subjectId, $basicSubjectsIds));
    }
}

