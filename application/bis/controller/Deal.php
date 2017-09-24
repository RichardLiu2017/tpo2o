<?php
namespace app\bis\controller;

use think\Controller;

use think\View;

use app\bis\controller\Base;

class Deal extends Base{
    public function index(){
        $deals=model('Deal')->getDealsByAccount($this->account['id']);
        //dump($locations);
        $this->assign('deals',$deals);
        return $this->fetch();
    }

    //新增团购
    public function add(){
        //获取一级城市列表
        $citys=model('City')->getNormalCityByParentId();
        $this->assign('citys',$citys);

        //获取一级分类列表
        $categorys=model('Category')->getNormalFirstCategory();
        $this->assign('categorys',$categorys);

        //获取当前账号下的所有门店
        $bis_id=$this->account['bis_id'];
        $locations=model('BisLocation')->getBisLocationsByBisId($bis_id);
        $this->assign('locations',$locations);
        
        return $this->fetch();
    }

    //增加一个团购 add
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
            array (size=16)
              'name' => string 'test' (length=4)
              'city_id' => string '1' (length=1)
              'se_city_id' => string '2' (length=1)
              'category_id' => string '9' (length=1)
              'se_category_id' => 
                array (size=1)
                  0 => string '13' (length=2)
              'location_ids' => 
                array (size=3)
                  0 => string '7' (length=1)
                  1 => string '6' (length=1)
                  2 => string '4' (length=1)
              'image' => string '/upload\20170920\a3a1bea4f937353ea95a717fc2abecc3.jpg' (length=53)
              'start_time' => string '2017-09-20 19:28' (length=16)
              'end_time' => string '2017-09-30 19:28' (length=16)
              'total_count' => string '500' (length=3)
              'origin_price' => string '999' (length=3)
              'current_price' => string '998' (length=3)
              'coupons_begin_time' => string '2017-09-20 19:28' (length=16)
              'coupons_end_time' => string '2017-09-30 19:28' (length=16)
              'description' => string '<p>test</p>' (length=11)
              'notes' => string '<p>test</p>' (length=11)
        */

        //商家店面信息验证
        /*$validate=validate('BisLocation');
        if (!$validate->scene('location')->check($data)) {
            $this->error($validate->getError());
        }*/

        //获取经纬度
        $address=model('BisLocation')->get($data['location_ids'][0]);

        //发到Bis_location model层
        
        $data_deal=[
            'name'=>$data['name'],
            'category_id'=>$data['category_id'],
            'se_category_id'=>empty($data['se_category_id'])?'':implode(',',$data['se_category_id']),
            'bis_id'=>$this->account['bis_id'],
            'location_ids'=>empty($data['location_ids'])?'':implode(',',$data['location_ids']),
            'image'=>$data['image'],
            'description'=>empty($data['description'])?'':$data['description'],
            'start_time'=>strtotime($data['start_time']),
            'end_time'=>strtotime($data['end_time']),
            'origin_price'=>$data['origin_price'],
            'current_price'=>$data['current_price'],
            'city_id'=>$data['city_id'],
            'se_city_id'=>$data['se_city_id'],
            'buy_count'=>0,
            'total_count'=>$data['total_count'],
            'coupons_begin_time'=>strtotime($data['coupons_begin_time']),
            'coupons_end_time'=>strtotime($data['coupons_end_time']),
            'xpoint'=>$address->xpoint,
            'ypoint'=>$address->ypoint,
            'bis_account_id'=>$this->account['id'],
            'balance_price'=>0.00,
            'notes'=>empty($data['notes'])?'':$data['notes'],
        ];
        $res_deal=model('Deal')->add($data_deal);
        //echo $res_bis_location."<br>";
        
        if (!$res_deal) {
            $this->error('团购保存失败');
        }else{
            $this->success("团购保存成功",url('bis/deal/index'));
        }
    }

}
