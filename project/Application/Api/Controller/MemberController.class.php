<?php
// 本类由系统自动生成，仅供测试用途
namespace Api\Controller;
#use Think\Controller;

require_once 'Common/Model/ProductCategoryModel.class.php';
require_once 'Common/Model/MemberModel.class.php';
require_once 'Common/Model/MemberAccountModel.class.php';
require_once 'Common/Model/MemberProductInfoModel.class.php';
require_once 'Common/Model/SmsVerifyModel.class.php';
class MemberController extends ApiController {

    public function Login() {
        $Member = M('Member'); 

        $data = $Member->field('id,name,mobile,status')->where("name = '%s' AND password = '%s'", array($this->_params['name'], md5($this->_params['password'])))->find();
        //$this->_success($Member->data());
        if (null === $data) {
            $this->_error(404, '0001', '用户名或密码错误');            
        }
        else {
            $this->_success($data);
        }
    }

    public function SmsLogin() {
        $mobile = $this->_params['mobile'];
        $code = $this->_params['code'];

        $smsModel = new \Model\SmsVerifyModel();
        $rs = $smsModel->verify($mobile, 'login', $code);
        if (!$rs['status']) {
            $this->_error(404, '0001', $rs['error']);            
        }

        $memModel = new \Model\MemberModel();
        $memberRs = $memModel->getMemberInfoByMobile($mobile);
        if ($memberRs) {
            $member = array(
                'id' => $memberRs[0]['id'],
                'name' => $memberRs[0]['name'],
                'status' => $memberRs[0]['status'],
            );
        }
        else {
            //增加用户
            $name = $mobile;
            $data = array(
                'name' => $name,
                'mobile' => $mobile,
                'mobile_verify' => 1,
                'status' => 1,
                'update_time' => time(),
                'create_time' => time(),
            );
            $memid = $memModel->addMemberInfo($data);             
            $member = array(
                'id' => $memid,
                'name' => $name,
                'status' => 1,
            );
        }
        $this->_success($member);
    }

    public function SendLoginSms(){
        $mobile = $this->_params['mobile'];
        $type = 'login';
        $expired = 600;

        $smsModel = new \Model\SmsVerifyModel();
        $code = $smsModel->sendSms($mobile, $type, $expired);
        $sms = new \Sms(); 
        $content = '您的验证码是：' . $code . '，十分钟内有效';
        $mobiles = array($mobile);
        $sms->send($mobiles, $content);
        
        if ($sms->getError) {
            $this->_error(404, '0001', '短信发送失败');            
        }
        else {
            $this->_success(array('send' => 'true'));
        }
    }

    public function SendRegSms(){
        $mobile = $this->_params['mobile'];
        $type = 'reg';
        $expired = 600;

        $memModel = new \Model\MemberModel();
        $memberRs = $memModel->getMemberInfoByMobile($mobile);
        if ($memberRs) {
            $this->_error(404, '0001', '该手机号已注册');            
        }

        $smsModel = new \Model\SmsVerifyModel();
        $code = $smsModel->sendSms($mobile, $type, $expired);
        $sms = new \Sms(); 
        $content = '您的验证码是：' . $code . '，十分钟内有效';
        $mobiles = array($mobile);
        $sms->send($mobiles, $content);
        
        if ($sms->getError) {
            $this->_error(404, '0001', '短信发送失败');            
        }
        else {
            $this->_success(array('send' => 'true'));
        }
    }

    public function SmsReg() {
        $mobile = $this->_params['mobile'];
        $code = $this->_params['code'];

        $smsModel = new \Model\SmsVerifyModel();
        $rs = $smsModel->verify($mobile, 'reg', $code);
        if (!$rs['status']) {
            $this->_error(404, '0001', $rs['error']);            
        }

        $memModel = new \Model\MemberModel();
        $memberRs = $memModel->getMemberInfoByMobile($mobile);
        if ($memberRs) {
            $this->_error(404, '0001', '该手机号已注册');            
        }
        else {
            //增加用户
            $name = $mobile;
            $data = array(
                'name' => $name,
                'mobile' => $mobile,
                'mobile_verify' => '1',
                'status' => 1,
                'update_time' => time(),
                'create_time' => time(),
            );
            $memid = $memModel->addMemberInfo($data);             
            $member = array(
                'id' => $memid,
                'name' => $name,
                'status' => 1,
            );
            $this->_success($member);
        }
    }

