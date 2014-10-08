<?php
namespace Mer\Model;
use Think\Model;

class StoreModel extends Model{
    //自动验证
    protected $_validate=array(
        //每个字段的详细验证内容
        array("store_name","require","商店名称不能为空"),
        array("store_telephone","require","商店电话不能为空"),
        array("store_managers","require","商店管理者不能为空"),
        array("store_address","require","地址不能为空"),
        array("payment_account","require","支付帐号不能为空"),
    );
	
    //自动填充
    protected $_auto=array(
        array("create_time","shijian",1,'callback'),
        array("update_time","shijian",1,'callback'),
    );

    function shijian(){
        return time();
    }
	
}
