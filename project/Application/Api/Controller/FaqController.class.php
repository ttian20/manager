<?php
// 本类由系统自动生成，仅供测试用途
namespace Api\Controller;
#use Think\Controller;

class FaqController extends ApiController {

    public function Getlist() {
        $Faq = M('Faq'); 

        $data = $Faq->field('id,faq_name')->where()->select();

        if (!$data) {
            $data = array();
        }
        $this->_success($data);
    }

    public function Getone() {
        $faq_id = $this->_params['faq_id'];

        $Faq = M('Faq'); 

        $filter = array('id' => $faq_id);
        $data = $Faq->field('id,faq_name,faq_answer')->where($filter)->find();

        if (!$data) {
            $data = array();
        }
        $this->_success($data);
    }
}
