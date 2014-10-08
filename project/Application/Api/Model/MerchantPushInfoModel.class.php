<?php
namespace Mer\Model;
use Think\Model;

class MerchantPushInfoModel extends Model{
    protected $tableName = 'merchant_push_info';
    //自动验证
    /*
    protected $_validate=array(
        //每个字段的详细验证内容
        //array("category_name","require","产品分类名称不能为空"),
        //array('category_name','','产品分类名称已经存在！',0,'unique',1),
    );
     */
	
    //自动填充
    protected $_auto=array(
        array("create_time","shijian",1,'callback'),
        array("update_time","shijian",1,'callback'),
    );

    function shijian(){
        return time();
    }
	
}
