<?php
namespace app\common\validate;

use think\Validate;

class Bis extends Validate{
    protected $rule=[
        ['name','require|max:60','商户名必须有|商户名不能超过20个汉字或者60个字母'],
        ['email','require|email','email必须有|email不符合格式'],
        ['logo','require'],
        ['city_id','require'],
        ['id','number'],
        ['status','number|in:-1,0,1','状态必须是数字|状态不合法'],
        ['listorder','number'],
        ['category_id','require'],
        ['username','require'],
        ['password','require']
    ];

    //场景设置
    protected $scene=[
        'add'=>['name','email','city_id','id'],//添加时候用的
        'listorder'=>['id','listorder'],//排序
        'edit'=>['id'],
        'status'=>['id','status'],
        'shop'=>['category_id'],
        'account'=>['username','password']
    ];
}
