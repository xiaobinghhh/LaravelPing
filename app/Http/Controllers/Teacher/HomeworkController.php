<?php

namespace App\Http\Controllers\Teacher;

use App\Application\Course;
use App\Http\Requests\UploadFileRequest;
use App\Http\Requests\UploadNewFolderRequest;
use App\Services\HomeworkUploadsManager;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;


class HomeworkController extends Controller
{
    //作业文件操作时使用的管理工具
    protected $manager;

    //创建时注入管理工具依赖
    public function __construct(HomeworkUploadsManager $manager)
    {
        $this->manager = $manager;
    }

    //GET.course/{course}/homework     作业列表首页
    public function index(Course $course)
    {
        $homeworks = $course->homeworks()->get();
        return view('teacher.homework.index', compact('course', 'homeworks'));
    }

    //GET.course/{course}/homework/create   发布作业
    public function create(Course $course)
    {
        return view('teacher.homework.add', compact('course'));
    }

    //POST.course/{course}/homework   发布作业提交
    public function store(Request $request, Course $course)
    {
        $input = $request->except('_token');
        //对作业信息进行合理性检验
        $rules = [
            'homework_name' => 'required|max:16',
            'start_at' => 'required',
            'end_at' => 'required',
            'homework_desc' => 'required',
        ];
        $message = [
            'homework_name.required' => '作业名称不能为空',
            'homework_name.max' => '作业名称最多输入16个字',
            'start_at.required' => '开始时间不能为空',
            'end_at.required' => '结束时间不能为空',
            'homework_desc.required' => '作业描述不能为空',
        ];
        $validator = Validator::make($input, $rules, $message);
        //验证失败
        if ($validator->fails()) {
            return back()->withErrors($validator);
        } //验证成功
        else {
            //进一步验证作业完成日期的合理性
            $start_at = strtotime($request->input('start_at'));
            $end_at = strtotime($request->input('end_at'));
            //日期合理
            if ($start_at < $end_at) {
                //将作业文件放入相应的课程文件夹下
                //如果有作业文件
                if ($request->file('homework_file') != null) {
                    //第一次创建课程目录
                    if ($this->manager->createDirectory('teacher/' . $course->name)) {
                        //将作业文件存入课程目录
                        $file = $_FILES['homework_file'];
                        $fileName = $request->get('homework_file');
                        $fileName = $fileName ?: $file['name'];
                        $path = str_finish('teacher', '/') . str_finish($course->name, '/') . $fileName;
                        $content = File::get($file['tmp_name']);
                        $result = $this->manager->saveFile($path, $content);
                        //文件保存成功
                        if ($result === true) {
                            //插入数据库，包含文件路径
                            DB::table('homeworks')->insert([
                                'homework_id' => $course->homeworks()->count() + 1,
                                'course_no' => $course->no,
                                'name' => $request->input('homework_name'),
                                'description' => $request->input('homework_desc'),
                                'src' => $this->manager->fileWebpath($path),
                                'start_at' => $request->input('start_at'),
                                'end_at' => $request->input('end_at')
                            ]);
                            //发布作业成功后重定向到作业列表
                            return redirect('course/' . $course->no . '/homework')->withSuccess('新的作业已发布');
                        } //文件保存失败
                        else {
                            return back()->withErrors('作业文件保存失败！请重试');
                        }
                    }
                } //没有作业文件
                else {
                    //插入数据库
                    DB::table('homeworks')->insert([
                        'homework_id' => $course->homeworks()->count() + 1,
                        'course_no' => $course->no,
                        'name' => $request->input('homework_name'),
                        'description' => $request->input('homework_desc'),
                        'start_at' => $request->input('start_at'),
                        'end_at' => $request->input('end_at')
                    ]);
                }
                //发布作业成功后重定向到作业列表
                return redirect('course/' . $course->no . '/homework')->withSuccess('新的作业已发布');
            } //日期不合理
            else {
                return back()->withErrors('作业信息填写出错，发布失败！请重试');
            }
        }
    }

    //GET.course/{course}/homework/{homework}/edit   编辑作业
    public function edit(Course $course, $homework_id)
    {
        $homework = $course->homeworks()->where('homework_id', $homework_id)->first();
        return view('teacher.homework.edit', compact('course', 'homework'));
    }

