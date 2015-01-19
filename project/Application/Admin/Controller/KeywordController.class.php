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
        $filter = array('status' => $status, 'appkey' => 'system');
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
            $config = array(
                'appkey' => 'system',
                'appsecret' => 'c54764af2f98102b259d9941f781a8a1',
                'baseUrl' => C('SITE') . '/api/',
            );
            
            $api = new \Common\Lib\Api($config);
            
            $method = 'tbpc/add';
            $data = array(
                'kwd' => trim($_POST['kwd']),
                'nid' => trim($_POST['nid']),
                'shop_type' => trim($_POST['shop_type']),
                'times' => trim($_POST['times']),
                'path1' => isset($_POST['path1']) ? trim($_POST['path1']) : 0,
                'path2' => isset($_POST['path2']) ? trim($_POST['path2']) : 0,
                'path3' => isset($_POST['path3']) ? trim($_POST['path3']) : 0,
                'sleep_time' => trim($_POST['sleep_time']),
                'click_start' => trim($_POST['click_start']),
                'click_end' => trim($_POST['click_end']),
                'status' => 'active',
                'begin_time' => trim($_POST['begin_time']),
                'end_time' => trim($_POST['end_time']),
            );
            $res = $api->request($method, $data);
            echo json_encode($res);
            exit;
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
            $config = array(
                'appkey' => 'system',
                'appsecret' => 'c54764af2f98102b259d9941f781a8a1',
                'baseUrl' => C('SITE') . '/api/',
            );
            
            $api = new \Common\Lib\Api($config);
            
            $method = 'tbpc/modify';
            $data = array(
                'kid' => trim($_POST['id']),
                'shop_type' => trim($_POST['shop_type']),
                'times' => trim($_POST['times']),
                'path1' => isset($_POST['path1']) ? trim($_POST['path1']) : 0,
                'path2' => isset($_POST['path2']) ? trim($_POST['path2']) : 0,
                'path3' => isset($_POST['path3']) ? trim($_POST['path3']) : 0,
                'sleep_time' => trim($_POST['sleep_time']),
                'click_start' => trim($_POST['click_start']),
                'click_end' => trim($_POST['click_end']),
                'begin_time' => trim($_POST['begin_time']),
                'end_time' => trim($_POST['end_time']),
            );
            $res = $api->request($method, $data);
            echo json_encode($res);
            exit;
        }
        $keyword = $kwdMdl->getRow(array('id' => $kid));
        $tbpcMdl =  D('KeywordTbpc');
        $path = $tbpcMdl->getRow(array('kid' => $kid));
        $this->assign('kwd', $keyword);
        $this->assign('path', $path);
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

    public function buildApiUrl() {

    }

    public function redetect() {
        $kid = trim($_POST['kid']);
        if (!$kid) {
            echo "failed";
            exit;
        }

        $kwdMdl = D('Keyword');
        $priceMdl = D('Price');
        $data = array('is_detected' => 0, 'detect_times' => 0);

        $kfilter = array('id' => $kid);
        $kwdMdl->where($kfilter)->save($data);

        $filter = array('kid' => $kid);
        $priceMdl->where($filter)->delete();
        echo "success";
        exit;
    }
}
