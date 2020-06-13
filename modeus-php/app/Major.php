<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    protected $table = 'major';
    protected $fillable = ['id', 'name','curriculum_id'];
    protected $hidden = ['pivot'];
    public function occupations()
    {
        $this->belongsToMany('App\Occupation');
    }
    public function majors()
    {
        return $this->belongsToMany('App\Major', 'major');
    }
}
