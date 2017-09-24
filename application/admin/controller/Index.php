<?php
namespace app\admin\controller;

use think\Controller;

class Index extends Controller{
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

    public function testmap(){
    	return \Map::getLngLat("苏州市东港新村");
    	//{"status":0,"result":{"location":{"lng":120.6580365067257,"lat":31.32745406261561},"precise":0,"confidence":50,"level":"地产小区"}}
    }

    public function teststaticimage(){
    	/*$res=\Map::getLngLat("苏州市拙政园");
    	$res_decode=json_decode($res);
    	$lng=$res_decode->result->location->lng;
    	$lat=$res_decode->result->location->lat;
    	$center=$lng.",".$lat;
    	return \Map::staticimage($center);*/

    	return \Map::staticimage("苏州市东港新村");
    }

}