    //PUT.course/{course}/homework/{homework}    更新作业
    public function update(Request $request, Course $course, $homework_id)
    {
        $homework = $course->homeworks()->where('homework_id', $homework_id)->first();
        $input = $request->except('_method', '_token');
        //对作业信息进行合理性检验
        $rules = [
            'homework_name' => 'required|max:16',
            'start_at' => 'required',
            'end_at' => 'required',
            'homework_desc' => 'required',
        ];
        $message = [
            'homework_name.required' => '作业名称不能为空',
            'homework_name.max' => '作业名称最多输入16个字',
            'start_at.required' => '开始时间不能为空',
            'end_at.required' => '结束时间不能为空',
            'homework_desc.required' => '作业描述不能为空',
        ];
        $validator = Validator::make($input, $rules, $message);
        //验证失败
        if ($validator->fails()) {
            return back()->withErrors($validator);
        } //验证成功
        else {
            //进一步验证作业完成日期的合理性
            $start_at = strtotime($request->input('start_at'));
            $end_at = strtotime($request->input('end_at'));
            //日期合理
            if ($start_at < $end_at) {
                //将作业文件放入相应的课程文件夹下
                //如果有作业文件
                if ($request->file('homework_file') != null) {
                    //第一次创建课程目录
                    if ($this->manager->createDirectory('teacher/' . $course->name)) {
                        //将作业文件存入课程目录
                        $file = $_FILES['homework_file'];
                        $fileName = $request->get('homework_file');
                        $fileName = $fileName ?: $file['name'];
                        $path = str_finish('teacher', '/') . str_finish($course->name, '/') . $fileName;
                        $content = File::get($file['tmp_name']);
                        $result = $this->manager->saveFile($path, $content);
                        //文件保存成功
                        if ($result === true) {
                            //更新数据库，包含文件路径
                            $homework->name = $request->input('homework_name');
                            $homework->description = $request->input('homework_desc');
                            $homework->src = $this->manager->fileWebpath($path);
                            $homework->start_at = $request->input('start_at');
                            $homework->end_at = $request->input('end_at');
                            $homework->update();
                            //更新作业成功后重定向到作业列表
                            return redirect('course/' . $course->no . '/homework')->withSuccess('课程作业已更新');
                        } //文件保存失败
                        else {
                            return back()->withErrors('作业文件更新失败！请重试');
                        }
                    }
                } //没有作业文件
                else {
                    //更新数据库
                    $homework->name = $request->input('homework_name');
                    $homework->description = $request->input('homework_desc');
                    $homework->start_at = $request->input('start_at');
                    $homework->end_at = $request->input('end_at');
                    $homework->update();
                }
                //更新作业成功后重定向到作业列表
                return redirect('course/' . $course->no . '/homework')->withSuccess('课程作业已更新');
            } //日期不合理
            else {
                return back()->withErrors('作业信息填写出错，更新失败！请重试');
            }
        }
    }

    //GET.course/{course}/homework/{homework}   显示单个作业信息
    public function show()
    {

    }

    //DELETE.course/{course}/homework/{homework}   删除单个作业
    public function destroy(Course $course, $homework_id)
    {
        $homework = $course->homeworks()->where('homework_id', $homework_id)->first();
        try {
            $re = $homework->delete();
        } //出错了
        catch (\Exception $e) {
            return back()->withErrors('删除出错，请重试');
        } finally {
            //删除成功
            if ($re) {
                $data = [
                    'status' => 0,
                    'msg' => '作业删除成功'
                ];
            } //删除失败
            else {
                $data = [
                    'status' => 1,
                    'msg' => '作业删除失败，请重试'
                ];
            }
        }
        return $data;
    }

    //作业评分
    public function ping(Course $course)
    {
        $homeworks = $course->homeworks()->get();
        return view('teacher.homework.ping', compact('course', 'homeworks'));
    }

    //作业评分表头
    public function columns(Course $course)
    {
        $homework_ping_columns = array();
        $student_no = array(
            'colname' => 'student_no',
            'colalias' => '学号',
        );
        //表格第一行，学号
        array_push($homework_ping_columns, $student_no);
        $student_name = array(
            'colname' => 'student_name',
            'colalias' => '姓名',
        );
        //表格第二行，学生姓名
        array_push($homework_ping_columns, $student_name);
        //获取课程作业
        $homeworks = $course->homeworks()->get();
        //接下来i行为作业成绩行
        foreach ($homeworks as $homework) {
            $temp = array(
                'colname' => strval($homework->id),
                'colalias' => $homework->name
            );
            array_push($homework_ping_columns, $temp);
        }
        $homework_score = array(
            'colname' => 'score',
            'colalias' => '作业得分',
        );
        //最后一行，签到成绩
        array_push($homework_ping_columns, $homework_score);
        return json_encode($homework_ping_columns);
    }

