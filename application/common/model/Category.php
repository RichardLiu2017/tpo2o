<?php
namespace app\common\model;

use think\Model;

class Category extends Model{
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
    public function getNormalFirstCategory(){
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

    public function getNormalCategoryByParentId($parent_id=0){
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

    //这个是在列表页展示用的方法
    public function getFirstCategory($parentId=0){
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

    public function getNormalRecommendCategoryByParentId($parent_id,$limit){
        $data=[
            'parent_id'=>$parent_id,
            'status'=>1,
        ];

        $order=[
            'listorder'=>'desc',
            'id'=>'asc'
        ];

        $result=$this->where($data)->order($order);

        if ($limit) {
            $result=$result->limit($limit);
        }

        return $result->select();
    }

    //根据parent_id取得二级分类数据
    public function getNormalCategoryParentId($parentId){
        //$parentId是个数组，把它弄成字符串，用in来查找
        $data=[
            'parent_id'=>['in',implode(',', $parentId)],
            'status'=>1,
        ];

        $order=[
            'listorder'=>'desc',
            'id'=>'asc'
        ];

        return $this->where($data)->order($order)->select();
    }

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
