<?php
namespace app\common\model;

use think\Model;

class Deal extends Model{
	protected $autoWriteTimestamp=true;//新增、修改时都会执行

    //增加数据
    public function add($data){
    	$data['status']=0;
    	// $data['create_time']=time();
    	return $this->save($data);//返回的是影响的行数
    }

    //修改数据
    public function edit($data){
    	return $this->where('id','eq',$data['id'])->update($data);
    }

    //获取所有的状态正常的一级目录
    //这个是进行新增\修改时用的方法
    public function getDealsByAccount($account_id){
    	$data=[
    		'bis_account_id'=>$account_id
    	];

    	$order=[
    		'id'=>'desc'
    	];

    	return $this->where($data)
    		 		->order($order)
    		 		->select();
    }

    public function getDealsByStatus($status=1){
        $data=[
            'status'=>$status
        ];

        $order=[
            'id'=>'desc'
        ];

        return $this->where($data)
                    ->order($order)
                    ->select();
    }

    //根据id拿到团购信息
    public function getDealById($id){
        $data=[
            //'status'=>['neq',-1],//状态不是-1
            'id'=>$id,
        ];

        return $this->get($data);//数组
    }

    //根据se_city_id和状态拿到团购信息 默认是拿苏州的
    public function getDealBySeCityId($se_city_id=2){
        $data=[
            'status'=>1,
            'se_city_id'=>$se_city_id,
        ];

        $order=[
            'listorder'=>'desc',
            'id'=>'desc'
        ];

        return $this->where($data)->order($order)->select();
    }

    //根据条件查找数据
    public function getDealsByCondition($data=[],$status=1){
        $data['status']=$status;

        $order=[
            'id'=>'desc'
        ];

        //echo $this->getLastSql();

        return $this->where($data)
                    ->order($order)
                    ->paginate();
    }

    //根据city_id,分类id和状态拿到团购信息
    public function getNormalDealByCategoryCityId($categoryId,$seCityId,$limit=10){
        $data=[
            'status'=>1,
            'se_city_id'=>$seCityId,
            'category_id'=>$categoryId,
            //'end_time'=>['gt',time()],需要根据结束时间来查，但是我数据库里的结束时间是个字符串，之前把这个字段设为了varchar，所以这边就不做这步了
        ];

        $order=[
            'listorder'=>'desc',
            'id'=>'desc'
        ];

        $result=$this->where($data)->order($order);

        if ($limit) {
            $result=$result->limit($limit);
        }

        return $result->select();
    }

    public function getDealByConditions($data=[], $orders) {
        if(!empty($orders['order_sales'])) {
            $order['buy_count'] = 'desc';
        }
        if(!empty($orders['order_price'])) {
            $order['current_price'] = 'desc';
        }
        if(!empty($orders['order_time'])) {
            $order['create_time'] = 'desc';
        }
        $order['id'] = 'desc';
        

        //$datas[] = ' end_time> '.time();
        $datas[] = ' status= 1';

        if(!empty($data['se_category_id'])) {
            
            $datas[]="find_in_set(".$data['se_category_id'].",se_category_id)";
        }
        if(!empty($data['category_id'])) {
            
            $datas[]="category_id = ".$data['category_id'];
        }
        if(!empty($data['city_id'])) {
            
            $datas[]="city_id = ".$data['city_id'];
        }   
        if(!empty($data['se_city_id'])) {
            
            $datas[]="se_city_id = ".$data['se_city_id'];
        }

        
        $result = $this->where(implode(' AND ',$datas))
            ->order($order)
            ->paginate(10);
            
        //echo $this->getLastSql();

        return $result;
    }

    
    
}
