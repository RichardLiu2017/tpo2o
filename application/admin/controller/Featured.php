<?php
namespace app\admin\controller;

use think\Controller;

use think\View;

class Featured extends Controller{
	private $obj;

	public function _initialize(){
		$this->obj=model('Featured');
	}

    public function index(){
    	//获取推荐位类别
    	$types=config('featured.featured_type');
    	//dump($types);
    	$this->assign('types',$types);

    	$condition=[];

    	//判断有没有搜索
    	if (isset($_GET['type'])) {
    		$type=input('get.type');
    		$condition['type']=$type;
    	}else{

    	}
		$featureds=$this->obj->getFeaturedByCondition($condition,1);

		$this->assign('featureds',$featureds);

        return $this->fetch();
    }

    //审核
    public function apply(){
    	//获取推荐位类别
    	$types=config('featured.featured_type');
    	//dump($types);
    	$this->assign('types',$types);

    	$condition=[];

    	//判断有没有搜索
    	if (isset($_GET['type'])) {
    		$type=input('get.type');
    		$condition['type']=$type;
    	}else{

    	}
		$featureds=$this->obj->getFeaturedByCondition($condition,0);
		//dump($featureds);

		$this->assign('featureds',$featureds);

        return $this->fetch();
    }

    //删除的推荐位
    public function del(){
    	//获取推荐位类别
    	$types=config('featured.featured_type');
    	//dump($types);
    	$this->assign('types',$types);

    	$condition=[];

    	//判断有没有搜索
    	if (isset($_GET['type'])) {
    		$type=input('get.type');
    		$condition['type']=$type;
    	}else{

    	}
		$featureds=$this->obj->getFeaturedByCondition($condition,-1);

		$this->assign('featureds',$featureds);

        return $this->fetch();
    }

    public function add(){
    	//获取推荐位类别
    	$types=config('featured.featured_type');
    	//dump($types);
    	$this->assign('types',$types);

        return $this->fetch();
    }

    public function save(){
        $data=input('post.','','htmlentities');
        //dump($data);
        /*
			array (size=5)
			  'title' => string 'test' (length=4)
			  'image' => string '/upload\20170921\c90390dfd72a82e3d07026a850fee77e.jpg' (length=53)
			  'type' => string '0' (length=1)
			  'url' => string 'test' (length=4)
			  'description' => string 'test' (length=4)
        */

		$res=$this->obj->add($data);
		if ($res) {
			$this->success("成功添加推荐位",url('admin/Featured/apply'));
		}else{
			$this->error("推荐位添加失败");
		}
    }

    //修改状态
    public function status(){
        //dump(input('get.id'));//都是string

        //进行校验
        $data=input('get.');

        //修改状态
       
        $res=$this->obj->save(['status'=>intval(input('get.status'))],['id'=>intval(input('get.id'))]);
        
        if($res){
            //给状态修改成功的商家发邮件
            $this->success("状态修改成功");
        }else{
            $this->error("状态修改失败");
        }
    }

    //编辑
    public function edit(){
    	//return "此功能暂未开发,敬请期待~~~";
    	//通过get方法拿到要修改的id
    	$id=input('get.id');
    	//echo $data;

    	//验证一下

    	//获取推荐位类别
    	$types=config('featured.featured_type');
    	//dump($types);
    	$this->assign('types',$types);

    	//通过model得到之前的数据
    	$res=$this->obj->get($id);//这是一个对象
    	//dump($res);
    	$featured=$res->toArray();
    	//dump($featured);
    	$this->assign('featured',$featured);
    	return $this->fetch();
    }

    public function saveEdit(){
    	$data=input('post.','','htmlentities');
    	//dump($data);
    	$res=$this->obj->save($data,['id'=>intval($data['id'])]);
    	if($res){
    		$this->success("修改成功",url('admin/Featured/index'));
    	}else{
    		$this->error("修改失败");
    	}
    }
    

}
