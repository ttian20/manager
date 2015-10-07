<?php
namespace Admin\Controller;
use Think\Controller;
class ImportController extends Controller {
    public function tbpc() {
        if ($_POST) {
            print_r($_POST);
        }
        $this->display();
    }

    public function processTbpcFile() {

    }
}
