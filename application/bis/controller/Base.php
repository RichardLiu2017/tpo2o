<?php
namespace app\bis\controller;

use think\Controller;

use think\View;

class Base extends Controller{
    public $account;


    //初始化的时候检查有没有session,有就代表有登录
    public function _initialize(){
        /*$account=session('bisAccount','','bis');
        //dump($account);//是一个bis_accoount记录(数组,因为我之前已经转了)
        if (!$account || !$account['id']) {
            $this->error('请先登录',url('bis/login/index'));
        }*/

        $isLogin=$this->isLogin();
        if (!$isLogin) {
            $this->error('请先登录',url('bis/login/index'));
        }
    }

    //判断是否登录
    protected function isLogin(){
        //获取session
        $user=$this->getLoginUser();
        if ($user && $user['id']) {
            return true;
        }
        return false;
    }

    protected function getLoginUser(){
        if (!$this->account) {
            $this->account=session('bisAccount','','bis');
        }
        return $this->account;
    }

    
}
