<?php
namespace app\admin\controller;

use think\Controller;

use think\View;

class Order extends BaseController{
    public function index(){
        $orders=model('Order')->getNormalOrdersByStatus(1);//获取状态是1，支付状态不是删除(3)的订单
        $this->assign('orders',$orders);
        return $this->fetch();
    }

    public function dellist(){
        $orders=model('Order')->getDealOrdersByStatus(1);//获取状态是1，支付状态是删除(3)的订单
        $this->assign('orders',$orders);
        return $this->fetch();
    }
}
