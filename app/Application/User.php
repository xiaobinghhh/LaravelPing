<?php

namespace App\Application;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    //定义关联的数据表
    protected $table='users';
}
