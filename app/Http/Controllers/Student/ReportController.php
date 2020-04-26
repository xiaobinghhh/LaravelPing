<?php

namespace App\Http\Controllers\Student;

use App\Application\Course;
use App\Services\ReportUploadsManager;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    //报告文件操作时使用的管理工具
    protected $manager;

    //创建时注入管理工具依赖
    public function __construct(ReportUploadsManager $manager)
    {
        $this->manager = $manager;
    }

    //我的报告-主页面
    public function index(Course $course)
    {
        $student_no = session('userInfo')['no'];//获取学号
        $data = [];//返回数据
        $reports = $course->reports()->get();//获得课程报告
        //课程布置了报告
        if ($reports) {
            foreach ($reports as $report) {
                $commit = $report->commits()->where('student_no', $student_no)->first();//获得该生的报告提交
                $one_data = [];
                //计算报告提交剩余日期
                $date_1 = $report->start_at;
                $date_2 = date('Y-m-d');
                $Date_List_a1 = explode("-", $date_1);
                $Date_List_a2 = explode("-", $date_2);
                $d1 = mktime(0, 0, 0, $Date_List_a1[1], $Date_List_a1[2], $Date_List_a1[0]);
                $d2 = mktime(0, 0, 0, $Date_List_a2[1], $Date_List_a2[2], $Date_List_a2[0]);
                $days = round(($d1 - $d2) / 3600 / 24);
                if ($days < 0) $days = 0;
                if ($commit) {
                    array_push($one_data, [
                        'report_id' => $report->id,
                        'report_name' => $report->name,
                        'report_desc' => $report->description,
                        'report_src' => $report->src,
                        'time_left' => $days,
                        'status' => '已完成',
                        'src' => $commit->src,
                        'commit_desc' => $commit->commit_desc
                    ]);
                    array_push($data, $one_data);
                } else {
                    array_push($one_data, [
                        'report_id' => $report->id,
                        'report_name' => $report->name,
                        'report_desc' => $report->description,
                        'report_src' => $report->src,
                        'time_left' => $days,
                        'status' => '未完成',
                        'src' => null,
                        'commit_desc' => null
                    ]);
                    array_push($data, $one_data);
                }
            }
        } else {
            array_push($data, null);
        }
//        dd($data);
        return view('student.report.index', compact('course', 'data'));
    }

    public function edit(Request $request, Course $course, $report_id)
    {
        if (Input::method() == 'POST') {
            $input = $request->except('_token');
            //对报告信息进行合理性检验
            $rules = [
                'commit_desc' => 'required',
            ];
            $message = [
                'commit_desc.required' => '请为完成本次报告做些描述',
            ];
            $validator = Validator::make($input, $rules, $message);
            //验证失败
            if ($validator->fails()) {
                return back()->withErrors($validator);
            } //验证成功
            else {
                $student_no = session('userInfo')['no'];//学生学号
                $report = $course->reports()->where('report_id', $report_id)->first();//获得该报告信息
                $commit = $report->commits()->where('student_no', $student_no)->first();//获得该学生报告提交记录
                //将报告文件放入相应的课程文件夹下
                //如果有报告文件
                if ($request->file('commit_src') != null) {
                    //第一次创建课程目录
                    if ($this->manager->createDirectory('student/' . $course->name . '/' . $report->name)) {
                        //将报告文件存入课程目录
                        $file = $_FILES['commit_src'];
                        $fileName = $request->get('commit_src');
                        $fileName = $fileName ?: $file['name'];
                        $path = str_finish('student', '/') . str_finish($course->name, '/') . str_finish($report->name, '/') . $fileName;
                        $content = File::get($file['tmp_name']);
                        $result = $this->manager->saveFile($path, $content);
                        //文件保存成功
                        if ($result === true) {
                            //更新数据库，包含文件路径
                            $commit->src = $this->manager->fileWebpath($path);//修改文件路径
                            $commit->commit_desc = $request->input('commit_desc');
                            $commit->update();
                            //修改完报告提交成功后重定向到报告列表
                            return redirect('student/course/' . $course->no . '/report')->withSuccess('提交修改成功');
                        } //文件保存失败
                        else {
                            return back()->withErrors('提交的报告文件修改失败！请重试');
                        }
                    }
                } //没有报告文件
                else {
                    //更新数据库
                    $commit->commit_desc = $request->input('commit_desc');
                    $commit->update();
                }
                //更新报告成功后重定向到报告列表
                return redirect('student/course/' . $course->no . '/report')->withSuccess('提交修改成功');
            }
        } else {
            $student_no = session('userInfo')['no'];//学生学号
            $report = $course->reports()->where('report_id', $report_id)->first();//获得该报告信息
            $commit = $report->commits()->where('student_no', $student_no)->first();//获得该学生报告提交记录
            //获取剩余天数
            $date_1 = $report->start_at;
            $date_2 = date('Y-m-d');
            $Date_List_a1 = explode("-", $date_1);
            $Date_List_a2 = explode("-", $date_2);
            $d1 = mktime(0, 0, 0, $Date_List_a1[1], $Date_List_a1[2], $Date_List_a1[0]);
            $d2 = mktime(0, 0, 0, $Date_List_a2[1], $Date_List_a2[2], $Date_List_a2[0]);
            $days = round(($d1 - $d2) / 3600 / 24);
            if ($days < 0) $days = 0;
            return view('student.report.edit', compact('course', 'report', 'days', 'commit'));
        }
    }

    public function commit(Request $request, Course $course, $report_id)
    {
        if (Input::method() == 'POST') {
            $input = $request->except('_token');
            //对报告信息进行合理性检验
            $rules = [
                'commit_desc' => 'required',
            ];
            $message = [
                'commit_desc.required' => '请为完成本次报告做些描述',
            ];
            $validator = Validator::make($input, $rules, $message);
            //验证失败
            if ($validator->fails()) {
                return back()->withErrors($validator);
            } //验证成功
            else {
                $student_no = session('userInfo')['no'];//学生学号
                $report = $course->reports()->where('report_id', $report_id)->first();//获得该报告信息
                //将报告文件放入相应的课程文件夹下
                //如果有报告文件
                if ($request->file('commit_src') != null) {
                    //第一次创建课程目录
                    if ($this->manager->createDirectory('student/' . $course->name . '/' . $report->name)) {
                        //将报告文件存入课程目录
                        $file = $_FILES['commit_src'];
                        $fileName = $request->get('commit_src');
                        $fileName = $fileName ?: $file['name'];
                        $path = str_finish('student', '/') . str_finish($course->name, '/') . str_finish($report->name, '/') . $fileName;
                        $content = File::get($file['tmp_name']);
                        $result = $this->manager->saveFile($path, $content);
                        //文件保存成功
                        if ($result === true) {
                            DB::table('student_report')->insert([
                                'report_course_id' => $report->id,
                                'student_no' => $student_no,
                                'src' => $this->manager->fileWebpath($path),
                                'commit_desc' => $request->input('commit_desc')
                            ]);
                            //重定向到报告列表
                            return redirect('student/course/' . $course->no . '/report')->withSuccess('报告完成了！');
                        } //文件保存失败
                        else {
                            return back()->withErrors('提交的报告文件提交失败！请重试');
                        }
                    }
                } //没有报告文件
                else {
                    //更新数据库
                    DB::table('student_report')->insert([
                        'report_course_id' => $report->id,
                        'student_no' => $student_no,
                        'commit_desc' => $request->input('commit_desc')
                    ]);
                }
                //提交报告成功后重定向到报告列表
                return redirect('student/course/' . $course->no . '/report')->withSuccess('报告完成了！');
            }
        } else {
            $report = $course->reports()->where('report_id', $report_id)->first();//获得该报告信息
            //获取剩余天数
            $date_1 = $report->start_at;
            $date_2 = date('Y-m-d');
            $Date_List_a1 = explode("-", $date_1);
            $Date_List_a2 = explode("-", $date_2);
            $d1 = mktime(0, 0, 0, $Date_List_a1[1], $Date_List_a1[2], $Date_List_a1[0]);
            $d2 = mktime(0, 0, 0, $Date_List_a2[1], $Date_List_a2[2], $Date_List_a2[0]);
            $days = round(($d1 - $d2) / 3600 / 24);
            if ($days < 0) $days = 0;
            return view('student.report.commit', compact('course', 'report', 'days'));
        }
    }

    //文件下载
    public function download(Request $request)
    {
        $src = $request['src'];
        $str = explode('/', $src);
        $src = public_path('reports') . '\\';
        for ($i = 4; $i < sizeof($str); $i++) {
            $src = $src . str_finish($str[$i], '\\');
        }
        $src = rtrim($src, '\\');
        $file_name = basename($src);
        return response()->download($src, $file_name);
    }
}
