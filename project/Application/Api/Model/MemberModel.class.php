<?php
namespace Api\Model;
use Think\Model;

class MemberModel extends Model{
    //自动验证
    protected $_validate=array(
        //每个字段的详细验证内容
        array("name","require","用户名不能为空"),
        array("name","checkLength","用户名长度不符合要求",0,'callback'),
        array('name','','帐号名称已经存在！',0,'unique',1),
        array("password","require","密码不能为空"),
        array("password","checkLength","密码长度的要求是5~15位之间",0,'callback'),
        array("password","repassword","两次密码输入不一致",0,'confirm'),
        //array("qq","require","qq必须填写"),
        //array("cdate","require","时间不能为空",callback),
    );
	
    //自动填充
    protected $_auto=array(
        array("password","md5",3,'function'),
        array("create_time","shijian",1,'callback'),
        array("update_time","shijian",3,'callback'),
        array("ip","getIp",3,'callback'),
    );

    //自定义验证方法，来验证用户名的长度是否合法
    //$date形参  可以写成任意如 $AA  $bb
    public function checkLength($data){
        //$data里存放的就是要验证的用户输入的字符串
        if(strlen($data)<5||strlen($data)>15){
            return false;
        }else{
            return true;
        }
    }

    //返回访问者的IP地址
    function getIp(){
        return $_SERVER['REMOTE_ADDR'];
    }

    function shijian(){
        return time();
    }
	
}
