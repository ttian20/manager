<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        if (!session('?user')) {
            $this->redirect('/login');    
        }
        $this->display();
    }

    public function login() {
        if (session('?user')) {
            $this->redirect('/');
        }
        if ($_POST) {
            if ($_POST['appkey'] && $_POST['appsecret']) {
                $filter = array(
                    'appkey' => trim($_POST['appkey']),
                    'appsecret' => trim($_POST['appsecret']),
                );
                $appkeyMdl = D('Appkey');
                $row = $appkeyMdl->getRow($filter);
                if ($row) {
                    session('user', $row);
                    $this->redirect('/');
                }
                else {
                    $errmsg = '错误的appkey或appsecret';
                }
            }
            else {
                $errmsg = '请输入appkey或appsecret';
            }
            $this->assign('errmsg', $errmsg);
        }
        $this->display();
    }

    public function logout() {
        session(null);
        $this->redirect('/login');    
    }
}
