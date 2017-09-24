<?php
namespace app\bis\controller;

use think\Controller;

use think\View;

class Login extends Controller{
	private $obj;

	public function _initialize(){
		$this->obj=model('BisAccount');
	}


	public function index(){
		if (request()->isPost()) {
			//登录的逻辑
			return $this->check();
		}else{
			//做判断,如果有session,代表已经登录了.就不用重复登录
			$account=session('bisAccount','','bis');
			//dump($account);//是一个bis_accoount记录(数组,因为我之前已经转了)
			if ($account && $account['id']) {
				return $this->redirect(url('index/index/index'));
			}
	        return $this->fetch();
		}
    }

    //检测用户名和密码
    private function check(){
    	$username=input('post.username');
    	$password=input('post.password');

    	$dataObj=$this->obj->getDataByUsername($username);

    	//如果对象为null,或者还处于审核状态,则不允许进入
    	if (empty($dataObj)) {
    		$this->error('用户名不存在');
    	}

    	if ($dataObj->status!=1) {
    		$this->error('您的申请还未通过,稍后再来吧~~~');
    	}

    	//用户名存在,就要检测密码
    	$data=$dataObj->toArray();

    	//拿到加密的code
    	$code=$data['code'];
    	$psw_code=$password.$code;
    	$psw_md5=md5($psw_code);
    	if ($data['password']!=$psw_md5) {
    		$this->error('密码不正确');
    	}else{
    		//先要更新一下数据库里的last_login_ip & time等信息
    		$this->obj->updateById([
    			'last_login_time'=>time(),
    			'last_login_ip'=>$_SERVER["REMOTE_ADDR"],
    		],$data['id']);

    		//保存用户信息 bis是作用域
    		session('bisAccount',$data,'bis');

    		return $this->fetch('index/index');
    	}
    }

    //退出登录
    public function logout(){
    	//清除session
    	session(null,'bis');

    	return $this->redirect(url('bis/login/index'));
    }
}