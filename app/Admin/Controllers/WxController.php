<?php

namespace App\Admin\Controllers;

use App\Model\Weixin;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class WxController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('Index')
            ->description('description')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('Detail')
            ->description('description')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('Edit')
            ->description('description')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('Create')
            ->description('description')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Weixin);

        $grid->id('Id');
        $grid->openid('用户ID');
        $grid->nickname('昵称');
        $grid->sex('性别');
        $grid->headimgurl('头像');
        $grid->city('城市');
        $grid->province('省级');
        $grid->country('国籍');

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
        $show = new Show(Weixin::findOrFail($id));

        $show->id('Id');
        $show->openid('Openid');
        $show->nickname('Nickname');
        $show->sex('Sex');
        $show->headimgurl('Headimgurl');
        $show->city('City');
        $show->province('Province');
        $show->country('Country');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Weixin);

        $form->text('openid', 'Openid');
        $form->text('nickname', 'Nickname');
        $form->text('sex', 'Sex');
        $form->text('headimgurl', 'Headimgurl');
        $form->text('city', 'City');
        $form->text('province', 'Province');
        $form->text('country', 'Country');

        return $form;
    }
}
