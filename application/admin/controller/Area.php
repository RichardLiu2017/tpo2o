<?php
namespace app\admin\controller;

use think\Controller;

use app\common\model\City;

class Area extends Controller{
	private $areaObj;
	private $cityObj;//因为要和城市绑定,所以要有city模块

	public function _initialize(){
		$this->areaObj=model('Area');
		$this->cityObj=model('City');
	}

	/*index.html里的展示部分
	* 拿到所有的一级商圈
	* 还需要把city_id变成中文
	* 这就需要把city表拿过来
	* 分配到页面中
	*/
	public function index(){
		$parentId=input('get.parent_id',0,'intval');//第二个参数是默认值
		$areas=$this->areaObj->getFirstArea($parentId);

		//这里需要把$areas里的city_id换成$se_citys里的name
		//dump($areas);
		foreach ($areas as $key => $value) {
			$id=$value['city_id'];
			//dump($id);

			$se_citys=City::get($id);
			$se_citys=$se_citys->toArray();
			//dump($se_citys);

			//这个貌似不行
			/*$se_citys=$this->cityObj->getSecondCity($id);
			$se_citys=$se_citys->toArray();*/

			$value['city_id']=$se_citys['name'];
		}

		$this->assign('areas',$areas);
		return $this->fetch();
	}

	/*index.html里点击添加商圈之后的动作
	* 拿到所有的商圈分类
	* 拿到所以的子城市
	* 分配到页面中
	*/
	public function add(){
		$areas=$this->areaObj->getNormalFirstArea();
		$se_citys=$this->cityObj->getNormalSecondCity();
		$this->assign('areas',$areas);
		$this->assign('se_citys',$se_citys);
		return $this->fetch();
	}

	/*add.html里点击保存之后的动作
	* 拿到所有的post数据
	* 进行验证
	* 提交到area模块中,保存进数据库
	*/
	public function save(){
		// dump($_POST);
		$data=input('post.');

		$validate=validate('Area');
		if (!$validate->scene('add')->check($data)) {
			$this->error($validate->getError());
		}

		//进行判断,如果有post过来id则为修改;否则为新增
		if (empty($_POST['id'])) {
			$res=$this->areaObj->add($data);
			if ($res) {
				$this->success("添加成功");
			}else{
				$this->error("添加失败");
			}
		}else{
			$res=$this->areaObj->edit($data);
			if ($res) {
				$this->success("修改成功");
			}else{
				$this->error("修改失败");
			}
		}
	}

	public function edit(){
		//首先获得get方法过来的id
		$id=input('get.id');
		//再获得这个id的记录
		$area=$this->areaObj->get($id);
		$this->assign('area',$area);
		//获取所有的商圈分类和子城市
		$areas=$this->areaObj->getNormalFirstArea();
		$se_citys=$this->cityObj->getNormalSecondCity();
		$this->assign('areas',$areas);
		$this->assign('se_citys',$se_citys);
		return $this->fetch('area/edit');
	}

	public function status(){
		//dump(input('get.id'));//都是string

    	//进行校验
    	$data=input('get.');
    	$validate=validate('Area');
    	if (!$validate->scene('status')->check($data)) {
    		$this->error($validate->getError());
    	}

    	//修改状态
    	$res=$this->areaObj->save(['status'=>intval(input('get.status'))],['id'=>intval(input('get.id'))]);
    	if($res){
    		$this->success("状态修改成功");
    	}else{
    		$this->error("状态修改失败");
    	}
	}

	//排序 根据listorder排序(用了ajax)
    public function listorder($id,$listorder){
    	/*echo $id."<br>";
    	echo $listorder."<br>";*/

    	//原理:先更新数据库里id=$id的记录的listorder
    	//然后在获取所有的parent_id=0的记录,以listorder排序
    	/*$data=$this->obj->get($id);
    	$data['listorder']=$listorder;
    	$res=$this->obj->save($data,['id'=>$id]);*/
    	$res=$this->areaObj->save(['listorder'=>$listorder],['id'=>$id]);

    	//echo $res;

    	if ($res) {
    		$this->result($_SERVER['HTTP_REFERER'],1,'success');
    	}else{
    		$this->result($_SERVER['HTTP_REFERER'],0,'fail');
    	}
    	/*
			code	1
			msg	success
			time	1505744857
			data	http://lch.com/admin/category/index.html
    	*/

		//以listorder排序已经在$this->obj->getFirstCategory()实现
    }
}
?>