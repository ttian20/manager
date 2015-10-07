<?php
namespace Report\Controller;
use Think\Controller;
class StatisticsController extends Controller {
    public function _initialize() {
        if (!session('?user')) {
            $this->redirect('/login');    
        }
    }

    public function realtime() {
        $kwdMdl = D('Keyword');
        $pageSize = 40;
        $pageNo = $_GET['p'];

        $appkey = session('user.appkey');
        $filter = array('status' => 'active', 'appkey' => $appkey);
        $count = $kwdMdl->where($filter)->count();
        $page = new \Think\Page($count, $pageSize);
        $show = $page->show();
        $list = $kwdMdl->getPager($filter, $pageNo, $pageSize, 'id DESC');

        $this->assign('page', $show);
        $this->assign('list', $list);
        
        $this->display();
    }

    public function history($date = '') {
        $appkey = session('user.appkey');

        $clicksMdl = D('Clicks');
        $pageSize = 40;
        $pageNo = $_GET['p'];
        if ('' == $date) {
            $date = date('Ymd', time()-86400);
        }

        $filter = array('date' => $date);
        $count = $clicksMdl->join("keyword ON keyword.id = clicks.kid AND keyword.appkey = '{$appkey}'")->where($filter)->count();
        $page = new \Think\Page($count, $pageSize);
        $show = $page->show();
        $list = $clicksMdl->getPager($filter, $pageNo, $pageSize, 'id DESC', $appkey);


        $this->assign('page', $show);
        $this->assign('list', $list);
        $this->assign('date', $date);
        
        $this->display();
    }

    public function info($id) {
        $kwdMdl = D('Keyword');
        $priceMdl = D('Price');
        $tbpcMdl = D('KeywordTbpc');

        $keyword = $kwdMdl->getRow(array('id' => $id));
        $price = $priceMdl->getRow(array('kid' => $id));
        $tbpc = $tbpcMdl->getRow(array('kid' => $id));

/*        echo "<pre>";
        print_r($keyword);
        print_r($price);
        print_r($tbpc);
        echo "</pre>";*/

        $this->assign('keyword', $keyword);
        $this->assign('tbpc', $tbpc);
        $this->assign('price', $price);
        
        $this->display();
    }



}
