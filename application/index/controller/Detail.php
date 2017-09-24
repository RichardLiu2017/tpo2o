<?php
namespace app\index\controller;

use think\Controller;

use think\View;

class Detail extends Base{
	public function index($id){
		if (!intval($id)) {
			$this->error("id不合法");
		}

		//根据id查询商品数据
		$deal=$this->dealObj->getDealById($id);
		//dump($deal);exit;
		$this->assign('deal',$deal);
		//dump($deal['category_id']);exit;
		//顺便得到团购开始时间和当前时间的差值,顺便根据这个判断此时能不能购买(时间不到不能买，时间过了不能买)
		$flag=$cha=0;//flag 0=>时间正好，可以买 1=>还没开始，不能买 -1=>结束，不能买
		$startTime=$deal['start_time'];
		$endTime=$deal['end_time'];
		if ($startTime>time()) {
			$flag=1;//此时还没开始,不能购买，要展示还剩多少时间
			$cha=$startTime-time();
			$day=floor($cha/86400);//计算还差多少天 一天有86400秒
			$hour=floor(($cha-86400*$day)/3600);
			$min=floor(($cha-86400*$day-3600*$hour)/60);
			$second=floor(($cha-86400*$day-3600*$hour-60*$min)/1);
			$this->assign('day',$day);
			$this->assign('hour',$hour);
			$this->assign('min',$min);
			$this->assign('second',$second);
		}elseif (time()>$endTime) {
			$flag=-1;
		}else{
			$flag=0;
		}
		//dump($cha);exit;
		//$this->assign('cha',$cha);
		$this->assign('flag',$flag);

		//获取分类信息
		$categoryId=$deal['category_id'];//当前团购的一级分类
		$categoryObj=model('Category')->get($categoryId);
		$category=$categoryObj->toArray();
		//dump($category);exit;
		$this->assign('category',$category);

		//根据团购信息的bis_id获取商家信息
		$bisId=$deal['bis_id'];
		$bisObj=model('Bis')->get($bisId);
		$bis=$bisObj->toArray();
		//dump($bis);exit;
		$this->assign('bis',$bis);

		//根据商家id获取商家的分店信息(需要当前城市吗？)
		/*$locationsArr=model('BisLocation')->all(['bis_id'=>$bisId]);
		//dump($locationObj);exit;
		$locations=[];
		foreach ($locationsArr as $key => $value) {
			$locations[]=$value->toArray();
		}
		//dump($locations);exit;
		$this->assign('locations',$locations);*/

		//其实应该根据团购信息里的location_ids获取分店信息
		$locationIdsStr=$deal['location_ids'];//1,2
		$locationIdsArr=explode(',', $locationIdsStr);
		//根据这个数组里的每个元素去获取分店信息
		$locations=[];
		foreach ($locationIdsArr as $key => $value) {
			$locations[]=model('BisLocation')->get($value)->toArray();
		}
		//dump($locations);exit;
		$this->assign('locations',$locations);

		//传递经纬度，方便形成地图
		$mapstr=$locations[0]['xpoint'].','.$locations[0]['ypoint'];
		$this->assign('mapstr',$mapstr);
		//dump($mapstr);exit;

        return $this->fetch();
    }

    
    
}
