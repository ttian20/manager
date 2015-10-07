<?php
namespace Admin\Controller;
use Think\Controller;
class ExportController extends Controller {
    public function tbpc() {
        $header = array("关键词","宝贝id","店铺类型","点击数","路径","价格起始值","价格结束值","地区","所在页码","宝贝页停留时间","开始时间","结束时间","执行日期");
        $rows = array();
        $rows[] = $header;
        $row = array("测试数据","12345678","b","20","taobao","180","199","上海","1","30","8:00:00","13:00:00","2015-01-30");
        $rows[] = $row;

        $content = '';
        foreach ($rows as $row) {
            $content .= implode(",", $row) . "\n";
        }

        $filename = 'tbpc_example.csv';
        header("Content-type:text/csv;chartset=gbk");
        header("Content-Disposition:attachment;filename=".$filename);
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');

        echo $content;
        exit;
    }
}
