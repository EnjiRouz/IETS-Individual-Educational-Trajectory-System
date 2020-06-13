<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Statistic extends Model
{
    protected $table = 'statistics';
    protected $fillable = ['id', 'course', 'chosen_major', 'chosen_profession', 'user_choices'];
    protected $hidden = ['pivot'];
}
