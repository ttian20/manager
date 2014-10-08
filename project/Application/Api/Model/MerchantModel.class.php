<?php
namespace Mer\Model;
use Think\Model;

class MerchantModel extends Model{
    //自动验证
    protected $_validate=array(
        //每个字段的详细验证内容
        array("merchant_name","require","商店名称不能为空"),
        array("name","require","联系人不能为空"),
        array("mobile","require","联系人手机不能为空"),
        array('merchant_name','','商店名称已经存在！',0,'unique',1),
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
