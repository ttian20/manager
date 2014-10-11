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
}
