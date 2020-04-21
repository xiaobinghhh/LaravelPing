<?php

namespace App\Http\Controllers\Teacher;

use App\Application\Course;
use App\Http\Requests\UploadFileRequest;
use App\Http\Requests\UploadNewFolderRequest;
use App\Services\FinalExamUploadsManager;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class FinalExamController extends Controller
{
    //签到依据时使用的管理工具
    protected $manager;

    //创建时注入管理工具依赖
    public function __construct(FinalExamUploadsManager $manager)
    {
        $this->manager = $manager;
    }

    //首页
    public function index(Course $course)
    {
        return view('teacher.final_exam.index', compact('course'));
    }

    public function list(Course $course)
    {
        //该课程的同学
        $students = $course->students()->get();
        $data = array();
        //遍历期末考试成绩表，获取每个同学的期末成绩记录
        foreach ($students as $student) {
            $_data = array(
                'student_no' => $student->no,
                'student_name' => $student->name,
            );
            //获得该生该课程期末成绩记录
            $final_exam = $student->final_exam()->where('course_no', '=', $course->no)->first();
            //期末成绩
            if ($final_exam) $_data['final_exam_score'] = $final_exam->final_exam_score;
            else $_data['final_exam_score'] = 0;
            array_push($data, $_data);
        }
        return json_encode($data);
    }

    //编辑期末考试成绩
    public function edit(Request $request, Course $course)
    {
        //初始化返回结果
        $result = [
            'flag' => 1,
            'msg' => '修改成功',
        ];
        //Request中封装了传来的row数据
        //获得修改的成绩
        $final_exam_score = $request->input('final_exam_score');
        //成绩输入合法,0-100间的数字
        if (is_numeric($final_exam_score) && is_int((int)$final_exam_score) && $final_exam_score >= 0 && $final_exam_score <= 100) {
            //获取学号
            $student_no = $request->input('student_no');
            //找到该生
            $student = $course->students()->where('student_no', $student_no)->first();
            //找到该生该课程的期末考试记录
            $final_exam = $student->final_exam()->where('course_no', $course->no)->first();
            //找到了期末考试记录
            if ($final_exam) {
                //修改了期末考试成绩
                if ($final_exam_score != $final_exam->final_exam_score) {
                    //更新期末考试成绩
                    $final_exam->final_exam_score = $final_exam_score;
                    //更新
                    $flag = $final_exam->save() ? 1 : 0;
                    //更新结果
                    $result['flag'] = $flag;
                    //更新失败
                    if ($flag == 0) {
                        $result['msg'] = '更新失败，请重试';
                    }
                } else {
                    $result = [
                        'flag' => 0,
                        'msg' => '成绩未作修改，请重试',
                    ];
                }
            } //没找到期末考试记录，第一次录入
            else {
                //插入该生期末成绩
                $flag = DB::table('student_final_exam')->insert([
                    'student_no' => $student->no,
                    'course_no' => $course->no,
                    'final_exam_score' => $final_exam_score
                ]);
                //更新结果
                $result['flag'] = $flag;
                if ($flag == 0) {
                    $result['msg'] = '更新失败，请重试';
                }
            }

        } //输入成绩不合法
        else {
            $result = [
                'flag' => 0,
                'msg' => '输入成绩不合法，请重试',
            ];
        }
        return json_encode($result);
    }

    public function file(Request $request, Course $course)
    {
        $folder = $request->get('folder');
        $data = $this->manager->folderInfo($folder);
        $data['course'] = $course;
        return view('teacher.final_exam.file_index', $data);
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
