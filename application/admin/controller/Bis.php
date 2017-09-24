<?php
namespace app\admin\controller;

use think\Controller;
use think\View;

class Bis extends Controller{
    private $obj;
    private $cityObj;
    private $bisLocationObj;
    private $bisAccountObj;

    public function _initialize(){
        $this->obj=model('Bis');
        $this->cityObj=model('City');
        $this->bisLocationObj=model('BisLocation');
        $this->bisAccountObj=model('BisAccount');
    }

    //通过的商家
    public function index(){
        $bisLists=$this->obj->getBisLists(1);
        $this->assign('bisLists',$bisLists);
        return $this->fetch();
    }

    //入驻申请
    public function apply(){
        $applyBisLists=$this->obj->getBisLists(0);
        $this->assign('applyBisLists',$applyBisLists);
        return $this->fetch();
    }

    //删除的商家
    public function dellist(){
        $delBisLists=$this->obj->getBisLists(-1);
        $this->assign('delBisLists',$delBisLists);
        return $this->fetch();
    }

    //不通过的商家
    public function disapprove(){
        $delBisLists=$this->obj->getBisLists(2);
        $this->assign('delBisLists',$delBisLists);
        return $this->fetch();
    }

    //查看申请详情
    public function detail(){
        $id=input('get.id');
        //首先拿到bis表里的记录
        $detail=$this->obj->getBisDetailById($id);//是个对象
        //dump($detail);
        $data=$detail->toArray();
        //dump($data);
        $this->assign('data',$data);

        //根据城市id 拿到对应的城市数据
        $firstCityId=$data['city_id'];
        $firstCityDataObj=$this->cityObj->getCityById($firstCityId);
        $firstCityData=$firstCityDataObj->toArray();

        //但是,蛋疼的是数据库里没有二级城市字段,所以只能根据city_path进行处理
        $cityPath=$data['city_path'];//1,2
        $secondCityId=trim(strrchr($cityPath, ','),',');
        $secondCityDataObj=$this->cityObj->getCityById($secondCityId);
        $secondCityData=$secondCityDataObj->toArray();

        $this->assign('firstCityData',$firstCityData);
        $this->assign('secondCityData',$secondCityData);

        //拿到bis_location的记录
        $bis_id=$data['id'];
        $bisLocationDetailObj=$this->bisLocationObj->getBisLocationByBisId($bis_id);

        if ($bisLocationDetailObj) {
            $bisLocationDetail=$bisLocationDetailObj->toArray();

            //一级分类
            $firstCategoryObj=model('Category')->get(['id'=>$bisLocationDetail['category_id']]);
            $firstCategory=$firstCategoryObj->toArray();

            //二级分类
            $seCategorys=getSeCategory($bisLocationDetail['category_path']);
            //dump($categorys);

        }else{
            $bisLocationDetail='';
            $firstCategory='';
            $seCategorys=[];
        }
        $this->assign('bisLocationDetail',$bisLocationDetail);
        $this->assign('firstCategory',$firstCategory);
        $this->assign('seCategorys',$seCategorys);


        return $this->fetch();
    }

    //修改审核状态,包括删除
    public function status(){
        //dump(input('get.id'));//都是string

        //进行校验
        $data=input('get.');

        //修改状态
        $bisRes=$this->obj->save(['status'=>intval(input('get.status'))],['id'=>intval(input('get.id'))]);
        $bisLocationRes=$this->bisLocationObj->save(['status'=>intval(input('get.status'))],['bis_id'=>intval(input('get.id')),'is_main'=>1]);
        $bisAccountRes=$this->bisAccountObj->save(['status'=>intval(input('get.status'))],['bis_id'=>intval(input('get.id')),'is_main'=>1]);

        if($bisRes && $bisLocationRes && $bisAccountRes){
            //给状态修改成功的商家发邮件
            $this->success("状态修改成功");
        }else{
            $this->error("状态修改失败");
        }

        /*echo '$bisRes'.$bisRes."<br>";
        echo '$bisLocationRes'.$bisLocationRes."<br>";
        echo '$bisAccountRes'.$bisAccountRes."<br>";*/
    }
    

}
