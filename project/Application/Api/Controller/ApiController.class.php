<?php
namespace Api\Controller;
use Think\Controller;

class ApiController extends Controller {
    protected $_params = array();
    protected $_appkey = array();
    protected $_appsecret = null;

    
    /**
     * 验证系统参数
     */
    public function _initialize() {
        $this->_params = array_merge($_POST, $_GET);
        
        //check sys argus
        $sysArgs = array('appkey', 'sign', 'timestamp');

        foreach ($sysArgs as $k) {
            if (!isset($this->_params[$k]) || '' == trim($this->_params[$k])) {
                $this->_error(404, 1001, 'missing system argument: ' . $k);            
            }
        }

        $appkey = trim($this->_params['appkey']);
        $appkeyMdl = D('Appkey');
        $filter = array('appkey' => $appkey);
        $_appkey = $appkeyMdl->getRow($filter);
        if (!$_appkey) {
            $this->_error(404, 1002, 'appkey not exists: ' . $appkey);            
        }

        $this->_appkey = $_appkey;

        $this->_checkIp();

        //check sign
        $this->_checkSign();


        //check timestamp
        $this->_checkTimestamp();
    }

    public function index() {
        $apiName = CONTROLLER_NAME;
        $this->_error(404, 4004, 'no api named ' . $apiName);
    }

    protected function _error($resp_id, $err_id, $err_msg, $err_exp = '') {
        $error = array(
            'status' => 'fail',
            'resp_id' => $resp_id,
            'err_id' => $err_id,
            'err_msg' => $err_msg,
            'err_exp' => $err_exp,
        );
        header("Content-type: application/json; charset=utf-8");
        echo json_encode($error);
        exit;
    }

    protected function _success($resp_data) {
        $succ = array(
            'status' => 'success',
            'data' => $resp_data,
        );
        header("Content-type: application/json; charset=utf-8");
        echo json_encode($succ);
        exit;
    }

    protected function _checkSign() {
        $method = CONTROLLER_NAME . "/" . ACTION_NAME; 
        $params = $this->_ksort($this->_params);
        $signString = strtolower($method);
        $ex_params = array('sign', 'appsecret', '_', 'method');

        foreach ($params as $k => $v) {
            if (!in_array($k, $ex_params)) {
                $signString .= $v;   
            }
        }

        $signString = md5($signString . md5($this->_appkey['appsecret']));
        if (strtolower($params['sign']) != strtolower($signString)) {
            $this->_error(403, 4003, 'error sign');
        }
    }

    protected function _checkIp() {
        $whiteList = explode(',', $this->_appkey['ip']);

        $ip = $_SERVER["REMOTE_ADDR"];
        if (!in_array($ip, $whiteList)) {
            $this->_error(403, 4003, 'ip not in whitelist');
        }
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

    protected function _checkSysArgus() {
        $sysArgs = array('appkey', 'sign', 'timestamp');

        foreach ($sysArgs as $k) {
            if (!isset($this->_params[$k]) || '' === trim($this->_params[$k])) {
                $this->_error(404, 1001, 'missing system argument: ' . $k);            
            }
        }
    }

    protected function _checkArgs($args) {
        foreach ($args as $k) {
            if (!isset($this->_params[$k]) || '' === trim($this->_params[$k])) {
                $this->_error(404, 1002, 'missing argument: ' . $k);            
            }
        }
    }

    protected function _checkTimestamp() {
        $curTime = time();
        $apiTime = $this->_params['timestamp']; 
        if (($apiTime - $curTime) > 200) {
            $this->_error(403, 4001, 'error timestamp');
        }
        else {
            if (($curTime - $apiTime) > 500) {
                $this->_error(403, 4002, 'the link is time out');
            }
        }
    }
}
