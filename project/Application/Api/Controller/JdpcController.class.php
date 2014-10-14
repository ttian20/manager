<?php
// 本类由系统自动生成，仅供测试用途
namespace Api\Controller;
class JdpcController extends ApiController {

    public function add(){
        $args = array('kwd', 'nid', 'shop_type', 'times', 'sleep_time', 'click_start', 'click_end', 'begin_time', 'end_time');
        $this->_checkArgs($args);
        $data = array(
            'kwd' => $this->_params['kwd'],
            'nid' => $this->_params['nid'],
            'appkey' => $this->_params['appkey'],
            'platform' => 'jdpc',
            'shop_type' => $this->_params['shop_type'],
            'times' => $this->_params['times'],
            'sleep_time' => $this->_params['sleep_time'],
            'click_start_input' => $this->_params['click_start'],
            'click_end_input' => $this->_params['click_end'],
            'status' => 'active',
            'begin_time' => strtotime($this->_params['begin_time']),
            'end_time' => strtotime($this->_params['end_time']),
            'created_at' => time(),
            'updated_at' => time(),
        );

        $seconds = $this->_checkTimeRange();

        $times = trim($_POST['times']);
        $interval = ceil($seconds['seconds'] / $times);
        $data['click_start'] = $seconds['click_start'];
        $data['click_end'] = $seconds['click_end'];
        $data['click_interval'] = $interval;
        $kwdMdl = D('Keyword');
        $kwd = $kwdMdl->createNew($data);
        if ($kwd) {
            $kwdTbpcMdl = D('KeywordJdpc');
            $data = array(
                'kid' => $kwd['id'],
            );
            $kwdTbpcMdl->add($data);
        }
        $this->_success($kwd);
    }

    public function modify() {
        $data = array(
            'id' => $this->_params['kid'],
            'appkey' => $this->_params['appkey'],
            'shop_type' => $this->_params['shop_type'],
            'times' => $this->_params['times'],
            'sleep_time' => $this->_params['sleep_time'],
            'click_start_input' => $this->_params['click_start'],
            'click_end_input' => $this->_params['click_end'],
            'begin_time' => strtotime($this->_params['begin_time']),
            'end_time' => strtotime($this->_params['end_time']),
        );
        /*
        if (isset($this->_params[])) {
            $data[] = $this->_params[];
        }

        if (isset($this->_params[])) {
            $data[] = $this->_params[];
        }

        if (isset($this->_params[])) {
            $data[] = $this->_params[];
        }

        if (isset($this->_params[])) {
            $data[] = $this->_params[];
        }
        */

        $seconds = $this->_checkTimeRange();

        $times = trim($_POST['times']);
        $interval = ceil($seconds['seconds'] / $times);
        $data['click_start'] = $seconds['click_start'];
        $data['click_end'] = $seconds['click_end'];
        $data['click_interval'] = $interval;

        $filter = array('id' => $this->_params['kid'], 'appkey' => $this->_params['appkey']);
        $kwdMdl = D('Keyword');
        if ($kwdMdl->getRow($filter)) {
            $kwdMdl->save($data);

            $tbpc = array(
                'kid' => $this->_params['kid'],
            );
            
            $kwdTbpcMdl = D('KeywordJdpc');
            $kwdTbpcMdl->save($tbpc);

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
