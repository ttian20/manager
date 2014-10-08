<?php
namespace Admin\Controller;
use Think\Controller;
class KeywordController extends Controller {
    public function __construct() {
        parent::__construct();
    }

    public function lists() {
        if (isset($_GET['status'])) {
            $status = trim($_GET['status']);
        }
        else {
            $status = 'active';
        }

        $allowedStatus = array('active', 'expired', 'delete');
        if (!in_array($status, $allowedStatus)) {
            $this->_redirect('/admin/keyword/lists');
            exit;
        }
        $kwdMdl = D('Keyword');
        $filter = array('status' => $status);
        $keywords = $kwdMdl->getAll($filter);
        foreach ($keywords as &$v) {
            $v['begin_time'] = date('Y-m-d', $v['begin_time']);
            $v['end_time'] = date('Y-m-d', $v['end_time']);
        }
        $this->assign('status', $status);
        $this->assign('keywords', $keywords);
        $this->display();
    }

    public function add() {
        if ($_POST) {
            $data = array(
                'kwd' => trim($_POST['kwd']),
                'nid' => trim($_POST['nid']),
                'shop_type' => trim($_POST['shop_type']),
                'times' => trim($_POST['times']),
                'path1' => trim($_POST['path1']),
                'path1_region' => trim($_POST['path1_region']),
                'path1_price_from' => trim($_POST['path1_price_from']),
                'path1_price_to' => trim($_POST['path1_price_to']),
                'path2' => trim($_POST['path2']),
                'path2_region' => trim($_POST['path2_region']),
                'path2_price_from' => trim($_POST['path2_price_from']),
                'path2_price_to' => trim($_POST['path2_price_to']),
                'path3' => trim($_POST['path3']),
                'path3_region' => trim($_POST['path3_region']),
                'path3_price_from' => trim($_POST['path3_price_from']),
                'path3_price_to' => trim($_POST['path3_price_to']),
                'sleep_time' => trim($_POST['sleep_time']),
                'click_start' => trim($_POST['click_start']),
                'click_end' => trim($_POST['click_end']),
                'status' => 'active',
                'begin_time' => strtotime(trim($_POST['begin_time'])),
                'end_time' => strtotime(trim($_POST['end_time'])),
            );

            $end = trim($_POST['click_end']);
            $begin = trim($_POST['click_start']);
            $seconds = ($end - $begin) * 3600;
            $times = trim($_POST['times']);
            $interval = ceil($seconds / $times);
            $data['click_interval'] = $interval;


            $kwdMdl = D('Keyword');
            $kwdMdl->createNew($data);
        }
        $this->assign('act', 'add');
        $this->assign('actionUrl', '/admin/keyword/add');
        $this->display();
    }

    public function edit() {
        $kwdMdl = D('Keyword');
        $kid = $_GET['kid'];
        if ($_POST) {
            if ($kid != $_POST['id']) {
                exit('非法请求');
            }
            $data = $_POST;
            $data['begin_time'] = strtotime($data['begin_time']);
            $data['end_time'] = strtotime($data['end_time']);
            if (($data['end_time'] + 86400) > time()) {
                $data['status'] = 'active';
            }
            $end = trim($data['click_end']);
            $begin = trim($data['click_start']);
            $seconds = ($end - $begin) * 3600;
            $times = trim($data['times']);
            $interval = ceil($seconds / $times);
            $data['click_interval'] = $interval;
            $kwdMdl->save($data);
        }
        $keyword = $kwdMdl->getRow(array('id' => $kid));
        $this->assign('kwd', $keyword);
        $this->assign('act', 'edit');
        $this->assign('actionUrl', '/admin/keyword/edit/' . $kid);
        $this->display('add');
    }

    public function del() {
        $kwdMdl = D('Keyword');
        $id = $_GET['id'];
        $kwd = $kwdMdl->getRow(array('id' => $id)); 
        if ($kwd) {
            $kwd['status'] = 'delete';
            $kwdMdl->save($kwd);
            $this->success('关键词已删除', '/admin/keyword/lists', 3);
        }
        else {
            $this->error('关键词不存在');
        }
    }

    public function info() {
        $kid = $_GET['kid'];
        $kwdMdl = D('Keyword');
        $kwd = $kwdMdl->getRow(array('id' => $kid)); 
        print_r($kwd);
        exit;
    }
}
