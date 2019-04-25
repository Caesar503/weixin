<?php

namespace App\Admin\Controllers;

use App\Model\Order;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class OrderController extends Controller
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
        $grid = new Grid(new Order);

        $grid->id('Id');
        $grid->order_amount('订单总价');
        $grid->order_sn('订单号');
        $grid->uid('用户id');
        $grid->session_id('Session id');
        $grid->pay_status('支付状态');
        $grid->pay_time('支付时间');
        $grid->is_status('订单状态');

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
        $show = new Show(Order::findOrFail($id));

        $show->id('Id');
        $show->order_amount('Order amount');
        $show->order_sn('Order sn');
        $show->uid('Uid');
        $show->session_id('Session id');
        $show->pay_status('Pay status');
        $show->pay_time('Pay time');
        $show->is_status('Is status');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Order);

        $form->text('order_amount', 'Order amount');
        $form->text('order_sn', 'Order sn');
        $form->number('uid', 'Uid');
        $form->text('session_id', 'Session id');
        $form->switch('pay_status', 'Pay status')->default(1);
        $form->number('pay_time', 'Pay time');
        $form->switch('is_status', 'Is status')->default(1);

        return $form;
    }
}