    //作业评分列表数据
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
            //获取课程作业
            $homeworks = $course->homeworks()->get();
            $homework_score = 0;//课程成绩，由课程提交记录求均值获得
            //遍历课程作业，得到该生课程作业包括成绩等信息
            foreach ($homeworks as $homework) {
                //该学生该门作业的提交记录
                $commits = $homework->commits()->where('student_no', $student->no)->get();
                //计算该生各次作业成绩
                foreach ($commits as $commit) {
                    $homework_score += $commit->homework_score;//获得课程作业总分
                }
                //获得该生提交记录中记录的对应次作业的成绩
                $commit = $commits->where('homework_course_id', '=', $homework->id)->first();
                //若找到提交记录，说明该生已提交作业
                if ($commit) {
                    $_data[$homework->id] = $commit->homework_score;
                    //详情展开中的内容
                    $_data['homework_commit_details_' . $homework->id] = array();
                    //作业提交附有文件
                    if ($commit->src != null) {
                        $_data['homework_commit_details_' . $homework->id] = [$homework->name, $commit->src, $commit->commit_desc];
                    } //作业提交没有文件
                    else {
                        $_data['homework_commit_details_' . $homework->id] = [$homework->name, "无文件", $commit->commit_desc];
                    }

                } //没有找到提交记录，说明该生未提交该次作业，记位0分
                else {
                    $_data[$homework->id] = 0;
                    $_data['homework_commit_details_' . $homework->id] = [$homework->name, "未提交"];
                }
            }
            //将课程作业总分除以作业数，得到学生作业分数
            $_data['score'] = $homework_score / count($homeworks);
            array_push($data, $_data);
        }
        return json_encode($data);
    }

    //作业评分分数修改
    public function ping_edit(Request $request, Course $course)
    {
        $homework_cnt = count($course->homeworks()->get());
        //初始化返回结果
        $result = [
            'flag' => 1,
            'msg' => '修改成功',
        ];
        //获取编辑行的学生学号
        $student_no = $request->input('student_no');
        //获取编辑行的各作业成绩
        $homework_scores = $request->except('student_no', 'student_name');
        //遍历各作业的成绩，找到修改的一项后修改
        foreach ($homework_scores as $k => $v) {
            //查找该学生$k作业的提交记录
            $commit = DB::table('student_homework')->where([
                ['homework_course_id', '=', $k],
                ['student_no', '=', $student_no]
            ])->first();
            //查找到该次作业的提交记录
            if ($commit != null) {
                //检查输入成绩格式
                //格式正确
                if (is_numeric($v) && is_int((int)$v) && $v >= 0 && $v <= 100) {
                    //成绩做了修改,进行修改
                    if ($commit->homework_score != $v) {
                        $basiss = $course->basis()->get();//获得该课程设置的评分项,准备计算加权总成绩
                        //计算之前作业成绩在总成绩中的加权值
                        $pre_homework = 0;
                        foreach ($basiss as $basis) {
                            switch ($basis->name) {
                                case 'homework':
                                    $pre_homework = $request->input('score') * $basis->weight / 100;
                                    break;
                            }
                        }
                        //计算修改后作业成绩在总成绩中的加权值
                        $rear_homework = 0;
                        foreach ($basiss as $basis) {
                            switch ($basis->name) {
                                case 'homework':
                                    $rear_homework = ($request->input('score') * $homework_cnt - $commit->homework_score + $v) / $homework_cnt * $basis->weight / 100;
                            }
                        }
                        //更新作业提交记录的作业成绩
                        $flag = DB::table('student_homework')->where([
                            ['homework_course_id', '=', $k],
                            ['student_no', '=', $student_no]
                        ])->update(['homework_score' => $v]) ? 1 : 0;
                        //同时更新课程总成绩
                        //获取学生课程选课表
                        $student_course = DB::table('student_course')->where('student_no', $student_no)
                            ->where('course_no', $course->no)->first();
                        //更新选课表的课程总成绩
                        $course_score = $student_course->course_score - $pre_homework + $rear_homework;
                        DB::table('student_course')->where('student_no', $student_no)->where('course_no', $course->no)
                            ->update(['course_score' => $course_score]);
                        $result['flag'] = $flag;
                        $result['msg'] = '修改成功';
                        return json_encode($result);
                    }//成绩没做修改
                    else {
                        $result['flag'] = 0;
                        $result['msg'] = '学生未提交作业无法修改，请重试';
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
        return view('teacher.homework.file_index', $data);
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

    //学生提交的作业文件下载
    public function download(Request $request)
    {
        $src = $request['src'];
        $str = explode('/', $src);
        $src = public_path('homeworks') . '\\';
        for ($i = 4; $i < sizeof($str); $i++) {
            $src = $src . str_finish($str[$i], '\\');
        }
        $src = rtrim($src, '\\');
        $file_name = basename($src);
        return response()->download($src, $file_name);
    }
}
