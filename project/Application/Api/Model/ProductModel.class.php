<?php
namespace Mer\Model;
use Think\Model;

class ProductModel extends Model{
    //自动验证
    protected $_validate=array(
        //每个字段的详细验证内容
        array("product_name","require","商品名称不能为空"),
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
