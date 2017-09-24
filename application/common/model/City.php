<?php
namespace app\common\model;

use think\Model;

class City extends Model{
	protected $autoWriteTimestamp=true;//新增、修改时都会执行

    //增加数据
    public function add($data){
    	$data['status']=1;
    	// $data['create_time']=time();
    	return $this->save($data);//返回的是影响的行数
    }

    //修改数据
    public function edit($data){
    	return $this->where('id','eq',$data['id'])->update($data);
    }

    //获取所有的状态正常的一级目录
    //这个是进行新增\修改时用的方法
    public function getNormalFirstCity(){
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

    public function getNormalCityByParentId($parent_id=0){
        $data=[
            'status'=>1,
            'parent_id'=>$parent_id
        ];

        $order=[
            'id'=>'desc'
        ];

        return $this->where($data)
                    ->order($order)
                    ->select();
    }

    //为了方便在添加商圈的时候,可以和城市绑定,所以要拿到所有状态正常的子城市
    public function getNormalSecondCity(){
        $data=[
            'status'=>1,
            'parent_id'=>['neq',0]
        ];

        $order=[
            'parent_id'=>'desc',
            'id'=>'desc',
        ];

        return $this->where($data)
                    ->order($order)
                    ->select();
    }


    //这个是在列表页展示用的方法
    public function getFirstCity($parentId=0){
    	$data=[
    		//'status'=>['neq',-1],//状态不是-1
    		'parent_id'=>$parentId
    	];

    	$order=[
    		'listorder'=>'desc',
    		'id'=>'desc'
    	];

    	return $this->where($data)
    		 		->order($order)
    		 		->paginate(15);//分页,默认就是15,在配置里
    }

    //这个是在area列表页展示用的方法
    //要拿到id=$id的某个特定的城市
    public function getCityById($id){
        $data=[
            //'status'=>['neq',-1],//状态不是-1
            'id'=>$id
        ];

        return $this->get($data);//数组
    }

    //public function 

    //更改状态代码
    //写在common.php里
    /*public function getStatusAttr($value){
        switch ($value) {
        	case 1:
        		return "正常";
        		break;

        	case 0:
        		return "待审核";
        		break;
        	
        	default:
        		return "删除";
        		break;
        }
    }*/
    
}
