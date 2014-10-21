<?php
namespace Common\Lib;

class Api
{
    public $appkey = null;
    public $appsecret = null;
    public $baseUrl = null;

    public function __construct($config) {
        $this->appkey = $config['appkey'];
        $this->appsecret = $config['appsecret'];
        $this->baseUrl = $config['baseUrl'];
    }

    public function request($method, $data) {
        $sysParams = array(
            'appkey' => $this->appkey,
            'timestamp' => time(),
        );

        $params = array_merge($sysParams, $data);
        $params['sign'] = $this->_genSign($method, $params);

        $requestUrl = $this->baseUrl . $method;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $requestUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        $res = curl_exec($ch);
        curl_close($ch);
        return json_decode($res, true);
    }

    protected function _genSign($method, $params) {
        $params = $this->_ksort($params);
        $signString = trim($method);

        foreach ($params as $k => $v) {
            $signString .= $v;
        }

        $signString = md5($signString . md5($this->appsecret));
        return strtolower($signString);
    }

    protected function _ksort($data) {
        ksort($data);
        foreach($data as $key => &$val){
            if (is_array($val)) {
                $val = $this->_ksort($val);
            }
        }
        return $data;
    }
}
