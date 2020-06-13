<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    protected $table = 'salary';
    protected $fillable = ['id', 'average', 'stats', 'highest', 'year_of_experience', 'without_experience','occupation_id'];
    protected $hidden = ['pivot'];
    public $timestamps=false;
    protected $primaryKey = 'id';
    public $incrementing = true;

    public function occupation()
    {
        $this->belongsTo('App\Occupation');
    }
}
