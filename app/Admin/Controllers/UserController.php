<?php

namespace App\Admin\Controllers;

use App\Application\Student;
use App\Application\Teacher;
use App\Application\User;
use Encore\Admin\Admin;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;

class UserController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '系统用户';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User());

        //列显示
//        $grid->column('id', __('Id'));
        $grid->column('no', __('学号/工号'));
//        $grid->column('password', __('Password'));
        $grid->column('gender', '性别')->using(['1' => '男', '2' => '女'])->label(
            [1 => 'primary', 2 => 'info']
        );
        $grid->column('age', __('年龄'));
        $grid->column('mobile', __('电话'));
        $grid->column('email', __('邮箱'));
//        $grid->column('avatar', __('Avatar'));
        $grid->column('type', '角色')->using(['1' => '学生', '2' => '老师'])->label(
            [1 => 'success', 2 => 'danger']
        );

        //查询过滤
        $grid->filter(function ($filter) {
            // 去掉默认的id过滤器
            $filter->disableIdFilter();
            // 在这里添加字段过滤器
            $filter->like('no', '学号/工号');
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(User::findOrFail($id));

//        $show->field('id', __('Id'));
        $show->field('no', __('学号/工号'));
        $show->field('password', __('密码'));
        $show->field('gender', __('性别'))->using(['1' => '男', '2' => '女']);
        $show->field('age', __('年龄'));
        $show->field('mobile', __('电话'));
        $show->field('email', __('邮箱'));
//        $show->field('avatar', __('Avatar'));
        $show->field('type', __('类型'))->using(['1' => '学生', '2' => '老师']);

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new User());
            $form->text('no', __('学号/工号'))->readonly();
            $form->password('password', __('密码'));
            $genders = [
                1 => '男',
                2 => '女',
            ];
            $form->select('gender', __('性别'))->default('1')->options($genders);
            $form->text('age', __('年龄'));
            $form->mobile('mobile', __('电话'));
            $form->email('email', __('邮箱'));
//        $form->image('avatar', __('Avatar'));
            $types = [
                1 => '学生',
                2 => '老师',
            ];
            $form->select('type', __('类别'))->default('1')->options($types);

        return $form;
    }

}
