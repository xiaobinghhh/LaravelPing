<?php

namespace App\Http\Controllers\Teacher;

use App\Application\Course;
use App\Http\Controllers\Controller;

class ChartController extends Controller
{
    //签到图表统计
    public function signment(Course $course)
    {
        return view('teacher.signment.chart', compact('course'));
    }

    //签到统计柱状图的数据
    public function signment_chart_data(Course $course)
    {
        $data = [];//结果数据
        $students = $course->students()->get();//获得课程的学生
        $sign_data = array();//获取签到二维数组，每行对应一个学生的课程签到，每列对应一次课程签到每个学生的情况
        $i = 0;//循环每个学生
        $signment = [];//每个学生签到记录的缓存
        foreach ($students as $student) {
            $signment = $student->signments()->where('course_no', $course->no)->first();//获取该学生的课程签到记录
            $signment = str_split($signment->sign_data);//获取该生具体签到数据
            $sign_data[$i] = $signment;
            $i++;
        }
        $sign_cnt = count($signment);//签到次数
        //获取x轴坐标文字
        $categories = [];
        for ($i = 1; $i <= $sign_cnt; $i++) {
            array_push($categories, '第' . $i . '次签到');
        }
        //将xtext存入data中
        $data['xtext'] = json_encode($categories);
        //获取签到数据
        $seriesData = [];
        $data_1 = [];//出勤记录
        //初始化出勤记录
        for ($i = 0; $i < $sign_cnt; $i++) {
            $data_1[$i] = 0;
        }
        $data_0 = [];//缺勤记录
        //初始化缺勤记录
        for ($i = 0; $i < $sign_cnt; $i++) {
            $data_0[$i] = 0;
        }
        $data_1_cnt = [];//出勤人数
        $data_1_rate = [];//出勤率
        //初始化出勤人数
        for ($i = 0; $i < $sign_cnt; $i++) {
            $data_1_cnt[$i] = 0;
        }
        //遍历签到二维数组
        for ($i = 0; $i < count($sign_data); $i++) {
            for ($j = 0; $j < count($sign_data[$i]); $j++) {
                //为1出勤
                if ($sign_data[$i][$j] == 1) {
                    $data_1[$j]++;
                    $data_1_cnt[$j]++;
                } //为0缺勤
                else if ($sign_data[$i][$j] == 0) $data_0[$j]++;
            }
        }
        //确定出勤百分比
        for ($i = 0; $i < $sign_cnt; $i++) {
            $data_1_rate[$i] = (double)$data_1_cnt[$i] / (double)count($students) * 100;
        }
        //插入出勤数据
        array_push($seriesData, ['name' => '出勤', 'color' => '#28a745', 'data' => $data_1]);
        //插入缺勤数据
        array_push($seriesData, ['name' => '缺勤', 'color' => '#6c757d', 'data' => $data_0]);
        $data['column_series'] = json_encode($seriesData);
        $line_series = ['name' => '出勤百分比', 'color' => '#17a2b8', 'data' => $data_1_rate];
        $data['line_series'] = json_encode($line_series);
        return json_encode($data);
    }

    //作业图表页面
    public function homework(Course $course)
    {
        return view('teacher.homework.chart', compact('course'));
    }

    //作业图表数据
    public function homework_chart_data(Course $course)
    {
        $data = [];//结果数据
        //获取课程作业
        $homeworks = $course->homeworks()->get();
        //获取x轴文本为各次作业
        $categories = [];
        foreach ($homeworks as $homework) {
            array_push($categories, $homework->name);
        }
        //将xtext存入data中
        $data['xtext'] = json_encode($categories);
        //获取作业完成、未完成数据
        $seriesData = [];
        $data_1 = [];//完成作业记录
        $data_0 = [];//未完成作业记录
        //遍历作业提交记录，有提交记录的为完成
        $i = 0;
        $j = 0;
        foreach ($homeworks as $homework) {
            //每个作业的提交记录
            $commits = $homework->commits()->get();
            $data_1[$i++] = count($commits);
            //获取学生数量，为应交作业数
            $students = $course->students()->get();
            $data_0[$j++] = count($students) - count($commits);
        }
        //插入完成作业数据
        array_push($seriesData, ['name' => '完成', 'color' => '#28a745', 'data' => $data_1]);
        //插入未完成作业数据
        array_push($seriesData, ['name' => '未完成', 'color' => '#6c757d', 'data' => $data_0]);
        $data['column_series'] = json_encode($seriesData);
        return json_encode($data);
    }

    //报告图表页面
    public function report(Course $course)
    {
        return view('teacher.report.chart', compact('course'));
    }

    //报告图表数据
    public function report_chart_data(Course $course)
    {
        $data = [];//结果数据
        //获取课程报告
        $reports = $course->reports()->get();
        //获取x轴文本为各次报告
        $categories = [];
        foreach ($reports as $report) {
            array_push($categories, $report->name);
        }
        //将xtext存入data中
        $data['xtext'] = json_encode($categories);
        //获取作业完成、未完成数据
        $seriesData = [];
        $data_1 = [];//完成报告记录
        $data_0 = [];//未完成报告记录
        //遍历报告提交记录，有提交记录的为完成
        $i = 0;
        $j = 0;
        foreach ($reports as $report) {
            //每个报告的提交记录
            $commits = $report->commits()->get();
            $data_1[$i++] = count($commits);
            //获取学生数量，为应交报告数
            $students = $course->students()->get();
            $data_0[$j++] = count($students) - count($commits);
        }
        //插入完成报告数据
        array_push($seriesData, ['name' => '完成', 'color' => '#28a745', 'data' => $data_1]);
        //插入未完成报告数据
        array_push($seriesData, ['name' => '未完成', 'color' => '#6c757d', 'data' => $data_0]);
        $data['column_series'] = json_encode($seriesData);
        return json_encode($data);
    }

    public function final_exam(Course $course)
    {
        return view('teacher.final_exam.chart', compact('course'));
    }

    public function final_exam_chart_data(Course $course)
    {
        $data = [];//返回数据
        $students = $course->students()->get();//课程学生
        $range_data = [['优秀(90+)', 0], ['良好(80-90)', 0], ['中(70-80)', 0], ['及格(60-70)', 0], ['不及格(60-)', 0]];
        $scores = array();//记录学生成绩数据
        //遍历每个学生的期末考试成绩记录
        foreach ($students as $student) {
            $final_exam = $student->final_exam()->first();
            //学生有考试记录
            if ($final_exam) {
                array_push($scores, ['name' => $student->name, 'y' => $final_exam->final_exam_score]);//记录名字和成绩
                //判断考试成绩等级,记录各个范围的人数
                switch (floor($final_exam->final_exam_score / 10)) {
                    case 10:
                        $range_data[0][1]++;
                        break;
                    case 9:
                        $range_data[0][1]++;
                        break;
                    case 8:
                        $range_data[1][1]++;
                        break;
                    case 7:
                        $range_data[2][1]++;
                        break;
                    case 6:
                        $range_data[3][1]++;
                        break;
                    default:
                        $range_data[4][1]++;
                        break;
                }
            } //没有考试记录
            else {
                $range_data[4][1]++;//不及格的人数加一
                array_push($scores, ['name' => $student->name, 'y' => 0]);//记录成绩0分
            }
        }
        $timeKey = array_column($scores, 'y'); //取出数组中y的一列，返回一维数组
        array_multisort($timeKey, SORT_DESC, $scores);//排序，根据 y 排序
        $data['range_data'] = $range_data;//记录成绩范围扇形图数据
        $data['scores'] = $scores;//记录成绩
        return json_encode($data);
    }
}
