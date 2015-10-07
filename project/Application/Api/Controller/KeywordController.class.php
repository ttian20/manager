<?php
namespace Api\Controller;
class KeywordController extends ApiController {

    public function detect(){
        $args = array('kid');
        $this->_checkArgs($args);
        $filter = array(
            'id' => $this->_params['kid'],
            'appkey' => $this->_params['appkey'],
        );

        $kwdMdl = D('Keyword');
        $kwd = $kwdMdl->getRow($filter);
        if (!$kwd) {
            $this->_error(404, 4001, 'kid not found');
        }

        $priceMdl = D('Price');
        $kfilter = array('kid' => $this->_params['kid']);
        $price = $priceMdl->getRow($kfilter);

        $status = '';
        if (!$price) {
            $status = "not_start";
        }
        else {
            if ($kwd['is_detected']) {
                $status = "detected";
            }
            else {
                if ($kwd['detect_times'] >= 10) {
                    $status = "not_found";
                }
                else if ($kwd['detect_times'] > 0) {
                    $status = "detecting";
                }
                else {
                    $status = "not_start";
                }
            }
        }

        $data = array(
            'kid' => $kwd['id'],
            'is_detected' => $kwd['is_detected'],
            'detect_times' => $kwd['detect_times'],
            'price_detected' => $price ? 1 : 0,
            'status' => $status,
        );

        $this->_success($data);
    }
}
