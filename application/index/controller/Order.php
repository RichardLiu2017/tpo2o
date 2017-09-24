<?php
namespace app\index\controller;

use think\Controller;

use think\View;

class Order extends Base{
	public function confirm(){
		if (!session('o2o_user','','o2o')) {
			$this->error("请先登录",url('index/User/login'));
		}

		//获取用户信息
		$user=session('o2o_user','','o2o');

		$id=input('get.id',0,'intval');//买的团购的id

		$count=input('get.count',1,'intval');//买了几个

		$dealObj=model('Deal')->get($id);//团购商品对象
		$deal=$dealObj->toArray();
		$this->assign('deal',$deal);
		$this->assign('count',$count);
		$this->assign('user',$user);

        return $this->fetch('',['controller'=>'pay']);
    }

    //保存订单信息
    public function index(){
    	$id=input('get.id',0,'intval');//买的团购的id
    	$count=input('get.count',0,'intval');//买了几个
    	$total_price=input('get.total_price',0,'intval');//总价
    	//dump($id.",".$count.",".$total_price);exit;

    	//拿到团购信息 我也不知道入库要这个干嘛
    	$dealObj=model('Deal')->get($id);//团购商品对象
		$deal=$dealObj->toArray();

    	//根据session拿到用户信息
    	if (!session('o2o_user','','o2o')) {
			$this->error("请先登录",url('index/User/login'));
		}
    	$user=session('o2o_user','','o2o');
    	//dump($user);exit;
    	$user_id=$user['id'];
    	$user_name=$user['username'];
    	//dump($user_name.",".$user_id);exit;

    	//获取上一个页面的地址
    	if (empty($_SERVER['HTTP_REFERER'])) {
    		$this->error("请求不合法",url('index/index/index'));
    	}else{
    		$referer=$_SERVER['HTTP_REFERER'];
    	}
    	//dump($referer);

    	//获取订单
    	$orderSn=setOrderSn();
    	//dump($orderSn);exit;

    	//入库
    	$data=[];
    	$data['deal_id']=$id;
    	$data['deal_count']=$count;
    	$data['total_price']=$total_price;
    	$data['user_id']=$user_id;
    	$data['user_name']=$user_name;
    	$data['referer']=$referer;
    	$data['out_trade_no']=$orderSn;

    	try{
	    	$orderId=model('Order')->add($data);
    	}catch(\Exception $e){
    		$this->error('订单处理失败');
    	}

    	if ($orderId) {
    		$this->success("下单成功",url('index/pay/index',['id'=>$orderId]));
    	}else{
    		$this->error("下单失败");
    	}
    }
    
}
