<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trajectory extends Model
{
    protected $table = 'trajectory';
    protected $fillable = ['id', 'name', 'occupations'];
    public function occupations()
    {
        return $this->hasMany('App\Occupation');
    }
}
