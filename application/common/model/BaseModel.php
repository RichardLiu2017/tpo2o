<?php
namespace app\common\model;

use think\Model;

//公共的model层
class BaseModel extends Model{
	protected $autoWriteTimestamp=true;//新增、修改时都会执行

    //增加数据
    public function add($data){
    	$data['status']=0;
    	// $data['create_time']=time();
    	$this->save($data);//返回的是影响的行数
        return $this->id;//返回主键id
    }

    //修改数据
    public function edit($data){
    	return $this->where('id','eq',$data['id'])->update($data);
    }
}
