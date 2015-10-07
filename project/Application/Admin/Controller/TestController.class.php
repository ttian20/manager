<?php
namespace Admin\Controller;
use Think\Controller;
class TestController extends Controller {
    public function proxy() {
        echo $_SERVER['REMOTE_ADDR'] . ":" . $_SERVER['REMOTE_PORT'];
        if ($_SERVER['HTTP_X_FORWARDED_FOR'] == '121.40.176.40') {
            \Common\Lib\Utils::log('service', 'proxy.log', $_SERVER);
        }
        exit;
    }

    public function header() {
        echo json_encode($_SERVER);
        exit;
    }
}
