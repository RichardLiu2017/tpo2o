<?php
namespace app\admin\controller;

use think\Controller;

class BaseController extends Controller{

    //修改状态
    public function status(){
        //dump(input('get.id'));//都是string

        //进行校验
        $data=input('get.');

        //获取控制器
        $model=request()->controller();

        //修改状态
       
        $res=model($model)->save(['status'=>intval(input('get.status'))],['id'=>intval(input('get.id'))]);
        
        if($res){
            //给状态修改成功的商家发邮件
            $this->success("状态修改成功");
        }else{
            $this->error("状态修改失败");
        }
    }

    //排序
}
