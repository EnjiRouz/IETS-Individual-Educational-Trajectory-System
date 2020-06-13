<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OccupationMajor extends Model
{
    protected $table = 'occupation_major';
    protected $fillable = ['occupation_id', 'major_id'];
    protected $hidden = ['pivot'];
    public $timestamps=false;

    public function occupations()
    {
        $this->belongsToMany('App\Occupation');
    }
    public function occupation_majors()
    {
        return $this->belongsToMany('App\OccupationMajor', 'occupation_major');
    }
}
