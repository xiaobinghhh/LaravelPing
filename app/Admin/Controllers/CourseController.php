<?php

namespace App\Admin\Controllers;

use App\Application\Course;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CourseController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '课程';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Course());

//        $grid->column('id', __('Id'));
        $grid->column('no', __('课程号'));
        $grid->column('name', __('课程姓名'));
        $grid->column('teacher_no', __('教师工号'));
        $grid->column('teacher.name', __('教师姓名'));
        $grid->column('begin_at', __('开课日期'));
        $grid->column('end_at', __('结课日期'));
        $grid->column('place', __('上课地点'));
        $grid->column('credit', __('学分'));
        $grid->column('period', __('课时'));

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
        $show = new Show(Course::findOrFail($id));

//        $show->field('id', __('Id'));
        $show->field('no', __('课程号'));
        $show->field('name', __('课程姓名'));
        $show->field('teacher_no', __('教师工号'));
        $show->field('teacher.name', __('教师姓名'));
        $show->field('begin_at', __('开课日期'));
        $show->field('end_at', __('结课日期'));
        $show->field('place', __('上课地点'));
        $show->field('credit', __('学分'));
        $show->field('period', __('课时'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Course());

        $form->text('no', __('课程号'));
        $form->text('name', __('课程名'));
        $form->text('teacher_no', __('教师号'));
        $form->date('begin_at', __('开课日期'))->default(date('Y-m-d'));
        $form->date('end_at', __('结课日期'))->default(date('Y-m-d'));
        $form->text('place', __('上课地点'));
        $form->text('credit', __('学分'));
        $form->text('period', __('课时'));

        return $form;
    }
}
