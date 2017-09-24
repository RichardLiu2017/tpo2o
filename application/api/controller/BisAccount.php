<?php
namespace app\api\controller;

use think\Controller;

use think\View;

class BisAccount extends Controller{
	private $obj;

	// 初始化
	public function _initialize(){
		$this->obj=model('BisAccount');
	}

	//显示主页
    public function index(){
    	
    }

    //Ajax验证用户名是否存在
    public function checkExist(){
    	$username=input('post.username');
    	//dump($username);
    	if (!$username) {
    		$this->error('username不合法');
    	}

    	//比对数据库 如果有下面这个值,说明用户名存在
    	$usernameInDatabase=$this->obj->get(['username'=>$username]);
    	//dump($se_categorys);

    	/*if ($se_categorys) {
    		$this->result($_SERVER['HTTP_REFERER'],1,'success');
    	}else{
    		$this->result($_SERVER['HTTP_REFERER'],0,'fail');
    	}*/

    	//为了让代码更具复用性,所以用了自定义的方法
    	//show()在app/common.php里
    	if ($usernameInDatabase) {
    		return show(1,'用户名已存在,换一个呗~~~',$usernameInDatabase);
    	}else{
			return show(0,'没有这个用户名');
        }
    	
    	
    }
}