    public function SmsSetPasswd() {
        $mobile = $this->_params['mobile'];
        $member_id = $this->_params['member_id'];
        $password = $this->_params['password'];

        $memModel = new \Model\MemberModel();
        $memberRs = $memModel->getMemberInfoByMobile($mobile);
        if (!$memberRs) {
            $this->_error(404, '0001', '该手机号未注册');
        }
        else {
            if ($memberRs[0]['id'] != $member_id) {
                $this->_error(404, '0001', '该手机号对应的会员ID有误');
            }
            //增加用户
            $data = array(
                'password' => md5($password),
                'update_time' => time(),
            );
            $memModel->updateMemberInfo($member_id, $data);             
            $this->_success($memberRs[0]);
        }
    }

    public function SendForgetSms() {
        $mobile = $this->_params['mobile'];
        $type = 'forget';
        $expired = 600;

        $memModel = new \Model\MemberModel();
        $memberRs = $memModel->getMemberInfoByMobile($mobile);
        if (!$memberRs) {
            $this->_error(404, '0001', '该手机号未注册');
        }

        $smsModel = new \Model\SmsVerifyModel();
        $code = $smsModel->sendSms($mobile, $type, $expired);
        $sms = new \Sms(); 
        $content = '您找回密码的验证码是：' . $code . '，十分钟内有效';
        $mobiles = array($mobile);
        $sms->send($mobiles, $content);
        
        if ($sms->getError) {
            $this->_error(404, '0002', '短信发送失败');            
        }
        else {
            $this->_success(array('send' => 'true'));
        }
    }

    public function VerifyForgetSms() {
        $mobile = $this->_params['mobile'];
        $code = $this->_params['code'];

        $smsModel = new \Model\SmsVerifyModel();
        $rs = $smsModel->verify($mobile, 'forget', $code);
        if (!$rs['status']) {
            $this->_error(404, '0001', $rs['error']);            
        }

        $memModel = new \Model\MemberModel();
        $memberRs = $memModel->getMemberInfoByMobile($mobile);
        if ($memberRs) {
            $member = array(
                'id' => $memberRs[0]['id'],
                'name' => $memberRs[0]['name'],
                'status' => $memberRs[0]['status'],
            );
            $this->_success($member);
        }
        else {
            $this->_error(404, '0001', '该手机号未注册');
        }
    }

    public function Add(){
        $User = array(
            'name' => $this->_params['name'],
            'password' => $this->_params['password'],
            'repassword' => $this->_params['repassword'],
        );

        $Member = D('Member');
        if (!$Member->create($User)) {
            //$this->_error();
            $this->_error(500, '0001', $Member->getError());            
        }
        else {
            $member_id = $Member->add();
            $mem = M('Member')->field('id,name,status')->find($member_id);
            $this->_success($mem);
        }
    }

    public function Modify() {
        if (empty($this->_params['member_id'])) {
            $this->_error(404, '0001', '请输入member_id');
        }
        $member_id = $this->_params['member_id'];
        $items = array('name', 'password', 'mobile');
        $data = array('id' => $member_id);
        foreach ($items as $v) {
            if (isset($this->_params[$v]) && $this->_params[$v]) {
                $data[$v] = $this->_params[$v];
            }
        }

        if (count($data) == 1) {
            $this->_error(404, '0001', '没有修改项');
        }

        if (isset($data['password'])) {
            $data['password'] = md5($data['password']);
        }

        $memModel = new \Model\MemberModel();
        if (isset($data['name'])) {
            $rs = $memModel->getMemberInfoByName($data['name'], 0, 0);
            if ($rs && $rs[0]['id'] != $member_id) {
                $this->_error(404, '0002', '用户名已被占用');
            }
        }

        if (isset($data['mobile'])) {
            $rs = $memModel->getMemberInfoByMobile($data['mobile']);
            if ($rs && $rs[0]['id'] != $member_id) {
                $this->_error(404, '0002', '手机号已被占用');
            }
        }

        $member_mdl = M('Member');
        if (!$member_mdl->save($data)) {
            //$this->_error();
            $this->_error(500, '0001', '更新失败');            
        }
        else {
            $this->_success($data);
        }
    }

    public function Wealth(){
        $member_name = $this->_params['name'];
        $memberModel = M('Member');
        $merModel = M("Merchant"); 
        $proModel = M("Product"); 

        $member = $memberModel->where("name = '%s'", array($member_name))->find();
        if (!$member) {
            $this->_error(404, '0001', '用户名不存在');
        }

        $memberAccountModel = new \Model\MemberAccountModel();
        $memberAccountInfos = $memberAccountModel->getMemberAccountInfo($member['id']);
        $account = $memberAccountInfos ? $memberAccountInfos[0] : array();

        $memberProductInfoModel = new \Model\MemberProductInfoModel();
        $mps = $memberProductInfoModel->getSpecifyTypeProducts($member['id']);
        //print_r($mps);
        //exit;
        $products = array();
        foreach ($mps as &$mp) {
            //$merModel->find();        
            $product = $proModel->find($mp['product_id']);
            $merchant = $merModel->find($product['merchant_id']);
            $mp['product'] = $product;
            $mp['merchant'] = $merchant;
            $products[$product['product_type']][] = $mp;
        }

        $data = array(
            'photo' => $member['photo'],
            'score' => $member['score'],
            'account' => $account['curren_money'],
            'present' => $account['present_money'],
        );

        //$this->_success(array('account' => $account, 'products' => $products));
        $this->_success(array('data' => $data, 'products' => $products));
    }

