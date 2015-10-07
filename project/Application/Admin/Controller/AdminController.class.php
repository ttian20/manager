<?php
namespace Admin\Controller;
use Think\Controller;
class AdminController extends Controller {
    public function __construct(){
        parent::__construct();
        if (!session('?user')) {
            $this->redirect('/login');    
        }
    }
}
