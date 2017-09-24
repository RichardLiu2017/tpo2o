<?php
namespace app\bis\controller;

use think\Controller;

use think\View;

use app\bis\controller\Base;

class Index extends Base{
    public function index(){
        return $this->fetch();
    }

    public function welcome(){
    	/*$title='nihao';
    	$content='Hellotest';
    	\phpmailer\Email::send('313291039@qq.com','Chihe Liu',$title,$content);
    	return 'success sent';*/
    	return "welcome to the backstage";
    }

    /*//退出登录
    public function logout(){
    	//清除session
    	session(null);

    	return $this->fetch('login/index');
    }*/

}
