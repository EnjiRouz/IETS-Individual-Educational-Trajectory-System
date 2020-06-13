<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Occupation extends Model
{
    protected $table = 'occupation';
    protected $fillable = ['id', 'name', 'description', 'occupation_extra_subjects_preset'];
    protected $hidden = ['trajectory_id'];
    public $timestamps=false;
    protected $primaryKey = 'id';
    public $incrementing = true;

    public function trajectory()
    {
        return $this->belongsTo('App\Trajectory');
    }
    public function salary()
    {
        return $this->hasOne('App\Salary');
    }
    public function majors()
    {
        return $this->belongsToMany('App\Major', 'occupation_major');
    }
    public function hasAnyMajor($majorId){
        return null != $this->majors()->whereIn("major_id", $majorId)->first();
    }
}
