<?php
namespace app\admin\controller;

use think\Controller;

use think\View;

class Category extends Controller{
	private $obj;
	// 初始化
	public function _initialize(){
		$this->obj=model('Category');
	}

	//显示主页
    public function index(){
    	$parentId=input('get.parent_id',0,'intval');//第二个参数是默认值
    	$categorys=$this->obj->getFirstCategory($parentId);
    	$this->assign('categorys',$categorys);
        return $this->fetch();
    }

    //在新增分类的时候,获得父级分类,方便选择
    public function add(){
    	$categorys=$this->obj->getNormalFirstCategory();
    	$this->assign('categorys',$categorys);//view里面的方法
    	return $this->fetch();
    }

    //向数据库添加数据
    public function save(){
    	//dump($_POST);这个不安全
    	//dump(input('post.'));
    	//dump(request()->post());

    	$data=input('post.');
    	$validate=validate('Category');
    	if (!$validate->scene('add')->check($data)) {
    		$this->error($validate->getError());
    	}

    	//dump($data);

    	if (empty($_POST['id'])) {
    		//如果是空的,则为新增
    		//将数据传入model层
    		$res=$this->obj->add($data);//返回的是影响的行数
    		if($res){
    			$this->success("添加成功");
    		}else{
    			$this->error("添加失败");
    		}
    	}else{//否则为修改
    		//$this->update($data);

    		//将数据传入model层
    		$res=$this->obj->edit($data);//返回的是影响的行数
    		if($res){
    			$this->success("修改成功");
    		}else{
    			$this->error("修改失败");
    		}
    	}
    }

    //更新数据
    public function updata($data){
    	$res=$this->obj->save($data,['id'=>intval($data['id'])]);
    	if($res){
    		$this->success("修改成功");
    	}else{
    		$this->error("修改失败");
    	}
    }

    //编辑
    public function edit(){
    	//通过get方法拿到要修改的id
    	$id=input('get.id');
    	//echo $data;

    	//验证一下
    	$validate=validate('Category');
    	if (!$validate->scene('edit')->check($id)) {
    		$this->error($validate->getError());
    	}

    	//通过model得到之前的数据
    	$res=$this->obj->get($id);//这是一个对象
    	//dump($res);
    	$categorys=$this->obj->getNormalFirstCategory();
    	$this->assign('category',$res);
    	$this->assign('categorys',$categorys);
    	return $this->fetch('category/edit');
    	//其实edit.html比add.html就多了一个hidden的input
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
    	$res=$this->obj->save(['listorder'=>$listorder],['id'=>$id]);

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

    //修改分类状态,包括删除
    public function status(){
    	//dump(input('get.id'));//都是string

    	//进行校验
    	$data=input('get.');
    	$validate=validate('Category');
    	if (!$validate->scene('status')->check($data)) {
    		$this->error($validate->getError());
    	}

    	//修改状态
    	$res=$this->obj->save(['status'=>intval(input('get.status'))],['id'=>intval(input('get.id'))]);
    	if($res){
    		$this->success("状态修改成功");
    	}else{
    		$this->error("状态修改失败");
    	}

    	//有个问题,怎么把删掉的再弄回来?(不在数据库里改)
    }
}
