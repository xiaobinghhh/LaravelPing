<?php

namespace App\Http\Controllers\Teacher;

use App\Application\Basis;
use App\Application\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\Types\String_;

class BasisController extends Controller
{
    //GET.course/{course}/basis     评分项列表首页
    public function index(Course $course)
    {
        return view('teacher.basis.index', compact('course'));
    }

    //GET.course/{course}/basis/list
    public function list(Course $course)
    {
        //该课程的评分项
        $basis = $course->basis()->get();
        $data = array();
        //遍历评分项，获取评分项数据
        foreach ($basis as $v) {
            $_data = array(
                'basis_id' => $v->id,
                'basis_name' => $v->name,
                'basis_weight' => $v->weight,
            );
            array_push($data, $_data);
        }
        return json_encode($data);
    }

    //Any.course/{course}/basis/add   新增评分项
    public function add(Course $course)
    {
        //post请求新增
        if (Input::method() == 'POST') {

            $rules = [
                'basis_name' => 'required',
                'basis_weight' => 'required|integer|between:0,100',
            ];
            $message = [
                'basis_name.required' => '请选择评分项权重',
                'basis_weight.required' => '评分项权重不能为空',
                'basis_weight.integer' => '评分项权重应为整数',
                'basis_weight.between' => '评分项权重应为0-100之间的数',
            ];
            $validator = Validator::make(Input::except('_token'), $rules, $message);
            //输入有效性验证
            //失败
            if ($validator->fails()) {
                return back()->withErrors($validator);
            } //成功
            else {
                //判断添加评分项的cn_name，手动添加
                $cn_name = '';
                $name = Input::get('basis_name');
                switch ($name) {
                    case 'signment':
                        $cn_name = '签到';
                        break;
                    case 'homework':
                        $cn_name = '作业';
                        break;
                    case 'report':
                        $cn_name = '报告';
                        break;
                    case 'final_exam':
                        $cn_name = '期末考试';
                        break;
                }
                try {
                    $result = Basis::insert(['course_no' => $course->no, 'name' => $name, 'cn_name' => $cn_name,
                        'weight' => Input::get('basis_weight')]);
                    //插入成功
                    if ($result) {
                        return redirect(url('course/' . $course->no . '/basis'))->withSuccess("新增评分项成功");
                    } //插入失败
                    else {
                        return back()->withErrors("数据库插入失败，检查后请重试");
                    }
                } catch (\Exception $e) {
                    return back()->withErrors("数据库插入失败，检查后请重试");
                }
            }
        } //get请求返回视图
        else {
            $basis = array('signment' => '签到', 'homework' => '作业', 'report' => '报告', 'final_exam' => '期末考试');
            return view('teacher.basis.add', compact('course', 'basis'));
        }
    }

    //POST.course/{course}/basis/edit   编辑评分项
    public function edit(Request $request, Course $course)
    {
        //初始化返回结果
        $result = [
            'flag' => 1,
            'msg' => '修改成功',
        ];
        //Request中封装了传来的row数据
        //获得修改的权重
        $basis_weight = $request->input('basis_weight');
        //成绩输入合法,0-100间的整数
        if (is_numeric($basis_weight) && is_int((int)$basis_weight) && $basis_weight >= 0 && $basis_weight <= 100) {
            //获取评分项的id
            $basis_id = $request->input('basis_id');
            //找到该评分项
            $basis = $course->basis()->where('id', $basis_id)->first();
            //修改了评分项的权重
            if ($basis_id != $basis->weight) {
                //更新评分项权重
                $basis->weight = $basis_weight;
                //更新
                $flag = $basis->save() ? 1 : 0;
                //更新结果
                $result['flag'] = $flag;
                //更新失败
                if ($flag == 0) {
                    $result['msg'] = '更新失败，请重试';
                }
            } else {
                $result = [
                    'flag' => 0,
                    'msg' => '权重未作修改，请重试',
                ];
            }
        } //输入权重不合法
        else {
            $result = [
                'flag' => 0,
                'msg' => '输入权重格式应为0-100间的整数，请重试',
            ];
        }
        return json_encode($result);
    }

    //DELETE.course/{course}/basis/{basis}  删除评分项
    public function delete(Course $course, $basis_id)
    {
        $basis = $course->basis()->where('id', $basis_id)->first();
        try {
            $re = $basis->delete();
        } //出错了
        catch (\Exception $e) {
            return back()->withErrors('删除出错，请重试');
        } finally {
            //删除成功
            if ($re) {
                $data = [
                    'status' => 0,
                    'msg' => '评分项删除成功'
                ];
            } //删除失败
            else {
                $data = [
                    'status' => 1,
                    'msg' => '评分项删除失败，请重试'
                ];
            }
        }
        return $data;
    }
}
