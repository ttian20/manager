<?php
namespace Common\Lib;

class Utils
{
   public static function log($type, $filename, $data) {
        $path = RUNTIME_PATH . 'Logs/' . $type;
        if (!is_dir($path)) {
            @mkdir($path, 0777, true);
        }
        $filename = date('Ymd').'_'.$filename;
        $file = $path . "/" . $filename;
        if (is_string($data)) {
            $msg = $data . "\n";
        }
        elseif (is_array($data)) {
            $msg = print_r($data, true);
        }
        else {
            $msg = serialize($data) . "\n";
        }
        $msg = date("Y-m-d H:i:s") . "\n" . $msg;
        error_log($msg, 3, $file);
    }

    public static function utf2gbk($data) {
        if (is_string($data)) {
            $data = iconv('UTF-8', 'GBK', $data);
        }
        elseif (is_array($data)) {
            foreach ($data as $k => &$v) {
                $v = self::utf2gbk($v);
            }
        }
        return $data;
    }
}
