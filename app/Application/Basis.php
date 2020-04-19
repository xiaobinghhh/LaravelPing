<?php

namespace App\Application;

use Illuminate\Database\Eloquent\Model;

class Basis extends Model
{
    //定义关联的数据表
    protected $table = 'basis';
    public $timestamps = false;

    //多个评分依据对应一门课程
    public function course()
    {
        return $this->belongsTo('App\Application\Course', 'course_no', 'no');
    }

}
