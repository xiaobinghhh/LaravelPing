<?php

namespace App\Admin\Controllers;

use App\Application\Student;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class StudentController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '学生';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Student());

//        $grid->column('id', __('Id'));
        $grid->column('no', __('学号'));
        $grid->column('name', __('姓名'));
        $grid->column('college', __('学号'));
        $grid->column('major', __('专业'));
        $grid->column('grade', __('年级'));
        $grid->column('class', __('班级'));

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
        $show = new Show(Student::findOrFail($id));

//        $show->field('id', __('Id'));
        $show->field('no', __('学号'));
        $show->field('name', __('姓名'));
        $show->field('college', __('学院'));
        $show->field('major', __('专业'));
        $show->field('grade', __('年级'));
        $show->field('class', __('班级'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Student());

        $form->text('no', __('学号'));
        $form->text('name', __('姓名'));
        $form->text('college', __('学院'));
        $form->text('major', __('专业'));
        $form->text('grade', __('年级'));
        $form->text('class', __('班级'));

        return $form;
    }
}
