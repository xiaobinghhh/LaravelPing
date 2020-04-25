<?php

namespace App\Http\Controllers\Student;

use App\Application\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CourseController extends Controller
{
    public function welcome(Course $course)
    {
        $data = ['rate' => [], 'signment' => [], 'homework' => [], 'report' => [], 'final_exam' => []];//返回的结果数据
        $student_no = session('userInfo')['no'];//获取学号
        $student = $course->students()->where('no', $student_no)->first();//获取该学生
        $signment = $student->signments()->where('course_no', $course->no)->first();//获取该生的该课程的签到记录
        //找到相应签到记录
        if ($signment) {
            $sign = str_split($signment->sign_data);
            $data['signment'] = ['sign_data' => $sign, 'sign_score' => $signment->sign_score];
            //计算出勤率
            $_1_cnt = 0;//出勤次数

            $cnt = count($sign);//签到总次数
            for ($i = 0; $i < $cnt; $i++) {
                if ($sign[$i] == 1) $_1_cnt++;
            }
            array_push($data['rate'], ['arrive_rate' => $_1_cnt / $cnt]);//存入出勤率
        }//找不到相应签到记录
        else {
            $data['signment'] = ['sign_data' => '无签到记录', 'sign_score' => 0];
            array_push($data['rate'], ['arrive_rate' => 0]);//存入出勤率0
        }
        $homeworks = $course->homeworks()->get();//获得课程的作业
        //有作业
        $finish_cnt = 0;//完成作业数
        if ($homeworks) {
            foreach ($homeworks as $homework) {
                $h_commit = $homework->commits()->where('student_no', $student_no)->first();//获得该生的该课程的作业提交记录
                //找到作业提交
                if ($h_commit) {
                    //存入该作业提交情况
                    array_push($data['homework'], [$homework->name => ['homework_score' => $h_commit->homework_score]]);
                    $finish_cnt++;
                } else {
                    //存入该作业未完成
                    array_push($data['homework'], [$homework->name => '未完成']);
                }
            }
            array_push($data['rate'], ['finish_rate' => $finish_cnt / count($homeworks)]);
        } else {
            $data['homework'] = null;
            array_push($data['rate'], ['finish_rate' => 0]);
        }

        $reports = $course->reports()->get();
        $commit_cnt = 0;//报告提交数
        if (count($reports) != 0) {
            foreach ($reports as $report) {
                $r_commit = $report->commits()->where('student_no', $student_no)->first();//获得该生的该课程的报告提交记录
                //找到报告提交
                if ($r_commit) {
                    //存入该报告提交情况
                    $data['report'] = [$report->name => ['report_score' => $r_commit->report_score]];
                } else {
                    //存入该报告未完成
                    $data['report'] = [$report->name => '未完成'];
                }
            }
            array_push($data['rate'], ['commit_rate' => $commit_cnt / count($reports)]);
        } else {
            $data['report'] = null;
            array_push($data['rate'], ['commit_rate' => 0]);
        }

        $final_exam = $student->final_exam()->where('course_no', $course->no)->first();//获得该生的该课程的期末考试记录
        //找到该生该课程的期末考试记录
        if ($final_exam) {
            $data['final_exam'] = ['final_exam_score' => $final_exam->final_exam_score];
        }//没找到该生该课程的期末考试记录
        else {
            $data['final_exam'] = ['final_exam_score' => 0];
        }

//        dd($data);
        return view('student.course_welcome', compact('course', 'data'));
    }
}
