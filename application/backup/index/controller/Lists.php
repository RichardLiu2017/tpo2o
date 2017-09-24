<?php
namespace app\index\controller;

use think\Controller;

use think\View;

class Lists extends Controller{
	public function index(){
        return $this->fetch('lists');
    }

    
    
}
