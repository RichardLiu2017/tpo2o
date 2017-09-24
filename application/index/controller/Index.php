<?php
namespace app\index\controller;

use think\Controller;

use think\View;

class Index extends Base{
    public function index(){
    	//根据城市id拿到当前城市下的所有团购
    	$se_city_id=empty(input('get.city_id'))?2:input('get.city_id');//如果没有传二级城市id,那就拿苏州的东西

        $deals=$this->dealObj->getDealBySeCityId($se_city_id);
        $this->assign('deals',$deals);

        //得到团购列表，通过二级城市id和一级分类
        //先拿到categoryId是1的
        /*$deals=$this->dealObj->getNormalDealByCategoryCityId(1,$this->city->id);*/
        //获得四个子分类,填充到团购列表的上面
        $idEqualZeroCats=model('Category')->getNormalRecommendCategoryByParentId(0,7);
        //$this->assign('deals',$deals);
        $this->assign('idEqualZeroCats',$idEqualZeroCats);


    	//拿到推荐位
    	$feature_big=$this->featuredObj->getFeaturedByType(0);
    	$this->assign('feature_big',$feature_big);
        //dump($feature_big);exit();

        return $this->fetch();
    }

    
    
}
