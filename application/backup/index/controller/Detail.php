<?php
namespace app\index\controller;

use think\Controller;

use think\View;

class Detail extends Controller{
	public function index(){
        return $this->fetch('detail');
    }

    
    
}
