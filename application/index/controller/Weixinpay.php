<?php
namespace app\index\controller;

use think\Controller;

use think\View;

class Weixinpay extends Contoller{
	public function notify(){
		//test
		$weixinData=file_get_contents("php://input");
		file_put_contents('/tmp/2.txt',$weixinData,FILE_APPEND);
    }
}
