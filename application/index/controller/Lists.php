<?php
namespace app\index\controller;

use think\Controller;

use think\View;

class Lists extends Base{
	public function index(){
		/*//首先拿到所有的一级分类
		$firstCats=model('Category')->getNormalFirstCategory();

		//其次拿到二级分类
		$seCats=model('Category')->

		//根据传的参数(一级分类id或者二级分类id)获得相应的团购
        return $this->fetch();*/

        $firstCatIds = [];
        // 思路 首先需要一级栏目
        $categorys = model("Category")->getNormalFirstCategory();

        foreach($categorys as $category) {
            $firstCatIds[] = $category->id;
        }

        $id = input('id', 0, 'intval');
        $data = [];

        // id=0 一级分类 二级分类 三种情况分别处理
        if(in_array($id, $firstCatIds)) { // 一级分类
            // todo
            $categoryParentId = $id;
            $data['category_id'] = $id;

        }elseif($id) { // 二级分类
            // 获取二级分类的数据
            $category = model('Category')->get($id);

            if(!$category || $category->status !=1) {
                $this->error('数据不合法');
            }

            $categoryParentId = $category->parent_id;
            $data['se_category_id'] = $id;

        }else{ // 0
            $categoryParentId = 0;
        }

        $sedcategorys = [];

        //获取父类下的所有 子分类
        if($categoryParentId) {
            $sedcategorys = model('Category')->getNormalCategoryByParentId($categoryParentId);
        }

        //城市相关
        $firstCityIds = [];
        $citys=model('City')->getNormalFirstCity();
        foreach($citys as $city) {
            $firstCityIds[] = $city->id;
        }

        $city_id = input('city_id', 0, 'intval');

        // city_id=0 一级分类 二级分类 三种情况分别处理
        if(in_array($city_id, $firstCityIds)) { // 一级分类
            // todo
            $cityParentId = $city_id;
            $data['city_id'] = $city_id;

        }elseif($city_id) { // 二级分类
            // 获取二级分类的数据
            $city = model('City')->get($city_id);

            if(!$city_id || $city->status !=1) {
                $this->error('数据不合法');
            }

            $cityParentId = $city->parent_id;
            $data['se_city_id'] = $city_id;

        }else{ // 0
            $cityParentId = 0;
        }

        $sedcitys = [];

        //获取父类下的所有 子分类
        if($cityParentId) {
            $sedcitys = model('City')->getNormalCityByParentId($cityParentId);
        }

        //现在一次只能根据分类或者城市来筛选，因为在选了另一个条件之后，第一个条件就被删掉了；所以我想是不是可以通过cookie或者session来传递筛选条件

        $orders = [];

        // 排序数据获取的逻辑
        $order_sales = input('order_sales','');
        $order_price = input('order_price','');
        $order_time = input('order_time','');

        if(!empty($order_sales)) {
            $orderflag = 'order_sales';
            $orders['order_sales'] = $order_sales;

        }elseif(!empty($order_price)) {
            $orderflag = 'order_price';
            $orders['order_price'] = $order_price;//这个地方默认写错了。注意修改下哦

        }elseif(!empty($order_time)) {
            $orderflag = 'order_time';
            $orders['order_time'] = $order_time;

        }else{
            $orderflag = '';
        }

        //Log::write('o2o-log-list-id'.$id, 'log');
        trace('o2o-log-list-id'.$id, 'log');
        //$data['se_city_id'] = $this->city->id; // add 这个是session里的城市数据

        // 根据上面条件来查询商品列表数据
        $deals = model('Deal')->getDealByConditions($data, $orders);
        //dump($data);dump($orders);dump($deals);exit;

        return $this->fetch('', [
            'categorys' => $categorys,
            'sedcategorys' => $sedcategorys,
            'id' => $id,
            'categoryParentId' => $categoryParentId,
            'orderflag' => $orderflag,
            'deals' => $deals,
            'citys'=>$citys,
            'sedcitys'=>$sedcitys,
            'city_id'=>$city_id,
            'cityParentId'=>$cityParentId,
        ]);
    }

    
    
}
