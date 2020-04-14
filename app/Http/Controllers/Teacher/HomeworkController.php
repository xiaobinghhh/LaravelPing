<?php

namespace App\Http\Controllers\Teacher;

use App\Application\Course;
use App\Application\Homework;
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
                    if ($this->manager->createDirectory($course->name)) {
                        //将作业文件存入课程目录
                        $file = $_FILES['homework_file'];
                        $fileName = $request->get('homework_file');
                        $fileName = $fileName ?: $file['name'];
                        $path = str_finish($course->name, '/') . $fileName;
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
    public function edit(Course $course, Homework $homework)
    {
        return view('teacher.homework.edit', compact('course', 'homework'));
    }

    //PUT.course/{course}/homework/{homework}    更新作业
    public function update(Request $request, Course $course, Homework $homework)
    {
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
                    if ($this->manager->createDirectory($course->name)) {
                        //将作业文件存入课程目录
                        $file = $_FILES['homework_file'];
                        $fileName = $request->get('homework_file');
                        $fileName = $fileName ?: $file['name'];
                        $path = str_finish($course->name, '/') . $fileName;
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
    public function destroy(Course $course, Homework $homework)
    {
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
        //获取课程发布的作业
        $homeworks = $course->homeworks()->get();
        //获取选了这门课程的学生
        $students = $course->students()->get();
        return view('teacher.homework.ping', compact('homeworks'));
    }
}
