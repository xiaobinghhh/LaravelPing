<?php

namespace App\Http\Controllers\Student;

use App\Application\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    public function welcome(Course $course)
    {
        $data = ['rank' => [], 'rate' => [], 'score' => []];//返回的结果数据
        $student_no = session('userInfo')['no'];//获取学号
        $students = DB::table('student_course')->where('course_no', $course->no)->orderBy('course_no', 'desc')->get();
        $rank = 0;
        foreach ($students as $stu) {
            $rank++;
            if ($stu->student_no == $student_no) break;
        }
        if (count($students) != 0) $data['rank'] = $rank . "/" . count($students);
        else $data['rank'] = 0;
        $student = $course->students()->where('no', $student_no)->first();//获取该学生
        $signment = $student->signments()->where('course_no', $course->no)->first();//获取该生的该课程的签到记录
        //找到相应签到记录
        $signment_score = 0;//记录签到成绩
        if ($signment) {
            $sign = str_split($signment->sign_data);
            $signment_score = $signment->sign_score;//签到成绩
            //计算出勤率
            $_1_cnt = 0;//出勤次数
            $cnt = count($sign);//签到总次数
            for ($i = 0; $i < $cnt; $i++) {
                if ($sign[$i] == 1) $_1_cnt++;
            }
            $data['rate'] ['arrive_rate'] = $_1_cnt / $cnt;//存入出勤率
        }//找不到相应签到记录
        else {
            $signment_score = 0;
            $data['rate'] ['arrive_rate'] = 0;//存入出勤率0
        }

        $homeworks = $course->homeworks()->get();//获得课程的作业
        //有作业
        $finish_cnt = 0;//完成作业数
        $homework_score = 0;//作业成绩
        if ($homeworks) {
            foreach ($homeworks as $homework) {
                $h_commit = $homework->commits()->where('student_no', $student_no)->first();//获得该生的该课程的作业提交记录
                //找到作业提交
                if ($h_commit) {
                    $homework_score += $h_commit->homework_score;//作业评分累加
                    $finish_cnt++;
                }
            }
            $data['rate']['finish_rate'] = $finish_cnt / count($homeworks);//作业完成率
            $homework_score = $homework_score / count($homeworks);//作业平均成绩
        } else {
            $homework_score = 0;//报告成绩
            $data['rate']['finish_rate'] = 0;
        }

        $reports = $course->reports()->get();
        $commit_cnt = 0;//报告提交数
        $report_score = 0;//报告成绩
        if (count($reports) != 0) {
            foreach ($reports as $report) {
                $r_commit = $report->commits()->where('student_no', $student_no)->first();//获得该生的该课程的报告提交记录
                //找到报告提交
                if ($r_commit) {
                    $report_score += $r_commit->report_score;
                    $commit_cnt++;
                }
            }
            $data['rate'] ['commit_rate'] = $commit_cnt / count($reports);
            $report_score = $report_score / count($reports);//报告平均成绩
        } else {
            $report_score = 0;//报告成绩
            $data['rate']['commit_rate'] = 0;
        }

        $final_exam = $student->final_exam()->where('course_no', $course->no)->first();//获得该生的该课程的期末考试记录
        $final_exam_score = 0;//期末考试成绩
        //找到该生该课程的期末考试记录
        if ($final_exam) {
            $final_exam_score = $final_exam->final_exam_score;
        }//没找到该生该课程的期末考试记录
        else {
            $final_exam_score = 0;
        }
        $total_score = 0;//总成绩
        $basiss = $course->basis()->get();//获得该课程设置的评分项,准备计算加权总成绩
        $total_score = 0;
        foreach ($basiss as $basis) {
            switch ($basis->name) {
                case 'signment':
                    $_signment_score = $signment_score * $basis->weight / 100;
                    $total_score += $_signment_score;
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
        $data['score'] = [
            'signment_score' => $signment_score,
            'homework_score' => $homework_score,
            'report_score' => $report_score,
            'final_exam_score' => $final_exam_score,
            'total_score' => $total_score,
        ];

//        dd($data);

        return view('student.course_welcome', compact('course', 'homework_score', 'report_score', 'data'));
    }
}
