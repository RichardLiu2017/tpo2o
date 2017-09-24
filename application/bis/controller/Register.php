<?php
namespace app\bis\controller;

use think\Controller;

use think\View;

class Register extends Controller{
	private $cityObj;
	private $categoryObj;
	private $bisObj;
	private $bisAccountObj;
	private $bisLocationObj;

	public function _initialize(){
		$this->cityObj=model('City');
		$this->categoryObj=model('Category');
		$this->bisObj=model('Bis');
		$this->bisAccountObj=model('BisAccount');
		$this->bisLocationObj=model('BisLocation');
	}

	public function index(){
		//获取一级城市列表
		$citys=$this->cityObj->getNormalCityByParentId();
		$this->assign('citys',$citys);

		//获取一级分类列表
		$categorys=$this->categoryObj->getNormalFirstCategory();
		$this->assign('categorys',$categorys);

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
        //$data=input('post.','','htmlentities');
    	//dump($data);
    	//dump($data['name']);

    	/*
			array (size=19)
			  'name' => string 'test' (length=4)
			  'city_id' => string '1' (length=1)
			  'se_city_id' => string '2' (length=1)
			  'logo' => string '' (length=0)
			  'licence_logo' => string '' (length=0)
			  'bank_info' => string '' (length=0)
			  'bank_name' => string '' (length=0)
			  'bank_user' => string '' (length=0)
			  'faren' => string '' (length=0)
			  'faren_tel' => string '' (length=0)
			  'email' => string '111@qq.com' (length=10)
			  'tel' => string '' (length=0)
			  'contact' => string '' (length=0)
			  'category_id' => string '7' (length=1)
			  'se_category_id' => 
			    array (size=1)
			      0 => string '15' (length=2)
			  'address' => string '' (length=0)
			  'open_time' => string '' (length=0)
			  'username' => string 'admin' (length=5)
			  'password' => string '' (length=0)
    	*/

    	//商家基本信息验证
		$validate=validate('Bis');
		if (!$validate->scene('add')->check($data)) {
			$this->error($validate->getError());
		}

		//商家总店信息验证
		$validate=validate('BisLocation');
		if (!$validate->scene('location')->check($data)) {
			$this->error($validate->getError());
		}

		//商家账号信息验证
		$validate=validate('BisAccount');
		if (!$validate->scene('account')->check($data)) {
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

		//判断商家是否已经注册过了
		$accountExist=$this->bisAccountObj->get(['username',$data['username']]);
		if ($accountExist) {
			$this->error('该用户名有了,客官,您换一个呗~');
		}

    	//发到Bis model层
    	$data_bis=[
    		'name'=>htmlentities($data['name']),
    		'email'=>$data['email'],
    		'logo'=>$data['logo'],
    		'licence_logo'=>$data['licence_logo'],
    		'description'=>empty($data['description'])?'':$data['description'],
    		'city_id'=>$data['city_id'],
    		'city_path'=>empty($data['se_city_id'])?$data['city_id']:$data['city_id'].','.$data['se_city_id'],//如果有子城市,就把父级城市和子城市连起来
    		'bank_info'=>$data['bank_info'],
    		'bank_name'=>$data['bank_name'],
    		'bank_user'=>$data['bank_user'],
    		'faren'=>$data['faren'],
    		'faren_tel'=>$data['faren_tel'],
    	];
    	$res_bis=$this->bisObj->add($data_bis);
    	//echo $res_bis."<br>";
    	/*if ($res_bis) {
    		$this->success('注册成功');
    	}else{
    		$this->error('注册失败');
    	}*/

    	//发到Bis_location model层
    	//搞一下传过来的$data['se_city_id']
    	$data['category']='';
    	//如果不为空,就把这些子分类连起来
    	if (!empty($data['se_category_id'])) {
    		$data['category']=implode('|', $data['se_category_id']);
    	}
    	$data_bis_location=[
    		'name'=>htmlentities($data['name']),
    		'logo'=>$data['logo'],
    		'address'=>$data['address'],
    		'tel'=>$data['tel'],
    		'contact'=>$data['contact'],
    		'xpoint'=>empty($lnglat['result']['location']['lng'])?'':$lnglat['result']['location']['lng'],
    		'ypoint'=>empty($lnglat['result']['location']['lat'])?'':$lnglat['result']['location']['lat'],
    		'bis_id'=>$res_bis,
    		'open_time'=>$data['open_time'],
    		'content'=>empty($data['content'])?'':$data['content'],//如果有子城市,就把父级城市和子城市连起来
    		'is_main'=>1,//代表默认是总店
    		'api_address'=>$data['address'],//貌似应该就是地址
    		'city_id'=>$data['city_id'],
    		'city_path'=>empty($data['se_city_id'])?$data['city_id']:$data['city_id'].','.$data['se_city_id'],
    		'category_id'=>$data['category_id'],
    		'category_path'=>$data['category_id'].','.$data['category']
    	];
    	$res_bis_location=$this->bisLocationObj->add($data_bis_location);
    	//echo $res_bis_location."<br>";
    	

    	//发到Bis_account model层
    	//密码的处理
    	$code=rand(1000,9999);
    	$psw=$data['password'];
    	$psw_code=$psw.$code;
    	$psw_md5=md5($psw_code);


    	$data_bis_account=[
    		'username'=>$data['username'],
    		'code'=>$code,
    		'password'=>$psw_md5,
    		'bis_id'=>$res_bis,
    		'last_login_ip'=>'',
    		'last_login_time'=>'',
    		'is_main'=>1,//代表默认是总管理员
    	];
    	$res_bis_account=$this->bisAccountObj->add($data_bis_account);
    	//echo $res_bis_account."<br>";

    	
    	if (!$res_bis || !$res_bis_location || !$res_bis_account) {
    		$this->error('注册失败');
    	}

    	//如果注册成功,则发一封邮件给商家
    	$url=request()->domain().url("bis/register/waiting",['id'=>$res_bis]);//邮件里的链接
    	$title="注册成功";
    	$content="恭喜您注册成功~~~~~<a href='".$url."'>点我跳转</a>~~~~~查看注册状态";
    	\phpmailer\Email::send($data['email'],$data['contact'],$title,$content);
    	$this->success("注册成功,快去邮箱看看吧~",url("bis/register/waiting",['id'=>$res_bis]));
    }

    //注册成功之后给的等待审核页面
    public function waiting($id){
    	if (empty($id)) {
    		$this->error('大哥,走错路了吧,这里没有你的注册信息啊~');
    	}
    	$detail=model('Bis')->get($id);
    	$this->assign('detail',$detail);


    	return $this->fetch();
    }
}