    public function Products(){
        $member_name = $this->_params['name'];
        $memberModel = M('Member');
        $member = $memberModel->where("name = '%s'", array($member_name))->find();
        if (!$member) {
            $this->_error(404, '0001', '用户名不存在');
        }

        $productCategoryModel = new \Model\ProductCategoryModel();
        $types = $productCategoryModel->getAllTypes();

        $memberProductInfoModel = new \Model\MemberProductInfoModel();
        foreach ($types as $t => $v) {
            $products[$t] = count($memberProductInfoModel->getSpecifyTypeProducts($member['id'], $t));
        } 

        $favModel = M('MemberFavorite');
        $favorites = $favModel->where("member_id = %d", array($member['id']))->select();
        $products['favorite'] = count($favorites);

        $this->_success(array('products' => $products));
    }

    public function Product(){
        $member_name = $this->_params['name'];
        $member_product_id = $this->_params['member_product_id'];
        $memberModel = M('Member');
        $member = $memberModel->where("name = '%s'", array($member_name))->find();
        if (!$member) {
            $this->_error(404, '0001', '用户名不存在');
        }

        $memberProductInfoModel = new \Model\MemberProductInfoModel();
        $product = $memberProductInfoModel->getBuyedProduct($member['id'], $member_product_id);

        $this->_success(array('product' => $product));
    }

    public function GetAddress() {
        if (empty($this->_params['member_id'])) {
            $this->_error(404, '0001', 'member_id is empty');            
        }
        $member_id = trim($this->_params['member_id']);

        $address_mdl = M('MemberAddress');
        $filter = array('member_id' => $member_id);
        $rs = $address_mdl->where($filter)->select();
        if ($rs) {
            $this->_success(array('address' => $rs[0]));
        }
        else {
            $this->_success(array('address' => array()));
        }
    }

    public function SaveAddress() {
        $current = time();
        if (empty($this->_params['member_id'])) {
            $this->_error(404, '0001', 'member_id is empty');            
        }
        $member_id = trim($this->_params['member_id']);
        $items = array('member_id', 'receiver', 'mobile', 'province', 'city', 'district', 'street');
        $data = array();
        foreach ($items as $v) {
            if (isset($this->_params[$v]) && trim($this->_params[$v])) {
                $data[$v] = trim($this->_params[$v]);
            }
        }

        $address_mdl = M('MemberAddress');
        $filter = array('member_id' => $member_id);
        $rs = $address_mdl->where($filter)->select();
        if ($rs) {
            $data['id'] = $rs[0]['id'];
            $data['update_time'] = $current;
            $address_mdl->save($data);
        }
        else {
            $data['create_time'] = $current;
            $data['update_time'] = $current;
            $address_mdl->add($data);
        }
        $this->_success($data);
    }

    public function GetComments() {
        if (empty($this->_params['member_id'])) {
            $this->_error(404, '0001', 'member_id is empty');            
        }
        $member_id = trim($this->_params['member_id']);

        $review_mdl = M('ProductReview');
        $filter = array('member_id' => $member_id);
        $rs = $review_mdl->where($filter)->select();
        $this->_success(array('comments' => $rs));
    }

    public function SaveComment() {
        $current = time();
        if (empty($this->_params['member_id'])) {
            $this->_error(404, '0001', 'member_id is empty');            
        }
        $member_id = trim($this->_params['member_id']);
        $product_id = trim($this->_params['product_id']);
        $items = array('member_id', 'product_id', 'content');
        $data = array();
        foreach ($items as $v) {
            if (isset($this->_params[$v]) && trim($this->_params[$v])) {
                $data[$v] = trim($this->_params[$v]);
            }
        }

        $product_mdl = M('Product');
        $product_filter = array('product_id' => $product_id);
        $product = $product_mdl->where($product_filter)->find();
        if (!$product) {
            $this->_error(404, '0001', 'product not exists');            
        }

        $review_mdl = M('ProductReview');
        $data['merchant_id'] = $product['merchant_id'];
        $data['status'] = '1';
        $data['create_time'] = $current;
        $data['update_time'] = $current;
        $review_mdl->add($data);
        $this->_success($data);
    }
}
