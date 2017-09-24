<?php
namespace app\index\controller;

use think\Controller;

use think\View;

class Map extends Controller{
	public function getMapImage($address){
        return \Map::staticimage($address);
    }

    
    
}
