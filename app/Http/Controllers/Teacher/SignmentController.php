<?php

namespace App\Http\Controllers\Teacher;

use App\Application\Course;
use App\Application\Signment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
//签到依据新增
use App\Services\SignmentUploadsManager;
use App\Http\Requests\UploadFileRequest;
use App\Http\Requests\UploadNewFolderRequest;
use Illuminate\Support\Facades\File;

class SignmentController extends Controller
{
    //签到依据时使用的管理工具
    protected $manager;

    //创建时注入管理工具依赖
    public function __construct(SignmentUploadsManager $manager)
    {
        $this->manager = $manager;
    }

    //签到评分
    public function ping(Course $course)
    {
        //返回签到评分首页
        return view('teacher.signment.index', compact('course'));
    }

    //签到列表表头
    public function columns(Course $course)
    {
        $sign_columns = array();
        $student_no = array(
            'colname' => 'student_no',
            'colalias' => '学号',
        );
        //表格第一行，学号
        array_push($sign_columns, $student_no);
        $student_name = array(
            'colname' => 'student_name',
            'colalias' => '姓名',
        );
        //表格第二行，学生姓名
        array_push($sign_columns, $student_name);
        //获取课程签到记录
        $signment = $course->students()->first()->signments()->where('course_no', '=', $course->no)->first();
        //接下来i行为签到记录行
        if ($signment->sign_data != null) {
            $signment_array = str_split($signment->sign_data, 1);
            for ($i = 1; $i <= count($signment_array); $i++) {
                $sign_data = array(
                    'colname' => 'sign_data_' . $i,
                    'colalias' => '第' . $i . '次签到'
                );
                array_push($sign_columns, $sign_data);
            }
        }
        $sign_score = array(
            'colname' => 'sign_score',
            'colalias' => '签到成绩',
        );
        //最后一行，签到成绩
        array_push($sign_columns, $sign_score);
        return json_encode($sign_columns);
    }

    //签到列表
    public function list(Course $course)
    {
        $students = $course->students()->get();
        $sign_list = array();
        foreach ($students as $student) {
            $sign = array(
                'student_no' => $student->no,
                'student_name' => $student->name,
            );
            //获得学生对应课程的签到记录
            $signment = $student->signments()->where('course_no', '=', $course->no)->first();
            //已经有签到记录
            if ($signment->sign_data != null) {
                $signment_array = str_split($signment->sign_data, 1);
                for ($i = 1; $i <= count($signment_array); $i++) {
                    $sign['sign_data_' . $i] = $signment_array[$i - 1];
                }
            }
            $sign['sign_score'] = $signment->sign_score;
            array_push($sign_list, $sign);
        }
        return json_encode($sign_list);
    }

    //添加签到
    public function add(Course $course)
    {
        if (Input::method() == 'POST') {
            if (Input::get('chkItm')) {
                //获取勾选签到的学生学号
                $post_signs = Input::get('chkItm');
                //更新课程对应学生的签到记录并修改签到成绩
                //获得课程每个学生的签到
                $course_signments = Signment::where('course_no', $course->no)->get();
                //遍历签到，逐个修改
                foreach ($course_signments as $one_signment) {
                    //先统计签到次数，更新签到成绩时需要用
                    //已经存在签到记录
                    if ($one_signment->sign_data) {
                        $sign_data = str_split($one_signment->sign_data);
                        $count = count($sign_data);//记录签到次数
                    } else {
                        $count = 0;//否则签到次数为0
                    }
                    //勾选了当前学生
                    if (in_array($one_signment->student_no, $post_signs)) {
                        //当前学生的课程签到记录串追加一个“1”
                        $one_signment->sign_data = $one_signment->sign_data . "1";
                        //更新签到成绩
                        $one_signment->sign_score = ($one_signment->sign_score * $count + 100) / ($count + 1);
                    } //没勾选当前学生
                    else {
                        //当前学生的课程签到记录加0
                        $one_signment->sign_data = $one_signment->sign_data . "0";
                        //更新签到成绩
                        $one_signment->sign_score = ($one_signment->sign_score * $count) / ($count + 1);
                    }
                    $one_signment->save();
                }
                //修改完毕返回签到评分页面
                return redirect(url('course/' . $course->no . '/signment_ping'));
            } else {
                //一门课不能没人来
                return back()->withErrors(['请选择签到的同学']);
            }
        } else {
            //获取课程学生
            $students = $course->students()->get();
            return view('teacher.signment.add', compact('students'));
        }
    }

    //编辑签到
    public function edit(Request $request, Course $course)
    {
        //初始化返回结果
        $result = [
            'flag' => 1,
            'msg' => '修改成功',
        ];
        //Request中封装了传来的row数据
        //获取要修改的学生学号信息
        $student_no = $request->input('student_no');
        //获取row数据中的签到数据
        $sign_datas = $request->except('student_no', 'student_name', 'sign_score');
        //将签到数据合成字符串
        $sign_data = '';
        $total_score = 0;//签到加权总分
        $count = 0;//签到次数
        foreach ($sign_datas as $k => $v) {
            if ($v == 0) {
                $total_score += 0;
                $count++;
            } else if ($v == 1) {
                $total_score += 100;
                $count++;
            } else {//编辑的格式出错
                $result['flag'] = 0;
                $result['msg'] = '编辑格式错误，1出勤，0缺勤';
                return json_encode($result);
            }
            $sign_data = $sign_data . $v;
        }
        //获得签到信息有更新的学生
        $student = $course->students()->where('no', $student_no)->first();
        //获得该生该门课程签到信息
        $signment = $student->signments()->where('course_no', $course->no)->first();
        //更新签到数据
        $signment->sign_data = $sign_data;
        //计算签到成绩
        if ($count) {
            $sign_score = $total_score / $count;
            //更新签到成绩
            $signment->sign_score = $sign_score;
        }
        //更新
        $flag = $signment->save() ? 1 : 0;
        $result['flag'] = $flag;//记录更新结果，成功1，失败0
        return json_encode($result);
    }

    //签到依据
    public function file(Request $request, Course $course)
    {
        $folder = $request->get('folder');
        $data = $this->manager->folderInfo($folder);
        $data['course'] = $course;
        return view('teacher.signment.file_index', $data);
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

}
