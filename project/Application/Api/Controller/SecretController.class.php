<?php
namespace Api\Controller;
use Think\Controller;

class SecretController extends Controller {
    /**
     * 生成appsecret工具
     */
    public function index() {
        if ($_POST) {
            $appkey = trim($_POST['appkey']);
            $appsecret = md5($appkey . "$$" . "crazyclick");
            $this->assign('appkey', $appkey);
            $this->assign('appsecret', $appsecret);
        }
        $this->display();
    }
}
