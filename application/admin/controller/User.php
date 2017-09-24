<?php
namespace app\admin\controller;

use think\Controller;

class User extends Controller{
	private $obj;

	public function _initialize(){
		$this->obj=model('User');
	}

    public function index(){
    	$condition=[];
    	$users=$this->obj->getUserByCondition($condition,1);
		$this->assign('users',$users);
        return $this->fetch();
    }

    public function del(){
    	$condition=[];
    	$users=$this->obj->getUserByCondition($condition,0);
		$this->assign('users',$users);
        return $this->fetch();
    }

    
    

}
