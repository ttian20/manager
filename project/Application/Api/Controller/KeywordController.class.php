<?php
// 本类由系统自动生成，仅供测试用途
namespace Api\Controller;
class KeywordController extends ApiController {

    public function add(){
        $data = array(
            'kwd' => $this->_params['kwd'],
            'nid' => $this->_params['nid'],
            'appkey' => $this->_params['appkey'],
            'shop_type' => $this->_params['shop_type'],
            'times' => $this->_params['times'],
            'path1' => $this->_params['path1'],
            'path2' => $this->_params['path2'],
            'path3' => $this->_params['path3'],
            'sleep_time' => $this->_params['sleep_time'],
            'click_start' => $this->_params['click_start'],
            'click_end' => $this->_params['click_end'],
            'begin_time' => strtotime($this->_params['begin_time']),
            'end_time' => strtotime($this->_params['end_time']),
        );
    
        $end = trim($_POST['click_end']);
        $begin = trim($_POST['click_start']);
        $seconds = ($end - $begin) * 3600;
        $times = trim($_POST['times']);
        $interval = ceil($seconds / $times);
        $data['click_interval'] = $interval;
        $kwdMdl = D('Keyword');
        $kwd = $kwdMdl->createNew($data);
        $this->_success($kwd);
    }

    public function modify() {
        $data = array(
            'id' => $this->_params['kid'],
            'appkey' => $this->_params['appkey'],
            'shop_type' => $this->_params['shop_type'],
            'times' => $this->_params['times'],
            'path1' => $this->_params['path1'],
            'path2' => $this->_params['path2'],
            'path3' => $this->_params['path3'],
            'sleep_time' => $this->_params['sleep_time'],
            'click_start' => $this->_params['click_start'],
            'click_end' => $this->_params['click_end'],
            'begin_time' => strtotime($this->_params['begin_time']),
            'end_time' => strtotime($this->_params['end_time']),
        );

        $end = trim($_POST['click_end']);
        $begin = trim($_POST['click_start']);
        $seconds = ($end - $begin) * 3600;
        $times = trim($_POST['times']);
        $interval = ceil($seconds / $times);
        $data['click_interval'] = $interval;

        $filter = array('id' => $this->_params['kid'], 'appkey' => $this->_params['appkey']);
        $kwdMdl = D('Keyword');
        if ($kwdMdl->getRow($filter)) {
            $kwdMdl->save($data);
            $this->_success($data);
        }
        else {
            $this->_error(404, 4001, 'keyword not exists');
        }
    }

    public function get() {
        $filter = array('id' => $this->_params['kid'], 'appkey' => $this->_params['appkey']);
        $kwdMdl = D('Keyword');
        if ($kwd = $kwdMdl->getRow($filter)) {
            $this->_success($kwd);
        }
        else {
            $this->_error(404, 4001, 'kid not exists');
        }
    }
}
