<?php
namespace app\api\controller;

use think\Controller;

use think\View;

class Category extends Controller{
	private $obj;

	// 初始化
	public function _initialize(){
		$this->obj=model('Category');
	}

	//显示主页
    public function index(){
    	
    }

    //Ajax获得二级城市
    public function getCategoryByParentId(){
    	$id=input('post.category_id',0,'intval');//这个id是父级城市的id,也就是相应二级城市的parent_id
    	//dump($id);
    	if (!$id) {
    		$this->error('id不合法');
    	}

    	//得到二级城市列表
    	$se_categorys=$this->obj->getNormalCategoryByParentId($id);
    	//dump($se_categorys);

    	/*if ($se_categorys) {
    		$this->result($_SERVER['HTTP_REFERER'],1,'success');
    	}else{
    		$this->result($_SERVER['HTTP_REFERER'],0,'fail');
    	}*/

    	//为了让代码更具复用性,所以用了自定义的方法
    	//show()在app/common.php里
    	if (!$se_categorys) {
    		return show(0,'error');
    	}
		return show(1,'success',$se_categorys);
    	
    	
    }
}
