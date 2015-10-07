<?php
// 本类由系统自动生成，仅供测试用途
namespace Api\Controller;
class TbpcController extends ApiController {

    public function add(){
        $args = array('kwd', 'nid', 'shop_type', 'times', 'sleep_time', 'click_start', 'click_end', 'begin_time', 'end_time');
        $this->_checkArgs($args);
        if (in_array($this->_params['appkey'], array('huxin', 'waguke', 'linlang'))) {
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

        $times = (int)trim($this->_params['times']) * 4;
        $interval = ceil($seconds['seconds'] / $times);
        $data['click_start'] = $seconds['click_start'];
        #$data['click_end'] = $seconds['click_end'];
        $data['click_end'] = '23:55:00';
        $data['click_interval'] = $interval;
        if ($data['click_start'] >= '12') {
            $data['sleep_time'] = 80;
        }
        if ($data['click_start'] >= '17') {
            $data['sleep_time'] = 30;
        }
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
            
            if (!in_array($this->_params['appkey'], array('system', 'shenyuanbao'))) {
                if ($data['path3'] > 0) {
                    $data['path2'] += $data['path3'];
                    $data['path3'] = 0;
                }
            }

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

        $times = (int)trim($this->_params['times']) * 4;
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

    public function check() {
        $args = array('kid');
        $this->_checkArgs($args);
        $filter = array(
            'id' => $this->_params['kid'],
            'appkey' => $this->_params['appkey'],
        );
        $kfilter = array('kid' => $this->_params['kid']);

        $kwdMdl = D('Keyword');
        $priceMdl = D('Price');
        $kwdTbpcMdl = D('KeywordTbpc');
        $clickLogMdl = D('ClickLog');
        $pagesMdl = D('Pages');

        $kwd = $kwdMdl->getRow($filter);
        if (!$kwd) {
            $this->_error(404, 4001, 'kid not found');
        }

        if ('active' != $kwd['status']) {
            $this->_error(500, 5001, 'task not active');
        }

/*
        $data = array(
            'keyword_detected' => '',
            'price_detected' => '',
            'path1' => '',
            'path1_page' => '',
            'path1_click_log' => '',
            'path2' => '',
            'path2_page' => '',
            'path2_click_log' => '',
            'path3' => '',
            'path3_page' => '',
            'path3_click_log' => '',
        );
*/

        $data['keyword_detected'] = $kwd['is_detected'] ? 'success' : 'fail';
        
        $price = $priceMdl->getRow($kfilter);
        $data['price_detected'] = $price ? 'success' : 'fail';

        $pagesCount = $pagesMdl->where($kfilter)->count();
        $data['page_detected'] = $pagesCount ? 'success' : 'fail';

        $tbpc = $kwdTbpcMdl->getRow($kfilter);
        $data['path1'] = $tbpc['path1'];
        if ($tbpc['path1']) {
            if ($tbpc['path1_page'] >= 1 && $tbpc['path1_page'] <= 10) {
                $data['path1_page'] = 'success';

                //click_log
                $cfilter = $kfilter;
                $cfilter['path'] = 'taobao';
                $cfilter['created_at'] = array('egt', strtotime(date('Y-m-d')));
                $cfilter['log'] = '404';

                $notfoundCount = $clickLogMdl->where($cfilter)->count();

                $cfilter['log'] = array('like','200%');
                $foundCount = $clickLogMdl->where($cfilter)->count();

                if ($notfoundCount && !$foundCount) {
                    $data['path1_click_log'] = 'fail';
                }
            }
            else {
                $data['path1_page'] = 'fail';
            }
        }

        $data['path2'] = $tbpc['path2'];
        if ($tbpc['path2']) {
            if ($tbpc['path2_page'] >= 1 && $tbpc['path2_page'] <= 10) {
                $data['path2_page'] = 'success';

                //click_log
                $cfilter = $kfilter;
                $cfilter['path'] = 'taobao';
                $cfilter['created_at'] = array('egt', strtotime(date('Y-m-d')));
                $cfilter['log'] = '404';

                $notfoundCount = $clickLogMdl->where($cfilter)->count();

                $cfilter['log'] = array('like','200%');
                $foundCount = $clickLogMdl->where($cfilter)->count();

                if ($notfoundCount && !$foundCount) {
                    $data['path2_click_log'] = 'fail';
                }
                else {

                }
            }
            else {
                $data['path2_page'] = 'fail';
            }
        }

        $data['path3'] = $tbpc['path3'];
        if ($tbpc['path3']) {
            if ($tbpc['path3_page'] >= 1 && $tbpc['path3_page'] <= 10) {
                $data['path3_page'] = 'success';

                //click_log
                $cfilter = $kfilter;
                $cfilter['path'] = 'taobao';
                $cfilter['created_at'] = array('egt', strtotime(date('Y-m-d')));
                $cfilter['log'] = '404';

                $notfoundCount = $clickLogMdl->where($cfilter)->count();

                $cfilter['log'] = array('like','200%');
                $foundCount = $clickLogMdl->where($cfilter)->count();

                if ($notfoundCount && !$foundCount) {
                    $data['path3_click_log'] = 'fail';
                }
                else {

                }
            }
            else {
                $data['path3_page'] = 'fail';
            }
        }


        $this->_success($data);
    }

    public function notfound() {
        //keyword.is_detected 为0
        //并且已经探测过价格和pages
        //视为搜索不到这个词

        $args = array('click_start_from', 'click_start_to', 'begin_time');
        $this->_checkArgs($args);
        //结束值要大于起始值
        if ($this->_params['click_start_to'] <= $this->_params['click_start_from']) {
            $this->_error(500, 5001, 'click_start_to should be bigger than click_start_from');
        }

        //距当前时间20分钟
        $current = time();
        $allowed_interval = 20 * 60;
        if (($current > $click_start_to) && (($current - $click_start_to) < $allowed_interval)) {
            $this->_error(500, 5002, 'click_start_to should be 20 minutes before');
        }

        $kwdMdl = D('Keyword');
        $priceMdl = D('Price');
        $kwdTbpcMdl = D('KeywordTbpc');
        $pagesMdl = D('Pages');

        //从任务表中获取未探测记录
        //当天0点
        $today = strtotime(date('Y-m-d 00:00:00'));
        $filter = array(
            'status' => 'active',
            'appkey' => $this->_params['appkey'],
            'is_detected' => 0,
            'begin_time' => $today,
        );
        $rs = $kwdMdl->field('id')->where($filter)->select();
        $kids = array();
        if ($rs) {
            foreach ($rs as $row) {
                $kids[] = $row['id'];
            }
        }
        else {
            $this->_success(array());
        }

        //获取价格探测记录
        $pfilter = array(
            'kid' => array('in', $kids),
        );
        $prices = $priceMdl->where($pfilter)->select();
        $kids = array();
        if ($prices) {
            foreach ($prices as $row) {
                $kids[] = $row['kid'];
            }
        }
        else {
            $this->_success(array());
        }

        //获取已探测过页面的kid
        $pgfilter = array(
            'kid' => array('in', $kids),
        );
        $pages = $pagesMdl->distinct(true)->field('kid')->where($pgfilter)->select();
        $kids = array();
        if ($pages) {
            foreach ($pages as $row) {
                $kids[] = $row['kid'];
            }
        }
        else {
            $this->_success(array());
        }
        
        $filter = array(
            'id' => array('in', $kids),
        );
        $rs = $kwdMdl->field('id,kwd,nid,platform,shop_type,times,click_start_input,click_end_input')->where($filter)->select();
        $data = array();
        if ($rs) {
            foreach ($rs as $row) {
                $data[] = $row;
            }
            $this->_success($data);
        }
        else {
            $this->_success(array());
        }

    }
}
