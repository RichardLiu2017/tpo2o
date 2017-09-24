<?php
namespace app\common\model;

use think\Model;
use app\common\model\BaseModel;

class BisAccount extends BaseModel{
	/*protected $autoWriteTimestamp=true;//新增、修改时都会执行

    //增加数据
    public function add($data){
    	$data['status']=1;
    	// $data['create_time']=time();
    	$this->save($data);//返回的是影响的行数
        return $this->id;
    }

    //修改数据
    public function edit($data){
    	return $this->where('id','eq',$data['id'])->update($data);
    }*/

    
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
