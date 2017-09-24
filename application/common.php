<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function status($status){
	if ($status==1) {
		$str="<span class='label label-success radius'>正常</span>";
	}elseif ($status==0) {
		$str="<span class='label label-danger radius'>待审</span>";
	}else{
		$str="<span class='label label-danger radius'>删除</span>";
	}
	return $str;
}

//推荐位类别
function featuredType($type){
	if ($type==0) {
		$str="<span class='label label-success radius'>首页大图</span>";
	}elseif ($type==1) {
		$str="<span class='label label-danger radius'>右侧广告</span>";
	}else{
		$str="<span class='label label-danger radius'>其他</span>";
	}
	return $str;
}

/*
* param $url
* param $type 0->get 1->post
* param array $data
*/
function doCurl($url,$type=0,$data=[]){
	$ch=curl_init();//初始化
	// 2. 设置选项，包括URL
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_HEADER,0);

	if ($type==1) {
		//post
		curl_setopt($ch, CURLOPT_POST,true);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
	}
	// 3. 执行并获取HTML文档内容
	$output = curl_exec($ch);
	if($output === FALSE ){
		echo "CURL Error:".curl_error($ch);
	}
	// 4. 释放curl句柄
	curl_close($ch);

	return $output;
}

//商家申请入驻 将状态转为中文
function bisRegister($status){
	switch ($status) {
		case 1:
		return "审核通过,您已经入驻成功了,开始吧~~~";
		break;

		case 0:
		return "还在审核中,再等一等呗~~~";
		break;

		case 2:
		return "您的申请不通过,请核对资料后重新提交申请吧~~~";
		break;
		
		default:
		return "您的申请被删除了,我也不知道为什么还有个2,这不是和0一样吗";
		break;
	}
}


//跟分页有关的方法
function pagination($obj){
	if (!$obj) {
		return '';
	}
	$params=request()->param();
	return '<div class="cl pd-5 bg-1 bk-gray mt-20 tp5-o2o">'.$obj->appends($params)->render().'</div>';	
}

//处理二级城市分割
function getSeCityName($path){
	if (empty($path)) {
		return '';
	}
	if (preg_match('/,/',$path)) {
		$cityPath=explode(',',$path);
		$cityId=$cityPath[1];
	}else{
		$cityId=$path;
	}
	$city=model('City')->get(['id'=>$cityId]);
	return $city->name;
}

//处理二级分类
function getSeCategory($path){
	if (empty($path)) {
		return '';
	}
	$categorys=array();
	//1,2|3|4
	if (preg_match('/,/',$path)) {
		$categoryPath=explode(',',$path);//[1,'2|3|4']
		$sePath=$categoryPath[1];//2|3|4
		$seCategoryPath=explode('|',$sePath);//[2,3,4];
		foreach ($seCategoryPath as $key => $value) {
			$categoryObj=model('Category')->get(['id'=>$value]);
			$categorys[]=$categoryObj->toArray();
		}
	}else{
		$categoryId=$path;
		$categoryObj=model('Category')->get(['id'=>$categoryId]);
		$categorys[]=$categoryObj->toArray();
	}
	return $categorys;
}

//那个什么几店通用的
function countLocation($ids){
	if ($ids) {
		return 1;
	}

	if (preg_match('/,/',$ids)) {
		$arr=explode(',', $ids);
		return count($arr);
	}
}

//订单编号
function setOrderSn(){
	return time().rand(1000,9999);
	
	/*list($t1,$t2)=explode(' ', microtime());//用这个毫秒再加上随机数来避免重复
	//但是我觉得我写的已经不可能重复了。。
	//$t1=0.124334532454 $t2=435436453
	$t3=explode('.',$t1*10000);//[12234,54534]
	return $t2.$t3[0].rand(1000,9999);*/
}