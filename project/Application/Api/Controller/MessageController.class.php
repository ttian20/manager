<?php
// 本类由系统自动生成，仅供测试用途
namespace Api\Controller;
#use Think\Controller;
require_once 'Common/Model/ProductModel.class.php';
require_once 'Common/Model/MemberMessageModel.class.php';
require_once 'Common/Model/MemberMerchantRelationModel.class.php';
require_once 'Common/Model/MerchantPushInfoModel.class.php';
require_once 'Common/Model/MemberMessageLogModel.class.php';
class MessageController extends ApiController {

    public function Getlistbak(){
        $mem_id = $this->_params['member_id'];
        $start_time = $this->_params['start_time'];
        if ($start_time) {
            $start_time = strtotime($start_time);
        }
        else {
            $start_time = 0;
        }
        $mmrModel = new \Model\MemberMerchantRelationModel();
        $merchants = $mmrModel->getMerchantIdsByMemberId($mem_id);

        $msgModel = new \Model\MerchantPushInfoModel();
        $messages = $msgModel->getMessagesGroupByMerchant($mem_id, $merchants);
        if (!$messages) {
            $messages = array();
        }

        $this->_success(array('messages' => $messages));
    }

    public function Getlist(){
        $mem_id = $this->_params['member_id'];

        $msgModel = new \Model\MerchantPushInfoModel();
        $messages = $msgModel->getMemberMessages($mem_id);
        if (!$messages) {
            $messages = array();
        }

        $this->_success(array('messages' => $messages));
    }

    public function GetlistByMerchant(){
        $mem_id = $this->_params['member_id'];
        $merchant_id = $this->_params['merchant_id'];

        $msgModel = new \Model\MerchantPushInfoModel();
        $messages = $msgModel->getMemberMessages($mem_id, $merchant_id);
        if (!$messages) {
            $messages = array();
        }

        $this->_success(array('messages' => $messages));
    }

    public function Getone(){
        $message_id = $this->_params['message_id'];
        $mem_id = $this->_params['member_id'];

        $msgModel = new \Model\MerchantPushInfoModel();
        $message = $msgModel->getMessageById($message_id);

        $logModel = new \Model\MemberMessageLogModel();
        $logModel->read($mem_id, $message_id);

        if (!$message) {
            $message = array();
        }

        $this->_success(array('message' => $message));
    }

    public function Gettypes(){
        $mem_id = $this->_params['member_id'];
        $start_time = $this->_params['start_time'];
        if ($start_time) {
            $start_time = strtotime($start_time);
        }
        else {
            $start_time = 0;
        }

        $msgModel = new \Model\MemberMessageModel();
        $messages = array();
        $types = array('1', '2', '3', '4');
        $total = 0;
        foreach ($types as $t) {
            $num = count($msgModel->getMessagesByMemberIdAndType($mem_id, $t, $start_time));
            $messages[$t] = $num;
            $total += $num;
        }
        $messages['total'] = $total;
        $this->_success(array('messages' => $messages));
    }
}
