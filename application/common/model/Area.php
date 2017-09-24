<?php
namespace app\common\model;

use think\Model;

class Area extends Model{
	protected $autoWriteTimestamp=true;

	public function getNormalFirstArea(){
		$data=[
			'status'=>1,
			'parent_id'=>0
		];

		$order=[
			'id'=>'desc'
		];

		return $this->where($data)
					->order($order)
					->select();
	}

	public function getFirstArea($parentId=0){
		$data=[
			//'status'=>1,
			'parent_id'=>$parentId
		];

		$order=[
			'listorder'=>'desc',
			'id'=>'desc'
		];

		return $this->where($data)
					->order($order)
					->paginate(15);
	}

	//添加数据
	public function add($data){
		//因为我想状态就默认是0(待审核),所以就不用给$data加一个状态字段了
		//$data['status']=1;

		return $this->save($data);
	}

	//修改数据
	public function edit($data){
		//因为我想状态就默认是0(待审核),所以就不用给$data加一个状态字段了
		//$data['status']=1;

		return $this->save($data,['id'=>$data['id']]);
	}
}
?>