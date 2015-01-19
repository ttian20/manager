<?php
namespace Api\Controller;
use Think\Controller;

class TestController extends Controller {
    protected $_params = array();
    protected $_appsecret = 'test';
    
    /**
     * 验证系统参数
     */
    public function index() {
        $api_Tbpc = array(
            array(
                'name' => 'tbpc/add', 'title' => '增加关键词',
                'params' => array(
                    array('name' => 'kwd', 'title' => '关键词'),
                    array('name' => 'nid', 'title' => '宝贝id'),
                    array('name' => 'shop_type', 'title' => '店铺类型', 'type'=>'select', 'optionlist'=>'b:天猫,c:淘宝'),
                    array('name' => 'times', 'title' => '日点击数'),
                    array('name' => 'path1', 'title' => '淘宝搜索路径占比(3路径占比之和为100)'),
                    array('name' => 'path2', 'title' => '淘宝搜天猫路径占比(C店商品不可设置)'),
                    array('name' => 'path3', 'title' => '天猫搜索路径占比(C店商品不可设置)'),
                    array('name' => 'sleep_time', 'title' => '宝贝页停留时间(秒)'),
                    array('name' => 'click_start', 'title' => '每日开始时间(如8点)'),
                    array('name' => 'click_end', 'title' => '每日结束时间(如24点)'),
                    array('name' => 'begin_time', 'title' => '开始日期(如2014-09-23)'),
                    array('name' => 'end_time', 'title' => '截止日期(如2014-09-23)'),
                ), 
            ),
            array(
                'name' => 'tbpc/modify', 'title' => '修改关键词',
                'params' => array(
                    array('name' => 'kid', 'title' => '关键词ID'),
                    array('name' => 'shop_type', 'title' => '店铺类型', 'type'=>'select', 'optionlist'=>'b:天猫,c:淘宝'),
                    array('name' => 'times', 'title' => '日点击数'),
                    array('name' => 'path1', 'title' => '淘宝搜索路径占比'),
                    array('name' => 'path2', 'title' => '淘宝搜天猫路径占比'),
                    array('name' => 'path3', 'title' => '天猫搜索路径占比'),
                    array('name' => 'sleep_time', 'title' => '宝贝页停留时间(秒)'),
                    array('name' => 'click_start', 'title' => '每日开始时间(如8点)'),
                    array('name' => 'click_end', 'title' => '每日结束时间(如24点)'),
                    array('name' => 'begin_time', 'title' => '开始日期(如2014-09-23)'),
                    array('name' => 'end_time', 'title' => '截止日期(如2014-09-23)'),
                ), 
            ),
            array(
                'name' => 'tbpc/check', 'title' => '关键词任务检测',
                'params' => array(
                    array('name' => 'kid', 'title' => '关键词ID'),
                ), 
            ),
            array(
                'name' => 'tbpc/notfound', 'title' => '获取探测不到的关键词',
                'params' => array(
                    array('name' => 'click_start_from', 'title' => '点击开始时间起始值(时间戳)'),
                    array('name' => 'click_start_to', 'title' => '点击开始时间结束值(时间戳)'),
                ), 
            ),
        );
        $api_Tbmobi = array(
            array(
                'name' => 'tbmobi/add', 'title' => '增加关键词',
                'params' => array(
                    array('name' => 'kwd', 'title' => '关键词'),
                    array('name' => 'nid', 'title' => '宝贝id'),
                    array('name' => 'shop_type', 'title' => '店铺类型', 'type'=>'select', 'optionlist'=>'b:天猫,c:淘宝'),
                    array('name' => 'times', 'title' => '日点击数'),
                    array('name' => 'path1', 'title' => '淘宝搜索路径占比(2路径占比之和为100)'),
                    array('name' => 'path2', 'title' => '淘宝搜天猫路径占比(C店商品不可设置)'),
                    array('name' => 'sleep_time', 'title' => '宝贝页停留时间(秒)'),
                    array('name' => 'click_start', 'title' => '每日开始时间(如8点)'),
                    array('name' => 'click_end', 'title' => '每日结束时间(如24点)'),
                    array('name' => 'begin_time', 'title' => '开始日期(如2014-09-23)'),
                    array('name' => 'end_time', 'title' => '截止日期(如2014-09-23)'),
                ), 
            ),
            array(
                'name' => 'tbmobi/modify', 'title' => '修改关键词',
                'params' => array(
                    array('name' => 'kid', 'title' => '关键词ID'),
                    array('name' => 'shop_type', 'title' => '店铺类型'),
                    array('name' => 'times', 'title' => '日点击数'),
                    array('name' => 'path1', 'title' => '淘宝搜索路径占比(2路径占比之和为100)'),
                    array('name' => 'path2', 'title' => '淘宝搜天猫路径占比(C店商品不可设置)'),
                    array('name' => 'sleep_time', 'title' => '宝贝页停留时间(秒)'),
                    array('name' => 'click_start', 'title' => '每日开始时间(如8点)'),
                    array('name' => 'click_end', 'title' => '每日结束时间(如24点)'),
                    array('name' => 'begin_time', 'title' => '开始日期(如2014-09-23)'),
                    array('name' => 'end_time', 'title' => '截止日期(如2014-09-23)'),
                ), 
            ),
        );

        $api_Jdpc = array(
            array(
                'name' => 'jdpc/add', 'title' => '增加关键词',
                'params' => array(
                    array('name' => 'kwd', 'title' => '关键词'),
                    array('name' => 'nid', 'title' => '宝贝id'),
                    array('name' => 'times', 'title' => '日点击数'),
                    array('name' => 'sleep_time', 'title' => '宝贝页停留时间(秒)'),
                    array('name' => 'click_start', 'title' => '每日开始时间(如8点)'),
                    array('name' => 'click_end', 'title' => '每日结束时间(如24点)'),
                    array('name' => 'begin_time', 'title' => '开始日期(如2014-09-23)'),
                    array('name' => 'end_time', 'title' => '截止日期(如2014-09-23)'),
                ), 
            ),
            array(
                'name' => 'jdpc/modify', 'title' => '修改关键词',
                'params' => array(
                    array('name' => 'kid', 'title' => '关键词ID'),
                    array('name' => 'times', 'title' => '日点击数'),
                    array('name' => 'click_start', 'title' => '每日开始时间(如8点)'),
                    array('name' => 'click_end', 'title' => '每日结束时间(如24点)'),
                    array('name' => 'begin_time', 'title' => '开始日期(如2014-09-23)'),
                    array('name' => 'end_time', 'title' => '截止日期(如2014-09-23)'),
                ), 
            ),
        );

        $api_Statistics = array(
            array(
                'name' => 'statistics/getclicks', 'title' => '获取点击数量',
                'params' => array(
                    array('name' => 'id', 'title' => '系统给出的点击任务ID'),
                    array('name' => 'date', 'title' => '日期(20140907)'),
                    array('name' => 'page_no', 'title' => '页数'),
                    array('name' => 'page_size', 'title' => '每页记录数(最大100)'),
                ), 
            ),
        );

        $api_Tbad = array(
            array(
                'name' => 'tbad/add', 'title' => '增加关键词',
                'params' => array(
                    array('name' => 'kwd', 'title' => '关键词'),
                    array('name' => 'nid', 'title' => '宝贝id'),
                    array('name' => 'shop_type', 'title' => '店铺类型', 'type'=>'select', 'optionlist'=>'b:天猫,c:淘宝'),
                    array('name' => 'times', 'title' => '日点击数'),
                    array('name' => 'path1', 'title' => '淘宝搜索路径占比(3路径占比之和为100)'),
                    array('name' => 'path2', 'title' => '淘宝搜天猫路径占比(C店商品不可设置)'),
                    array('name' => 'path3', 'title' => '天猫搜索路径占比(C店商品不可设置)'),
                    array('name' => 'sleep_time', 'title' => '宝贝页停留时间(秒)'),
                    array('name' => 'click_start', 'title' => '每日开始时间(如8点)'),
                    array('name' => 'click_end', 'title' => '每日结束时间(如24点)'),
                    array('name' => 'begin_time', 'title' => '开始日期(如2014-09-23)'),
                    array('name' => 'end_time', 'title' => '截止日期(如2014-09-23)'),
                ), 
            ),
            array(
                'name' => 'tbad/modify', 'title' => '修改关键词',
                'params' => array(
                    array('name' => 'kid', 'title' => '关键词ID'),
                    array('name' => 'shop_type', 'title' => '店铺类型', 'type'=>'select', 'optionlist'=>'b:天猫,c:淘宝'),
                    array('name' => 'times', 'title' => '日点击数'),
                    array('name' => 'path1', 'title' => '淘宝搜索路径占比'),
                    array('name' => 'path2', 'title' => '淘宝搜天猫路径占比'),
                    array('name' => 'path3', 'title' => '天猫搜索路径占比'),
                    array('name' => 'sleep_time', 'title' => '宝贝页停留时间(秒)'),
                    array('name' => 'click_start', 'title' => '每日开始时间(如8点)'),
                    array('name' => 'click_end', 'title' => '每日结束时间(如24点)'),
                    array('name' => 'begin_time', 'title' => '开始日期(如2014-09-23)'),
                    array('name' => 'end_time', 'title' => '截止日期(如2014-09-23)'),
                ), 
            ),
            array(
                'name' => 'tbad/check', 'title' => '关键词任务检测',
                'params' => array(
                    array('name' => 'kid', 'title' => '关键词ID'),
                ), 
            ),
            array(
                'name' => 'tbad/notfound', 'title' => '获取探测不到的关键词',
                'params' => array(
                    array('name' => 'click_start_from', 'title' => '点击开始时间起始值(时间戳)'),
                    array('name' => 'click_start_to', 'title' => '点击开始时间结束值(时间戳)'),
                ), 
            ),
        );

        $api = array(
            array('name' => 'Tbpc', 'title' => '淘宝PC'),
            array('name' => 'Tbmobi', 'title' => '淘宝移动'),
            array('name' => 'Jdpc', 'title' => '京东PC'),
            array('name' => 'Statistics', 'title' => '点击数查询'),
            array('name' => 'Tbad', 'title' => '淘宝直通车'),
        );

        foreach ($api as $k => &$v) {
            $n = 'api_' . $v['name'];
            $v['settings'] = json_encode($$n);
        }
        layout(false);

        $this->assign('api', $api); 
        $this->assign('encodedApi', json_encode($api)); 
        $this->assign('SITE', C('SITE')); 
        $this->display();
    }

