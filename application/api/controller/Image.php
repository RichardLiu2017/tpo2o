<?php
namespace app\api\controller;

use think\Controller;
use think\Request;
use think\File;

class Image extends Controller{
	public function upload(){
		$file=Request::instance()->file('file');
		//给定一个目录
		$info=$file->move('upload');
		//dump($info);

		//如果有这个图片上传信息并且能够拿到上传的路径,则认为上传成功
		if ($info && $info->getPathname()) {
			return show(1,'success','/'.$info->getPathname());//将图片返回给前端显示
		}else{
			return show(0,'fail');
		}
	}
}