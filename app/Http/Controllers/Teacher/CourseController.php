<?php

namespace App\Http\Controllers\Teacher;

use App\Application\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    //课程欢迎页
    public function welcome(Course $course)
    {
        $students = $course->students()->get();//获取选修课程的学生
        $rate = [];
        //获取出勤率
        $_1 = 0;//到
        $_1_0 = 0;//签到总次数
        //遍历学生
        foreach ($students as $student) {
            $sign_data = $student->signments()->where('course_no', $course->no)->first()->sign_data;//签到数据
            $sign_data = str_split($sign_data);
            for ($i = 0; $i < count($sign_data); $i++) {
                $_1_0++;
                if ($sign_data[$i] == 1) $_1++;
            }
        }
        $rate['arrive_rate'] = (double)$_1 / (double)$_1_0;
        //获取作业完成率
        $finish_cnt = 0;
        $homeworks = $course->homeworks()->get();//课程作业数
        //有布置作业
        if (count($homeworks) != 0) {
            foreach ($homeworks as $homework) {
                $finish_cnt += $homework->commits()->count();//该作业完成数
            }
            $rate['finish_rate'] = (double)$finish_cnt / (double)(count($homeworks) * count($students));
        }//没布置作业
        else {
            $rate['finish_rate'] = 0;//没布置作业作业完成率为0%
        }
        //获取报告提交率
        $commit_cnt = 0;
        $reports = $course->reports()->get();//课程报告数
        if (count($reports) != 0) {
            foreach ($reports as $report) {
                $commit_cnt += $report->commits()->count();//该报告提交数
            }
            $rate['commit_rate'] = (double)$commit_cnt / (double)(count($reports) * count($students));
        } else {
            $rate['commit_rate'] = 0;
        }
        //获取期末考试及格率
        $pass_cnt = 0;
        foreach ($students as $student) {
            $final_exam = $student->final_exam()->where('course_no', $course->no)->first();//获取学生该课程的期末考试记录
            //期末考试记录存在,且成绩不小于60分，及格人数加一
            if ($final_exam) {
                if ($final_exam->final_exam_score >= 60) {
                    $pass_cnt++;
                }
            }
        }
        $rate['pass_rate'] = (double)$pass_cnt / (double)count($students);

        /***************************获取数据华丽丽的分割线**********************************/

        $data = [];
        //获取每个学生的各项成绩
        foreach ($students as $student) {
            $sign_score = $student->signments()->where('course_no', $course->no)->first()->sign_score;//签到成绩
            $homeworks = $course->homeworks()->get();//该课程所有作业
            $homework_score = 0;
            //遍历作业
            foreach ($homeworks as $homework) {
                //检查作业提交中该生的提交的成绩
                $commit = $homework->commits()->where('student_no', $student->no)->first();
                //该生有提交
                if ($commit) {
                    $commit_score = $commit->homework_score;
                } //该生未提交，该作业记0分
                else {
                    $commit_score = 0;
                }
                $homework_score += $commit_score;
            }
            //该老师有布置作业
            if (count($homeworks) != 0) {
                $homework_score = $homework_score / count($homeworks);//求得该生合计作业成绩
            } //否则学生无需提交作业，该项暂记为100分
            else {
                $homework_score = 0;
            }
            $reports = $course->reports()->get();//该课程所有报告
            $report_score = 0;
            //遍历报告
            foreach ($reports as $report) {
                //检查报告提交中该生的提交的成绩
                $commit = $report->commits()->where('student_no', $student->no)->first();
                //该生有提交
                if ($commit) {
                    $commit_score = $commit->report_score;
                } //该生未提交，该作业记0分
                else {
                    $commit_score = 0;
                }
                $report_score += $commit_score;
            }
            //该老师有要求报告任务
            if (count($reports) != 0) {
                $report_score = $report_score / count($reports);//求得该生合计报告成绩
            } //否则学生无需提交报告，该项暂记为0分
            else {
                $report_score = 0;
            }
            $_final_exam = $student->final_exam()->where('course_no', $course->no)->first();
            if ($_final_exam) $final_exam_score = $_final_exam->final_exam_score;//期末考试成绩
            else $final_exam_score = 0;
            $basiss = $course->basis()->get();//获得该课程设置的评分项,准备计算加权总成绩
            $total_score = 0;
            foreach ($basiss as $basis) {
                switch ($basis->name) {
                    case 'signment':
                        $_sign_score = $sign_score * $basis->weight / 100;
                        $total_score += $_sign_score;
                        break;
                    case 'homework':
                        $_homework_score = $homework_score * $basis->weight / 100;
                        $total_score += $_homework_score;
                        break;
                    case 'report':
                        $_report_score = $report_score * $basis->weight / 100;
                        $total_score += $_report_score;
                        break;
                    case 'final_exam':
                        $_final_exam_score = $final_exam_score * $basis->weight / 100;
                        $total_score += $_final_exam_score;
                        break;
                }
            }
            $data[$student->name] = [
                'student_no' => $student->no,
                'sign_score' => $sign_score,
                'homework_score' => $homework_score,
                'report_score' => $report_score,
                'final_exam_score' => $final_exam_score,
                'total_score' => $total_score,
            ];
        }
        return view('teacher.course_welcome', compact('rate', 'data', 'course'));
    }
}

