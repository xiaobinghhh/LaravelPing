<?php

namespace App\Http\Controllers\Teacher;

use App\Application\Course;
use App\Application\Homework;
use Illuminate\Http\Request;
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
                    $data_1[$i]++;
                    $data_1_cnt[$i]++;
                } //为0缺勤
                else if ($sign_data[$i][$j] == 0) $data_0[$i]++;
            }
        }
        //确定出勤百分比
        for ($i = 0; $i < count($sign_data); $i++) {
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

    //作业图表数据
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

}
