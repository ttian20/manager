<?php
// 本类由系统自动生成，仅供测试用途
namespace Api\Controller;
class TbpcController extends ApiController {

    public function add(){
        $args = array('kwd', 'nid', 'shop_type', 'times', 'sleep_time', 'click_start', 'click_end', 'begin_time', 'end_time');
        $this->_checkArgs($args);
        if ('huxin' == $this->_params['appkey']) {
            $this->_params['times'] = floor($this->_params['times'] * 1.22);
        }
        $data = array(
            'kwd' => $this->_params['kwd'],
            'nid' => $this->_params['nid'],
            'appkey' => $this->_params['appkey'],
            'platform' => 'tbpc',
            'shop_type' => $this->_params['shop_type'],
            'times' => $this->_params['times'],
            'sleep_time' => ((int)$this->_params['sleep_time'] > 120) ? 120 : (int)$this->_params['sleep_time'],
            'click_start_input' => $this->_params['click_start'],
            'click_end_input' => $this->_params['click_end'],
            'status' => 'active',
            'begin_time' => strtotime($this->_params['begin_time']),
            'end_time' => strtotime($this->_params['end_time']),
            'created_at' => time(),
            'updated_at' => time(),
        );

        $seconds = $this->_checkTimeRange();

        $times = (int)trim($this->_params['times']) * 2;
        $interval = ceil($seconds['seconds'] / $times);
        $data['click_start'] = $seconds['click_start'];
        $data['click_end'] = $seconds['click_end'];
        $data['click_interval'] = $interval;
        $kwdMdl = D('Keyword');
        $kwd = $kwdMdl->createNew($data);
        if ($kwd) {
            if (($this->_params['path1'] + $this->_params['path2'] + $this->_params['path3']) != 100) {
                $this->_error(500, 5001, 'total path is ' . ($this->_params['path1'] + $this->_params['path2'] + $this->_params['path3']));
                $this->_error(500, 5001, 'total path is not 100');
            }

            if (($this->_params['shop_type'] == 'c') && ($this->_params['path1'] != 100)) {
                $this->_error(500, 5001, 'pah1 should be 100 while shop_type is c');
            }

            $kwdTbpcMdl = D('KeywordTbpc');
            $data = array(
                'kid' => $kwd['id'],
                'path1' => $this->_params['path1'],
                'path2' => $this->_params['path2'],
                'path3' => $this->_params['path3'],
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
            //'sleep_time' => $this->_params['sleep_time'],
            'sleep_time' => ((int)$this->_params['sleep_time'] > 120) ? 120 : (int)$this->_params['sleep_time'],
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

        $times = (int)trim($this->_params['times']) * 2;
        $interval = ceil($seconds['seconds'] / $times);
        $data['click_start'] = $seconds['click_start'];
        $data['click_end'] = $seconds['click_end'];

        $filter = array('id' => $this->_params['kid'], 'appkey' => $this->_params['appkey']);
        $kwdMdl = D('Keyword');
        if ($kwdMdl->getRow($filter)) {
            if (($this->_params['path1'] + $this->_params['path2'] + $this->_params['path3']) != 100) {
                $this->_error(500, 5001, 'total path is ' . ($this->_params['path1'] + $this->_params['path2'] + $this->_params['path3']));
                $this->_error(500, 5001, 'total path is not 100');
            }

            if (($this->_params['shop_type'] == 'c') && ($this->_params['path1'] != 100)) {
                $this->_error(500, 5001, 'pah1 should be 100 while shop_type is c');
            }

            if ($data['end_time'] >= (time() - 86400)) {
                $data['status'] = 'active';
            }

            $kwdMdl->save($data);

            $tbpc = array(
                'kid' => $this->_params['kid'],
                'path1' => $this->_params['path1'],
                'path2' => $this->_params['path2'],
                'path3' => $this->_params['path3'],
            );
            
            $kwdTbpcMdl = D('KeywordTbpc');
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
