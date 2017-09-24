<?php
namespace app\common\model;

use think\Model;

//公共的model层
class Featured extends BaseModel{
	public function getFeaturedByCondition($condition=[],$status){
        $condition['status']=$status;
        
        $order=[
            'id'=>'desc',
        ];

        $res=$this->where($condition)->order($order)->select();
        echo $this->getLastSql();
        return $res;
    }

    //根据类别来拿推荐位
    public function getFeaturedByType($type){
    	$data=['type'=>$type];
    	$order=['id'=>'desc'];
    	return $this->where($data)->order($order)->select();
    }
}
