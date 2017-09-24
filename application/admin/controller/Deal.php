<?php
namespace app\admin\controller;

use think\Controller;

class Deal extends Controller{
    public function index(){
        //筛选
        //获取一级城市列表
        $citys=model('City')->getNormalSecondCity();
        $this->assign('citys',$citys);

        //获取一级分类列表
        $categorys=model('Category')->getNormalFirstCategory();
        $this->assign('categorys',$categorys);

        //方便把城市和分类中文化
        $categoryArr=$cityArr=[];

        foreach ($categorys as $key => $value) {
            $categoryArr[$value->id]=$value->name;
        }

        foreach ($citys as $key => $value) {
            $cityArr[$value->id]=$value->name;
        }

        $this->assign('categoryArr',$categoryArr);
        $this->assign('cityArr',$cityArr);

        //如果有post过来,就是要筛选
        $data=input('post.');
        $sdata=[];

        if (!empty($data['start_time']) && !empty($data['end_time']) && (strtotime($data['end_time']) > strtotime($data['start_time']))) {
            $sdata['create_time']=[
                ['gt',strtotime($data['start_time'])],
                ['lt',strtotime($data['end_time'])],
            ];
        }

        if (!empty($data['category_id'])) {
            $sdata['category_id']=$data['category_id'];
        }

        if (!empty($data['se_city_id'])) {
            $sdata['se_city_id']=$data['se_city_id'];
        }

        if (!empty($data['name'])) {
            $sdata['name']=['like','%'.$data['name'].'%'];
        }

    	$deals=model('Deal')->getDealsByCondition($sdata,1);
        $this->assign('deals',$deals);

        //让搜索那地方不会在点了搜索之后就消失
        $category_id=empty($data['category_id'])?'':$data['category_id'];
        $se_city_id=empty($data['se_city_id'])?'':$data['se_city_id'];
        $name=empty($data['name'])?'':$data['name'];
        $start_time=empty($data['start_time'])?'':$data['start_time'];
        $end_time=empty($data['end_time'])?'':$data['end_time'];

        $this->assign('category_id',$category_id);
        $this->assign('se_city_id',$se_city_id);
        $this->assign('name',$name);
        $this->assign('start_time',$start_time);
        $this->assign('end_time',$end_time);
        
        return $this->fetch();
    }

    public function apply(){
        //筛选
        //获取一级城市列表
        $citys=model('City')->getNormalSecondCity();
        $this->assign('citys',$citys);

        //获取一级分类列表
        $categorys=model('Category')->getNormalFirstCategory();
        $this->assign('categorys',$categorys);

        //方便把城市和分类中文化
        $categoryArr=$cityArr=[];

        foreach ($categorys as $key => $value) {
            $categoryArr[$value->id]=$value->name;
        }

        foreach ($citys as $key => $value) {
            $cityArr[$value->id]=$value->name;
        }

        $this->assign('categoryArr',$categoryArr);
        $this->assign('cityArr',$cityArr);

        //如果有post过来,就是要筛选
        $data=input('post.');
        $sdata=[];

        if (!empty($data['start_time']) && !empty($data['end_time']) && (strtotime($data['end_time']) > strtotime($data['start_time']))) {
            $sdata['create_time']=[
                ['gt',strtotime($data['start_time'])],
                ['lt',strtotime($data['end_time'])],
            ];
        }

        if (!empty($data['category_id'])) {
            $sdata['category_id']=$data['category_id'];
        }

        if (!empty($data['se_city_id'])) {
            $sdata['se_city_id']=$data['se_city_id'];
        }

        if (!empty($data['name'])) {
            $sdata['name']=['like','%'.$data['name'].'%'];
        }

        $deals=model('Deal')->getDealsByCondition($sdata,0);
        $this->assign('deals',$deals);

        //让搜索那地方不会在点了搜索之后就消失
        $category_id=empty($data['category_id'])?'':$data['category_id'];
        $se_city_id=empty($data['se_city_id'])?'':$data['se_city_id'];
        $name=empty($data['name'])?'':$data['name'];
        $start_time=empty($data['start_time'])?'':$data['start_time'];
        $end_time=empty($data['end_time'])?'':$data['end_time'];

        $this->assign('category_id',$category_id);
        $this->assign('se_city_id',$se_city_id);
        $this->assign('name',$name);
        $this->assign('start_time',$start_time);
        $this->assign('end_time',$end_time);
        
        return $this->fetch();
    }

    //修改状态
    public function status(){
        //dump(input('get.id'));//都是string

        //进行校验
        $data=input('get.');

        //修改状态
       
        $dealRes=model('Deal')->save(['status'=>intval(input('get.status'))],['id'=>intval(input('get.id'))]);
        
        if($dealRes){
            //给状态修改成功的商家发邮件
            $this->success("状态修改成功");
        }else{
            $this->error("状态修改失败");
        }
    }

    //查看申请详情
    public function detail(){
        $id=input('get.id');

        //拿到bis_location的记录
        $DealObj=model('Deal')->getDealById($id);

        $Deal=$DealObj->toArray();

        //处理一下门店信息
        $locationIds=$Deal['location_ids'];//1,2,3
        $locationIdsArr=explode(',', $locationIds);
        $locations=[];
        foreach ($locationIdsArr as $key => $value) {
        	$locationObj=model('BisLocation')->get(['id'=>$value]);
        	$locations[]=$locationObj->toArray();
        }
        //dump($locations);
        $this->assign('locations',$locations);

        //根据城市id 拿到对应的城市数据
        $firstCityId=$Deal['city_id'];
        $firstCityDataObj=model('City')->getCityById($firstCityId);
        $firstCityData=$firstCityDataObj->toArray();

        $secondCityId=$Deal['se_city_id'];//1,2
        $secondCityDataObj=model('City')->getCityById($secondCityId);
        $secondCityData=$secondCityDataObj->toArray();

        $this->assign('firstCityData',$firstCityData);
        $this->assign('secondCityData',$secondCityData);

        //一级分类
        $firstCategoryObj=model('Category')->get(['id'=>$Deal['category_id']]);
        $firstCategory=$firstCategoryObj->toArray();

        //二级分类
        $seCatIds=$Deal['se_category_id'];//1,2,3
        $seCatIdsArr=explode(',', $seCatIds);
        $seCategorys=[];
        foreach ($seCatIdsArr as $key => $value) {
        	$catObj=model('Category')->get(['id'=>$value]);
        	$seCategorys[]=$catObj->toArray();
        }

        
        $this->assign('Deal',$Deal);
        $this->assign('firstCategory',$firstCategory);
        $this->assign('seCategorys',$seCategorys);


        return $this->fetch();
    }
    

}
