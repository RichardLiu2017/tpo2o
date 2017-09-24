<?php
namespace app\index\controller;

use think\Controller;

class User extends Controller{
    private $obj;

    public function _initialize(){
        $this->obj=model('User');
    }

    public function login(){
            //做判断,如果有session,代表已经登录了.就不用重复登录
            $user='';
            $user=session('o2o_user','','o2o');
            //dump($account);//是一个bis_accoount记录(数组,因为我之前已经转了)
            if ($user && $user['id']) {
                //return $this->redirect(url('index/index/index'));
                $this->error("您已经登录过了,请不要重复登录",url('index/index/index'));
            }
            return $this->fetch();
        
    }

    //检测用户名和密码
    public function check(){
        $username=input('post.username');
        $password=input('post.password');
        /*echo "用户名".$username."密码".$password;
        exit();*/

        $dataObj=$this->obj->getDataByUsername($username);

        //如果对象为null,或者还处于审核状态,则不允许进入
        if (empty($dataObj)) {
            $this->error('用户名不存在');
        }

        if ($dataObj->status!=1) {
            $this->error('您的申请还未通过,稍后再来吧~~~');
        }

        //用户名存在,就要检测密码
        $data=$dataObj->toArray();

        //拿到加密的code
        $code=$data['code'];
        $psw_code=$password.$code;
        $psw_md5=md5($psw_code);
        if ($data['password']!=$psw_md5) {
            $this->error('密码不正确');
        }else{
            //先要更新一下数据库里的last_login_ip & time等信息
            $this->obj->updateById([
                'last_login_time'=>time(),
                'last_login_ip'=>$_SERVER["REMOTE_ADDR"],
            ],$data['id']);

            //保存用户信息 user是session的名字 index是作用域
            session('o2o_user',$data,'o2o');
            /*echo "success"."<br>";
            dump($_SESSION);*/
            $this->success("登录成功,即将回到首页",url('index/index/index'));
            

            //return $this->fetch('index/index');
        }
    }

    public function register(){
        return $this->fetch();
    }

    //新增用户
    public function save(){
        $data=input('post.','','htmlentities');
        //dump($data);exit();
        /*
            array (size=5)
              'userName' => string 'liuchihe' (length=8)
              'name_repeated' => string 'name_repeated' (length=13)
              'email' => string 'test@qq.com' (length=11)
              'email_repeated' => string 'email_repeated' (length=14)
              'mobile' => string '1222' (length=4)
              'password' => string '111' (length=3)
              'password2' => string '111' (length=3)
              'verifycode' => string '111' (length=3)
        */
        $data['name_repeated']=isset($data['name_repeated'])?$data['name_repeated']:'';
        $data['email_repeated']=isset($data['email_repeated'])?$data['email_repeated']:'';

        //校验验证码
        if(!captcha_check($data['verifycode'])){
            //失败
            $this->error('验证码不正确');
        }else{
            //$this->success("验证码正确");

            //验证信息是否有缺 没写
            
            //用户名存在
            if ($data['name_repeated']=='name_repeated') {
                $this->error('跟你说了用户名重复了,你为什么就是不听!!!');
            }

            //邮箱存在
            if ($data['name_repeated']=='email_repeated') {
                $this->error('跟你说了邮箱重复了,你为什么就是不听!!!');
            }

            //看密码是否一致
            if ($data['password']!=$data['password2']) {
                $this->error('跟你说了密码不一样,你为什么就是不听!!!');
            }

            $user=[];
            $user['username']=$data['userName'];
            $user['email']=$data['email'];
            $user['mobile']=$data['mobile'];

            $code=rand(1000,9999);
            $psw_code=$data['password'].$code;
            $psw_md5=md5($psw_code);
            $user['password']=$psw_md5;
            $user['code']=$code;

            //入库
            $res=$this->obj->add($user);

            if ($res) {
                $this->success('成功注册',url('index/User/login'));
            }else{
                $this->error('注册失败');
            }
        }              
    }

    //退出登录
    public function logout(){
        //清除session
        session(null,'o2o');
        $this->success("您已退出登录");

        //return $this->redirect(url('index/Index/index'));
    }
}
