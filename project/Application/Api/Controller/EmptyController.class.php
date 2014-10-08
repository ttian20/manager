<?php
namespace Api\Controller;
use Think\Controller;

class EmptyController extends ApiController {

    public function index() {
        $apiName = CONTROLLER_NAME;
        $this->error(404, 4004, 'no api named ' . $apiName);
    }
}