    public function sign() {
        $params = array_merge($_POST, $_GET);
        if (!isset($params['timestamp'])) {
            $params['timestamp'] = time();
        } 

        $appsecret = $params['appsecret'];
        $method = $params['method'];
        $ex_params = array('sign', 'appsecret', '_', 'method');
        $filter_params = array();
        foreach ($params as $k => $v) {
            if (!in_array($k, $ex_params)) {
                $filter_params[$k] = $v;
            }
        }

        $sign = $this->_genSign($method, $appsecret, $filter_params);

        $filter_params['sign'] = $sign;
        $url = C('SITE') .  'api/' . $method . '?' . http_build_query($filter_params);

        header("Content-type: application/json");
        $data = array(
            'sign' => $sign,
            'url' => $url,
        );
        echo json_encode($data);
        exit;      
    }

    protected function _genSign($method, $appsecret, $params) {
        $params = $this->_ksort($params);
        \Common\Lib\Utils::log('api', 'api.log', $params);
        $signString = trim($method);

        foreach ($params as $k => $v) {
            $signString .= $v;   
        }
        \Common\Lib\Utils::log('api', 'api.log', 'sign string is: ' . $signString);
        \Common\Lib\Utils::log('api', 'api.log', 'appsecret is: ' . $appsecret);

        $signString = md5($signString . md5($appsecret));
        \Common\Lib\Utils::log('api', 'api.log', 'sign string is: ' . $signString);
        return strtolower($signString);
    }

    protected function _ksort($data)
    {
        ksort($data);
        foreach($data as $key => &$val){
            if (is_array($val)) {
                $val = $this->_ksort($val);
            }
        }
        return $data;
    }
}
