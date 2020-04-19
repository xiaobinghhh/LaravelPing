<?php

namespace App\Http\Controllers\Teacher;

use App\Application\Course;
use App\Services\FinalExamUploadsManager;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FinalExamController extends Controller
{
    //签到依据时使用的管理工具
    protected $manager;

    //创建时注入管理工具依赖
    public function __construct(FinalExamUploadsManager $manager)
    {
        $this->manager = $manager;
    }

    //首页
    public function index(Course $course)
    {
        return view('teacher.final_exam.index', compact('course'));
    }

    public function list(Course $course)
    {
        //该课程的同学
        $students = $course->students()->get();
        $data = array();
        //遍历期末考试成绩表，获取每个同学的期末成绩记录
        foreach ($students as $student) {
            $_data = array(
                'student_no' => $student->no,
                'student_name' => $student->name,
            );
            //获得该生该课程期末成绩记录
            $final_exam = $student->final_exam()->where('course_no', '=', $course->no)->first();
            //期末成绩
            $_data['final_exam_score'] = $final_exam->final_exam_score;
            array_push($data, $_data);
        }
        return json_encode($data);
    }

    //编辑期末考试成绩
    public function edit(Request $request, Course $course)
    {
        //初始化返回结果
        $result = [
            'flag' => 1,
            'msg' => '修改成功',
        ];
        //Request中封装了传来的row数据
        //获得修改的成绩
        $final_exam_score = $request->input('final_exam_score');
        //成绩输入合法,0-100间的数字
        if (is_numeric($final_exam_score) && is_int((int)$final_exam_score) && $final_exam_score >= 0 && $final_exam_score <= 100) {
            //获取学号
            $student_no = $request->input('student_no');
            //找到该生
            $student = $course->students()->where('student_no', $student_no)->first();
            //找到该生该课程的期末考试记录
            $final_exam = $student->final_exam()->where('course_no', $course->no)->first();
            //修改了期末考试成绩
            if ($final_exam_score != $final_exam->final_exam_score) {
                //更新期末考试成绩
                $final_exam->final_exam_score = $final_exam_score;
                //更新
                $flag = $final_exam->save() ? 1 : 0;
                //更新结果
                $result['flag'] = $flag;
                //更新失败
                if ($flag == 0) {
                    $result['msg'] = '更新失败，请重试';
                }
            } else {
                $result = [
                    'flag' => 0,
                    'msg' => '成绩未作修改，请重试',
                ];
            }
        } //输入成绩不合法
        else {
            $result = [
                'flag' => 0,
                'msg' => '输入成绩不合法，请重试',
            ];
        }
        return json_encode($result);
    }

    public function file(Request $request, Course $course)
    {
        $folder = $request->get('folder');
        $data = $this->manager->folderInfo($folder);
        $data['course'] = $course;
        return view('teacher.final_exam.file_index', $data);
    }
}
