<?php
namespace app\common\model;

use think\Model;

//公共的model层
class User extends BaseModel{
	public function getUserByCondition($condition=[],$status){
        $condition['status']=$status;
        
        $order=[
            'id'=>'desc',
        ];

        $res=$this->where($condition)->order($order)->select();
        echo $this->getLastSql();
        return $res;
    }

    //增加数据 改写原方法
    public function add($data){
    	$data['status']=1;
    	// $data['create_time']=time();
    	$this->save($data);//返回的是影响的行数
        return $this->id;//返回主键id
    }

    /*根据用户名获取数据
    * @param $username用户名
    * @return 对象|''
    */
    public function getDataByUsername($username){
        if (empty($username)) {
            return '';
        }
        return $this->get(['username'=>$username]);
    }

    public function updateById($data,$id){
        //allowField(true)过滤data数组中非数据表的数据
        return $this->allowField(true)->save($data,['id'=>$id]);
    }
}
