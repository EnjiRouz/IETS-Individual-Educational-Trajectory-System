<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Curriculum extends Model
{
    public $timestamps=false;
    protected $primaryKey = 'curriculum_id';
    public $incrementing = true;

    protected $table = 'curriculum';
    protected $fillable = ['curriculum_id', 'basic_subjects_ids_1','basic_subjects_ids_2','basic_subjects_ids_3',
                            'basic_subjects_ids_4','basic_subjects_ids_5','basic_subjects_ids_6','basic_subjects_ids_7',
                            'basic_subjects_ids_8','extra_subjects_ids'];
    protected $hidden = ['pivot'];

    public function curriculum()
    {
        return $this->belongsToMany('App\Curriculum', 'curriculum');
    }
}
