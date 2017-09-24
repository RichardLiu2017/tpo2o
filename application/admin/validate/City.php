<?php
namespace app\admin\validate;

use think\Validate;

class City extends Validate{
    protected $rule=[
        ['name','require|max:10','分类名必须有|分类名不能超过10个字符'],
        ['uname','require|max:10','分类名必须有|分类名不能超过10个字符'],
        ['parent_id','number'],
        ['id','number'],
        ['status','number|in:-1,0,1','状态必须是数字|状态不合法'],
        ['listorder','number']
    ];

    //场景设置
    protected $scene=[
        'add'=>['name','uname','parent_id','id'],//添加时候用的
        'listorder'=>['id','listorder'],//排序
        'edit'=>['id'],
        'status'=>['id','status']
    ];
}
