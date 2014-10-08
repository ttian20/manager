<?php
// 本类由系统自动生成，仅供测试用途
namespace Api\Controller;
#use Think\Controller;
require_once 'Common/Model/ProductModel.class.php';
require_once 'Common/Model/MerchantModel.class.php';
require_once 'Common/Model/MemberModel.class.php';
require_once 'Common/Model/StoreModel.class.php';
require_once 'Common/Model/MemberOrderInfoModel.class.php';
require_once 'Common/Model/MemberPaymentInfoModel.class.php';
require_once 'Common/Model/MemberAccountModel.class.php';
require_once 'Common/Model/MemberPaySpendLogModel.class.php';
class PaymentController extends ApiController {

    public function Create(){
        $order_id = trim($this->_params['order_id']);
    	//$product_id = $this->_params['product_id'];
        //$member_id = $this->_params['member_id'];
        $pay_money = trim($this->_params['pay_money']);
        $pay_balance = trim($this->_params['pay_balance']);
        $pay_type = $this->_params['pay_type'];
        $pay_account = $this->_params['pay_account'];
        $source = $this->_params['source'];

        //查看订单是否存在
        $orderModel = M('MemberOrderInfo');
        $filter = array('order_id' => $order_id);
        $order = $orderModel->where($filter)->find();
        if (!$order) {
            $this->_error(404, 4001, 'order not exists');
        }

        if (0 != $order['order_status']) {
            $this->_error(404, 4002, 'order status error');
        }

        //检查商品是否在售
        $product_id = $order['product_id'];
    	$productModel = M('Product');
    	$product = $productModel->find($product_id);
        if (!$product) {
            $this->_error(404, 4001, 'product not exists');
        }

        $member_id = $order['member_id'];
        $memberAccountModel = M('MemberAccount');
        $filter = array('member_id' => $member_id);
        $member_account = $memberAccountModel->where($filter)->find();
        if (!$member_account || bccomp($member_account['curren_money'], $pay_balance) == -1) {
            $this->_error(404, 4001, 'not enough pay balance');
        }

        //$this->_success(array('product' => $product));
        $payment_id = date('YmdHis') . "_" . mt_rand(100, 999) . "_" . floor(microtime(true));

        $data = array();
        $data['payment_id'] = $payment_id;
        $data['order_id'] = $order_id;
        $data['member_id'] = $member_id;
        $data['merchant_id'] = $order['merchant_id'];
        $data['product_id'] = $product_id;
        $data['sale_price'] = $product['sale_price'];
        $data['pay_money'] = $pay_money;
        $data['pay_balance'] = $pay_balance;
        $data['pay_type'] = '';
        $data['pay_account']= '';
        $data['source'] = $source;
  
        $time = time();         
        $data['pay_time'] = 0;
        $data['update_time'] = $time;
        $data['create_time'] = $time;
        
        $memPaymentInfo = new \Model\MemberPaymentInfoModel();          
        $memPaymentInfoResult = $memPaymentInfo->addMemberPaymentInfo($data);
        $this->_success(array('payment' => $data));
    }

    public function Success(){
        $payment_id = $this->_params['payment_id'];
        $orderModel = M('MemberOrderInfo');
        $paymentModel = M('MemberPaymentInfo');
        $memberAccountModel = M('MemberAccount');
        $productModel = M('Product');
 
        $payment_filter = array('payment_id' => $payment_id);
        $payment = $paymentModel->where($payment_filter)->find();
        if ('paid' == $payment['pay_status']) {
            $this->_error(501, 5001, 'already paid');
        }
        $order_id = $payment['order_id'];
        $member_id = $payment['member_id'];

        $order_filter = array('order_id' => $order_id);
        $order = $orderModel->where($order_filter)->find();
        
        $pay_time = time();
        $pay_data = array('pay_status' => 'paid', 'pay_time' => $pay_time, 'update_time' => $pay_time);
        $paymentModel->where($payment_filter)->save($pay_data);

        $order_data = array('order_status' => 2, 'pay_time' => $pay_time, 'update_time' => $pay_time);
        $orderModel->where($order_filter)->save($order_data);

        $account_filter = array('member_id' => $member_id);
        $member_account = $memberAccountModel->where($account_filter)->find();
        $account_data = array('curren_money' => bcsub($member_account['curren_money'], $payment['pay_balance'], 2));
        $memberAccountModel->where($account_filter)->save($account_data);

    	$product = $productModel->find($order['product_id']);

        $memberProductModel = M('MemberProductInfo');
        $data = array(
            'member_id' => $member_id,
            'product_id' => $order['product_id'],
            'product_name' => $product['product_name'],
            'member_product_id' => $order['member_product_id'],
            'product_type' => $order['product_type'],
            'total_money' => $order['total_money'],
            'used_money' => 0.0,
            'remain_money' => $order['total_money'],
            'status' => '1',
            'start_time' => $product['start_time'],
            'end_time' => $product['end_time'],
            'used_time' => 0,
            'create_time' => time(),
        );
        $result = $memberProductModel->add($data);
        if (!$result) {
            exit($memberProductModel->getDbError());
        }

        $this->_success(array('status' => 'success'));
    }

    public function Failed(){
        $this->_error(501, 5001, 'pay failed');
    }
}
