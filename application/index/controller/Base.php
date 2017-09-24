<?php
namespace app\index\controller;

use think\Controller;

use think\View;

class Base extends Controller{
	protected $cityObj;
	protected $dealObj;
	protected $featuredObj;
    public $account;
    public $city = '';

	public function _initialize(){
		$this->cityObj=model('City');
		$this->dealObj=model('Deal');
		$this->featuredObj=model('Featured');

        //获取城市数据
    	$citys=$this->cityObj->getNormalSecondCity();
        $this->getCitys($citys);
        $this->assign('citys',$citys);
    	//dump($citys);exit();

        //获取用户数据
        $account=$this->getLoginUser();
        $user=$account?$account:'';
        $this->assign('user',$user);

        //获取一级分类的数据
        $cats=$this->getRecommendCats();
        $this->assign('cats',$cats);

        //分配不同css
        $this->assign('controller',strtolower(request()->controller()));
    }

    //在页面左上角显示默认城市或者点击的城市
    public function getCitys($citys){
        foreach ($citys as $key => $value) {
            $value=$value->toArray();
            if ($value['is_default']==1) {
                $defaultName=$value['name'];
                break;
            }
        }

        $defaultName=$defaultName?$defaultName:'苏州';

        //验证session是否存在
        if (session('cityName','','o2o') && !input('get.city')) {
            $cityName=session('cityName','','o2o');
        }else{
            $cityName=input('get.city',$defaultName,'trim');
            session('cityName',$cityName,'o2o');
        }

        $this->city = model('City')->where(['name'=>$cityName])->find();
        //dump($this->city);exit;

        $this->assign('cityName',$cityName);
    }

    protected function getLoginUser(){
        if (!$this->account) {
            $this->account=session('o2o_user','','o2o');
        }
        return $this->account;
    }

    //获得首页推荐分类数据
    public function getRecommendCats(){
        $parentIds=$sedCatArr=$recomCats=[];
        $cats=model('Category')->getNormalRecommendCategoryByParentId(0,5);//获取一级分类，获取5条
        //dump($cats);

        foreach ($cats as $key => $value) {
            $parentIds[]=$value->id;
        }
        //dump($parentIds);exit();

        //获取二级分类数据
        $sedCats=model('Category')->getNormalCategoryParentId($parentIds);

        foreach ($sedCats as $key => $value) {
            $sedCatArr[$value->parent_id][]=[
                'id'=>$value->id,
                'name'=>$value->name
            ]; 
        }
        //dump($sedCatArr);

        foreach ($cats as $key => $value) {
            //将二级分类数据和他爹绑定
            // recomCats 代表是一级 和 二级数据，  []第一个参数是 一级分类的name, 第二个参数 是 此一级分类下面的所有二级分类数据
             $recomCats[$value->id]=[$value->name,empty($sedCatArr[$value->id])?[]:$sedCatArr[$value->id]]; 
        }
        //dump($recomCats);exit;

        return $recomCats;
    }

    
    
}
