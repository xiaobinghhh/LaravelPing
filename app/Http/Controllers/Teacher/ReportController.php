<?php

namespace App\Http\Controllers\Teacher;

use App\Application\Course;
use App\Http\Requests\UploadFileRequest;
use App\Http\Requests\UploadNewFolderRequest;
use App\Services\ReportUploadsManager;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
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

    //GET.course/{course}/report     报告列表首页
    public function index(Course $course)
    {
        $reports = $course->reports()->get();
        return view('teacher.report.index', compact('course', 'reports'));
    }

    //GET.course/{course}/report/create   发布报告
    public function create(Course $course)
    {
        return view('teacher.report.add', compact('course'));
    }

    //POST.course/{course}/report   发布报告提交
    public function store(Request $request, Course $course)
    {
        $input = $request->except('_token');
        //对报告信息进行合理性检验
        $rules = [
            'report_name' => 'required|max:16',
            'start_at' => 'required',
            'end_at' => 'required',
            'report_desc' => 'required',
        ];
        $message = [
            'report_name.required' => '报告名称不能为空',
            'report_name.max' => '报告名称最多输入16个字',
            'start_at.required' => '开始时间不能为空',
            'end_at.required' => '结束时间不能为空',
            'report_desc.required' => '报告描述不能为空',
        ];
        $validator = Validator::make($input, $rules, $message);
        //验证失败
        if ($validator->fails()) {
            return back()->withErrors($validator);
        } //验证成功
        else {
            //进一步验证报告完成日期的合理性
            $start_at = strtotime($request->input('start_at'));
            $end_at = strtotime($request->input('end_at'));
            //日期合理
            if ($start_at < $end_at) {
                //将报告文件放入相应的课程文件夹下
                //如果有报告文件
                if ($request->file('report_file') != null) {
                    //第一次创建课程目录
                    if ($this->manager->createDirectory('teacher/' . $course->name)) {
                        //将报告文件存入课程目录
                        $file = $_FILES['report_file'];
                        $fileName = $request->get('report_file');
                        $fileName = $fileName ?: $file['name'];
                        $path = str_finish('teacher', '/') . str_finish($course->name, '/') . $fileName;
                        $content = File::get($file['tmp_name']);
                        $result = $this->manager->saveFile($path, $content);
                        //文件保存成功
                        if ($result === true) {
                            //插入数据库，包含文件路径
                            DB::table('reports')->insert([
                                'report_id' => $course->reports()->count() + 1,
                                'course_no' => $course->no,
                                'name' => $request->input('report_name'),
                                'description' => $request->input('report_desc'),
                                'src' => $this->manager->fileWebpath($path),
                                'start_at' => $request->input('start_at'),
                                'end_at' => $request->input('end_at')
                            ]);
                            //发布报告成功后重定向到报告列表
                            return redirect('course/' . $course->no . '/report')->withSuccess('新的报告已发布');
                        } //文件保存失败
                        else {
                            return back()->withErrors('报告文件保存失败！请重试');
                        }
                    }
                } //没有报告文件
                else {
                    //插入数据库
                    DB::table('reports')->insert([
                        'report_id' => $course->reports()->count() + 1,
                        'course_no' => $course->no,
                        'name' => $request->input('report_name'),
                        'description' => $request->input('report_desc'),
                        'start_at' => $request->input('start_at'),
                        'end_at' => $request->input('end_at')
                    ]);
                }
                //发布报告成功后重定向到报告列表
                return redirect('course/' . $course->no . '/report')->withSuccess('新的报告已发布');
            } //日期不合理
            else {
                return back()->withErrors('报告信息填写出错，发布失败！请重试');
            }
        }
    }

    //GET.course/{course}/report/{report}/edit   编辑报告
    public function edit(Course $course, $report_id)
    {
        $report = $course->reports()->where('report_id', $report_id)->first();
        return view('teacher.report.edit', compact('course', 'report'));
    }

    //PUT.course/{course}/report/{report}    更新报告
    public function update(Request $request, Course $course, $report_id)
    {
        $report = $course->reports()->where('report_id', $report_id)->first();
        $input = $request->except('_method', '_token');
        //对报告信息进行合理性检验
        $rules = [
            'report_name' => 'required|max:16',
            'start_at' => 'required',
            'end_at' => 'required',
            'report_desc' => 'required',
        ];
        $message = [
            'report_name.required' => '报告名称不能为空',
            'report_name.max' => '报告名称最多输入16个字',
            'start_at.required' => '开始时间不能为空',
            'end_at.required' => '结束时间不能为空',
            'report_desc.required' => '报告描述不能为空',
        ];
        $validator = Validator::make($input, $rules, $message);
        //验证失败
        if ($validator->fails()) {
            return back()->withErrors($validator);
        } //验证成功
        else {
            //进一步验证报告完成日期的合理性
            $start_at = strtotime($request->input('start_at'));
            $end_at = strtotime($request->input('end_at'));
            //日期合理
            if ($start_at < $end_at) {
                //将报告文件放入相应的课程文件夹下
                //如果有报告文件
                if ($request->file('report_file') != null) {
                    //第一次创建课程目录
                    if ($this->manager->createDirectory('teacher/' . $course->name)) {
                        //将报告文件存入课程目录
                        $file = $_FILES['report_file'];
                        $fileName = $request->get('report_file');
                        $fileName = $fileName ?: $file['name'];
                        $path = str_finish('teacher', '/') . str_finish($course->name, '/') . $fileName;
                        $content = File::get($file['tmp_name']);
                        $result = $this->manager->saveFile($path, $content);
                        //文件保存成功
                        if ($result === true) {
                            //更新数据库，包含文件路径
                            $report->name = $request->input('report_name');
                            $report->description = $request->input('report_desc');
                            $report->src = $this->manager->fileWebpath($path);
                            $report->start_at = $request->input('start_at');
                            $report->end_at = $request->input('end_at');
                            $report->update();
                            //更新报告成功后重定向到报告列表
                            return redirect('course/' . $course->no . '/report')->withSuccess('课程报告已更新');
                        } //文件保存失败
                        else {
                            return back()->withErrors('报告文件更新失败！请重试');
                        }
                    }
                } //没有报告文件
                else {
                    //更新数据库
                    $report->name = $request->input('report_name');
                    $report->description = $request->input('report_desc');
                    $report->start_at = $request->input('start_at');
                    $report->end_at = $request->input('end_at');
                    $report->update();
                }
                //更新报告成功后重定向到报告列表
                return redirect('course/' . $course->no . '/report')->withSuccess('课程报告已更新');
            } //日期不合理
            else {
                return back()->withErrors('报告信息填写出错，更新失败！请重试');
            }
        }
    }

    //GET.course/{course}/report/{report}   显示单个报告信息
    public function show()
    {

    }

    //DELETE.course/{course}/report/{report}   删除单个报告
    public function destroy(Course $course, $report_id)
    {
        $report = $course->reports()->where('report_id', $report_id)->first();
        try {
            $re = $report->delete();
        } //出错了
        catch (\Exception $e) {
            return back()->withErrors('删除出错，请重试');
        } finally {
            //删除成功
            if ($re) {
                $data = [
                    'status' => 0,
                    'msg' => '报告删除成功'
                ];
            } //删除失败
            else {
                $data = [
                    'status' => 1,
                    'msg' => '报告删除失败，请重试'
                ];
            }
        }
        return $data;
    }

    //报告评分
    public function ping(Course $course)
    {
        $reports = $course->reports()->get();
        return view('teacher.report.ping', compact('course', 'reports'));
    }

    //报告评分表头
    public function columns(Course $course)
    {
        $report_ping_columns = array();
        $student_no = array(
            'colname' => 'student_no',
            'colalias' => '学号',
        );
        //表格第一行，学号
        array_push($report_ping_columns, $student_no);
        $student_name = array(
            'colname' => 'student_name',
            'colalias' => '姓名',
        );
        //表格第二行，学生姓名
        array_push($report_ping_columns, $student_name);
        //获取课程报告
        $reports = $course->reports()->get();
        //接下来i行为报告成绩行
        foreach ($reports as $report) {
            $temp = array(
                'colname' => strval($report->id),
                'colalias' => $report->name
            );
            array_push($report_ping_columns, $temp);
        }
        $report_score = array(
            'colname' => 'score',
            'colalias' => '报告得分',
        );
        //最后一行，签到成绩
        array_push($report_ping_columns, $report_score);
        return json_encode($report_ping_columns);
    }

    //报告评分列表数据
    public function list(Course $course)
    {
        //获取课程学生
        $students = $course->students()->get();
        $data = array();
        foreach ($students as $student) {
            $_data = array(
                'student_no' => $student->no,
                'student_name' => $student->name,
            );
            //获取课程报告
            $reports = $course->reports()->get();
            $report_score = 0;//课程成绩，由课程提交记录求均值获得
            //遍历课程报告，得到该生课程报告包括成绩等信息
            foreach ($reports as $report) {
                //该学生该门报告的提交记录
                $commits = $report->commits()->where('student_no', $student->no)->get();
                //计算该生各次报告成绩
                foreach ($commits as $commit) {
                    $report_score += $commit->report_score;//获得课程报告总分
                }
                //获得该生提交记录中记录的对应次报告的成绩
                $commit = $commits->where('report_course_id', '=', $report->id)->first();
                //若找到提交记录，说明该生已提交报告
                if ($commit) {
                    $_data[$report->id] = $commit->report_score;
                    //详情展开中的内容
                    $_data['report_commit_details_' . $report->id] = array();
                    //报告提交附有文件
                    if ($commit->src != null) {
                        $_data['report_commit_details_' . $report->id] = [$report->name, $commit->src, $commit->commit_desc];
                    } //报告提交没有文件
                    else {
                        $_data['report_commit_details_' . $report->id] = [$report->name, "无文件", $commit->commit_desc];
                    }

                } //没有找到提交记录，说明该生未提交该次报告，记位0分
                else {
                    $_data[$report->id] = 0;
                    $_data['report_commit_details_' . $report->id] = [$report->name, "未提交"];
                }
            }
            //将课程报告总分除以报告数，得到学生报告分数
            $_data['score'] = $report_score / count($reports);
            array_push($data, $_data);
        }
        return json_encode($data);
    }

    //报告评分分数修改
    public function ping_edit(Request $request, Course $course)
    {
        $report_cnt = count($course->reports()->get());
        //初始化返回结果
        $result = [
            'flag' => 1,
            'msg' => '修改成功',
        ];
        //获取编辑行的学生学号
        $student_no = $request->input('student_no');
        //获取编辑行的各报告成绩
        $report_scores = $request->except('student_no', 'student_name', 'score');
        //遍历各报告的成绩，找到修改的一项后修改
        foreach ($report_scores as $k => $v) {
            //查找该学生$k报告的提交记录
            $commit = DB::table('student_report')->where([
                ['report_course_id', '=', $k],
                ['student_no', '=', $student_no]
            ])->first();
            //查找到该次报告的提交记录
            if ($commit != null) {
                //检查输入成绩格式
                //格式正确
                if (is_numeric($v) && is_int((int)$v) && $v >= 0 && $v <= 100) {
                    //成绩做了修改,进行修改
                    if ($commit->report_score != $v) {
                        $basiss = $course->basis()->get();//获得该课程设置的评分项,准备计算加权总成绩
                        //计算之前报告成绩在总成绩中的加权值
                        $pre_report = 0;
                        foreach ($basiss as $basis) {
                            switch ($basis->name) {
                                case 'report':
                                    $pre_report = $request->input('score') * $basis->weight / 100;
                                    break;
                            }
                        }
                        //计算修改后报告成绩在总成绩中的加权值
                        $rear_report = 0;
                        foreach ($basiss as $basis) {
                            switch ($basis->name) {
                                case 'report':
                                    $rear_report = ($request->input('score') * $report_cnt - $commit->report_score + $v) / $report_cnt * $basis->weight / 100;
                                    break;
                            }
                        }
                        //更新报告提交记录的报告成绩
                        $flag = DB::table('student_report')->where([
                            ['report_course_id', '=', $k],
                            ['student_no', '=', $student_no]
                        ])->update(['report_score' => $v]) ? 1 : 0;
                        //同时更新课程总成绩
                        //获取学生课程选课表
                        $student_course = DB::table('student_course')->where('student_no', $student_no)->where('course_no', $course->no)->first();
                        //更新选课表的课程总成绩
                        $course_score = $student_course->course_score - $pre_report + $rear_report;
                        DB::table('student_course')->where('student_no', $student_no)->where('course_no', $course->no)
                            ->update(['course_score'=>$course_score]);
                        $result['flag'] = $flag;
                        $result['msg'] = '修改成功';
                        return json_encode($result);
                    }//成绩没做修改
                    else {
                        $result['flag'] = 0;
                        $result['msg'] = '学生未提交报告无法修改，请重试';
                        continue;
                    }
                }//格式错误
                else {
                    $result['flag'] = 0;
                    $result['msg'] = '成绩编辑格式错误，应为0-100间的整数';
                    return json_encode($result);
                }
            } else {
                continue;
            }
        }
        return json_encode($result);
    }

    //签到依据
    public function file(Request $request, Course $course)
    {
        $folder = $request->get('folder');
        $data = $this->manager->folderInfo($folder);
        $data['course'] = $course;
        return view('teacher.report.file_index', $data);
    }

    /**
     * 创建新目录
     * @param UploadNewFolderRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createFolder(UploadNewFolderRequest $request)
    {
        $new_folder = $request->get('new_folder');
        $folder = $request->get('folder') . '/' . $new_folder;

        $result = $this->manager->createDirectory($folder);

        if ($result === true) {
            return redirect()
                ->back()
                ->withSuccess("目录 '$new_folder' 已创建");
        }

        $error = $result ?: "创建目录时出错";
        return redirect()
            ->back()
            ->withErrors([$error]);
    }

    /**
     * 删除文件
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteFile(Request $request)
    {
        $del_file = $request->get('del_file');
        $path = $request->get('folder') . '/' . $del_file;

        $result = $this->manager->deleteFile($path);

        if ($result === true) {
            return redirect()
                ->back()
                ->withSuccess("文件 '$del_file' 已删除");
        }

        $error = $result ?: "删除文件时出错";
        return redirect()
            ->back()
            ->withErrors([$error]);
    }

    /**
     * 删除目录
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteFolder(Request $request)
    {
        $del_folder = $request->get('del_folder');
        $folder = $request->get('folder') . '/' . $del_folder;

        $result = $this->manager->deleteDirectory($folder);

        if ($result === true) {
            return redirect()
                ->back()
                ->withSuccess("目录 '$del_folder' 已删除");
        }

        $error = $result ?: "删除目录时出错";
        return redirect()
            ->back()
            ->withErrors([$error]);
    }

    /**
     * 上传文件
     * @param UploadFileRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uploadFile(UploadFileRequest $request)
    {
        $file = $_FILES['file'];
        $fileName = $request->get('file_name');
        $fileName = $fileName ?: $file['name'];
        $path = str_finish($request->get('folder'), '/') . $fileName;
        $content = File::get($file['tmp_name']);

        $result = $this->manager->saveFile($path, $content);

        if ($result === true) {
            return redirect()
                ->back()
                ->withSuccess("文件 '$fileName' 已上传");
        }

        $error = $result ?: "上传文件时出错";
        return redirect()
            ->back()
            ->withErrors([$error]);
    }

    //学生提交的报告下载
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
