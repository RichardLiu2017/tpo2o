<?php
namespace app\common\model;

use think\Model;

//公共的model层
class Order extends BaseModel{
    //增加数据
    public function add($data){
        $data['status']=1;
        // $data['create_time']=time();
        $this->save($data);//返回的是影响的行数
        return $this->id;//返回主键id
    }

	public function getNormalOrdersByStatus($status=1){
        $data=[
            'status'=>$status,
            'pay_status'=>['neq',3],//3是删除的订单
        ];

        $order=[
            'id'=>'desc',
        ];

        return $this->where($data)->order($order)->paginate();
    }

    public function getDealOrdersByStatus($status=1){
        $data=[
            'status'=>$status,
            'pay_status'=>3,//3是删除的订单
        ];

        $order=[
            'id'=>'desc',
        ];

        return $this->where($data)->order($order)->paginate();
    }
}
