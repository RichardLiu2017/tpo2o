<?php
namespace app\api\controller;

use think\Controller;

use think\View;

class City extends Controller{
	private $obj;

	// 初始化
	public function _initialize(){
		$this->obj=model('City');
	}

	//显示主页
    public function index(){
    	
    }

    //Ajax获得二级城市
    public function getCityByParentId(){
    	$id=input('post.city_id',0,'intval');//这个id是父级城市的id,也就是相应二级城市的parent_id
    	//dump($id);
    	if (!$id) {
    		$this->error('id不合法');
    	}

    	//得到二级城市列表
    	$se_citys=$this->obj->getNormalCityByParentId($id);
    	//dump($se_citys);

    	/*if ($se_citys) {
    		$this->result($_SERVER['HTTP_REFERER'],1,'success');
    	}else{
    		$this->result($_SERVER['HTTP_REFERER'],0,'fail');
    	}*/

    	//为了让代码更具复用性,所以用了自定义的方法
    	//show()在app/common.php里
    	if (!$se_citys) {
    		return show(0,'error');
    	}
		return show(1,'success',$se_citys);
    	
    	
    }
}
