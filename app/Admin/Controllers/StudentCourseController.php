<?php

namespace App\Admin\Controllers;

use App\Application\StudentCourse;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class StudentCourseController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '选课';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new StudentCourse());

//        $grid->column('id', __('Id'));
        $grid->column('student.name', __('学生'));
        $grid->column('student_no', __('学号'));
        $grid->column('course', __('课程'))->display(function ($course) {
            return "<span class='label label-primary'>{$course['name']}</span>";
        });
        $grid->column('course_no', __('课程号'));
        $grid->column('course_score', __('课程成绩'));

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
        $show = new Show(StudentCourse::findOrFail($id));

//        $show->field('id', __('Id'));
        $show->field('student_no', __('学号'));
        $show->field('course_no', __('课程号'));
        $show->field('course_score', __('课程成绩'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new StudentCourse());

        $form->text('student_no', __('学号'));
        $form->text('course_no', __('课程号'));
        $form->decimal('course_score', __('课程成绩'))->default(0.00);

        return $form;
    }
}
