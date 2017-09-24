<?php
namespace app\common\model;

use think\Model;
use app\common\model\BaseModel;

class Bis extends BaseModel{
	/*protected $autoWriteTimestamp=true;//新增、修改时都会执行

    //增加数据
    public function add($data){
    	$data['status']=1;
    	// $data['create_time']=time();
    	$this->save($data);//返回的是影响的行数
        return $this->id;//返回主键id
    }

    //修改数据
    public function edit($data){
    	return $this->where('id','eq',$data['id'])->update($data);
    }*/

    /*public function getStatusAttr($value){
        switch ($value) {
            case 1:
                return "通过";
                break;

            case 0:
                return "待审核";
                break;
            
            default:
                return "已删除";
                break;
        }
    }*/

    /*得到商家列表
    * @param $status 状态-1 0 1 2
    * @return 数组,所有商家列表
    */
    public function getBisLists($status){
        $data=[
            'status'=>$status
        ];
        $order=[
            'id'=>'desc',
        ];
        return $this->where($data)
                    ->order($order)
                    ->paginate(10);//分页
    }

    /*根据id得到某个商家列表
    * @param $id 
    * @return 对象,某个商家列表
    */
    public function getBisDetailById($id){
        $data=[
            'id'=>$id
        ];
        
        return $this->get($data);
    }
}
