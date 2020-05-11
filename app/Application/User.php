<?php

namespace App\Application;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    //定义关联的数据表
    protected $table = 'users';

    public $timestamps = false;

    //用户和学生一对一
    public function student()
    {
        return $this->hasOne('App\Application\Student', 'no', 'no');
    }

    //用户和教师一对一
    public function teacher()
    {
        return $this->hasOne('App\Application\Teacher', 'no', 'no');
    }
}
