<?php
namespace app\bis\controller;

use think\Controller;

use think\View;

use app\bis\controller\Base;

class Location extends Base{
    public function index(){
        $locations=model('BisLocation')->getBisLocationsByBisId($this->account['bis_id']);
        //dump($locations);
        $this->assign('locations',$locations);
        return $this->fetch();
    }

    //增加一个注册 add
    public function save(){
        //通过post拿到数据
        //验证一下
        //发到register model里,去存到数据库

        //如果数据不是通过post方法过来的,就报错
        if (!request()->isPost()) {
            $this->error('请求错误');
        }

        //获取表单的值
        $data=input('post.');
        //dump($data);
        //dump($data['name']);

        /*
            array (size=19)
              'name' => string 'test' (length=4)
              'city_id' => string '1' (length=1)
              'se_city_id' => string '2' (length=1)
              'logo' => string '' (length=0)
              'tel' => string '' (length=0)
              'contact' => string '' (length=0)
              'category_id' => string '7' (length=1)
              'se_category_id' => 
                array (size=1)
                  0 => string '15' (length=2)
              'address' => string '' (length=0)
              'open_time' => string '' (length=0)
        */

        //商家店面信息验证
        $validate=validate('BisLocation');
        if (!$validate->scene('location')->check($data)) {
            $this->error($validate->getError());
        }

        //获取经纬度
        $lnglat=\Map::getLngLat($data['address']);
        //dump($lnglat);
        /*
            array (size=2)
              'status' => int 0
              'result' => 
                array (size=4)
                  'location' => 
                    array (size=2)
                      'lng' => float 120.65803650673
                      'lat' => float 31.327454062616
                  'precise' => int 0 模糊匹配
                  'confidence' => int 50
                  'level' => string '地产小区' (length=12)
        */
        //根据匹配的精准度做下一步判断
        //但是貌似这个定位无法做到非常精确,所以就不去验证了
        /*if (empty($lnglat) || $lnglat['status']!=0 || $lnglat['result']['precise']!=1) {
            $this->error('无法获取数据,或者匹配精度不够');
        }*/

        //发到Bis_location model层
        //搞一下传过来的$data['se_category_id']
        $data['category']='';
        //如果不为空,就把这些子分类连起来
        if (!empty($data['se_category_id'])) {
            $data['category']=implode('|', $data['se_category_id']);
        }
        $data_bis_location=[
            'name'=>$data['name'],
            'logo'=>$data['logo'],
            'address'=>$data['address'],
            'tel'=>$data['tel'],
            'contact'=>$data['contact'],
            'xpoint'=>empty($lnglat['result']['location']['lng'])?'':$lnglat['result']['location']['lng'],
            'ypoint'=>empty($lnglat['result']['location']['lat'])?'':$lnglat['result']['location']['lat'],
            'bis_id'=>$this->account['bis_id'],
            'open_time'=>$data['open_time'],
            'content'=>empty($data['content'])?'':$data['content'],//如果有子城市,就把父级城市和子城市连起来
            'is_main'=>0,//代表默认是总店
            'api_address'=>$data['address'],//貌似应该就是地址
            'city_id'=>$data['city_id'],
            'city_path'=>empty($data['se_city_id'])?$data['city_id']:$data['city_id'].','.$data['se_city_id'],
            'category_id'=>$data['category_id'],
            'category_path'=>$data['category_id'].','.$data['category']
        ];
        $res_bis_location=model('BisLocation')->add($data_bis_location);
        //echo $res_bis_location."<br>";
        
        if (!$res_bis_location) {
            $this->error('分店保存失败');
        }else{
            $this->success("分店保存成功");
        }
    }

    //新增门店
    public function add(){
    	//获取一级城市列表
		$citys=model('City')->getNormalCityByParentId();
		$this->assign('citys',$citys);

		//获取一级分类列表
		$categorys=model('Category')->getNormalFirstCategory();
		$this->assign('categorys',$categorys);
		
    	return $this->fetch('location/add');
    }

}
