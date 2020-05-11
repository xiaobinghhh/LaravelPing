<?php

namespace App\Admin\Controllers;

use App\Application\Teacher;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class TeacherController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '老师';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Teacher());

//        $grid->column('id', __('Id'));
        $grid->column('no', __('工号'));
        $grid->column('name', __('姓名'));
        $grid->column('office', __('办公室'));

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
        $show = new Show(Teacher::findOrFail($id));

//        $show->field('id', __('Id'));
        $show->field('no', __('工号'));
        $show->field('name', __('姓名'));
        $show->field('office', __('办公室'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Teacher());

        $form->text('no', __('工号'));
        $form->text('name', __('姓名'));
        $form->text('office', __('办公室'));

        return $form;
    }
}
