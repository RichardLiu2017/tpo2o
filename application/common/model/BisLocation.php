<?php
namespace app\common\model;

use think\Model;
use app\common\model\BaseModel;

class BisLocation extends BaseModel{
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

    //根据bis_id拿到总店信息
    public function getBisLocationByBisId($bis_id){
        $data=[
            //'status'=>['neq',-1],//状态不是-1
            'bis_id'=>$bis_id,
            'is_main'=>1,
        ];

        return $this->get($data);//数组
    }

    //拿到所有的店面信息
    public function getBisLocationsByBisId($bis_id){
        $data=[
            'bis_id'=>$bis_id,
        ];
        $order=['id'=>'desc'];

        return $this->where($data)
                    ->order($order)
                    ->select();
    }

    //拿到所有的店面信息
    public function getBisLocationsById($id){
        $data=[
            'id'=>$id,
        ];
        // $order=['id'=>'desc'];

        return $this->get($data);
    }

    /*根据状态得到商家列表
    * @param $status 状态-1 0 1 2
    * @return 数组,所有商家列表
    */
    public function getBisLocationsLists($status){
        $data=[
            'status'=>$status,
        ];
        $order=[
            'id'=>'desc',
        ];
        return $this->where($data)
                    ->order($order)
                    ->paginate(10);//分页
    }
}
