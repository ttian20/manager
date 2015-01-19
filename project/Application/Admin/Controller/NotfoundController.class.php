<?php
namespace Admin\Controller;
use Think\Controller;
class NotfoundController extends Controller {
    public function __construct() {
        parent::__construct();
    }

    public function delete() {
        $clickLogMdl = D('ClickLog');
        $priceMdl = D('Price');
        $kwdMdl = D('Keyword');
        $rs = $clickLogMdl->distinct(true)->field('kid')->where("log='404' AND click_log.created_at > UNIX_TIMESTAMP('" . date('Y-m-d') . "')")->select();
        foreach ($rs as $row) {
            $priceMdl->where("kid = " . $row['kid'])->delete();
            $kwdMdl->where("id = " . $row['kid'])->setField('is_detected', 0);
        }
    }
}
