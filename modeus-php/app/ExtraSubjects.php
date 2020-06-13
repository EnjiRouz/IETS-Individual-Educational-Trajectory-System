<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExtraSubjects extends Model
{
    protected $table = 'extra_subjects';
    protected $fillable = ['id', 'name','description', 'creditUnits','category','formOfControl' ];
    protected $hidden = ['pivot'];
    public $timestamps=false;
    protected $primaryKey = 'id';
    public $incrementing = true;

    public function extraSubjects()
    {
        return $this->belongsToMany('App\ExtraSubjects', 'extra_subjects');
    }

    public function extras(){
        return $this->belongsToMany('App\ExtraSubjects');
    }

    public function curriculums()
    {
        return $this->belongsToMany('App\Curriculum', 'curriculum');
    }

    /**
     * Проверка на наличие дисциплины в учебном плане
     * @param $curriculum_id - id учебного плана
     * @param $subjectId - id дисциплины
     * @return bool - true, если дисциплина находится в учебном плане
     */
    public function isInCurriculum($curriculum_id, $subjectId){
        $curriculum= Curriculum::query()->where('curriculum_id', '=', $curriculum_id)->get();
        $ids=$curriculum->first()->getAttribute("extra_subjects_ids");
        if($ids==NULL) return null;
        $extraSubjectsIds = explode(",", ($ids));
        return (in_array($subjectId, $extraSubjectsIds));
    }
}
