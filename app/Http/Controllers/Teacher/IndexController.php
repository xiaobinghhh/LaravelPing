<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    //教师首页
    public function index()
    {
        return view('teacher.index');
    }
}
