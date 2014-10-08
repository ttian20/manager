<?php
// 本类由系统自动生成，仅供测试用途
namespace Api\Controller;
#use Think\Controller;

require_once 'Common/Model/ProductModel.class.php';
require_once 'Common/Model/MerchantModel.class.php';
require_once 'Common/Model/MemberModel.class.php';
require_once 'Common/Model/StoreModel.class.php';
require_once 'Common/Model/MemberOrderInfoModel.class.php';
require_once 'Common/Model/MemberAccountModel.class.php';
require_once 'Common/Model/MemberPaySpendLogModel.class.php';

class OrderController extends ApiController {

    public function Create() {
        #$necessary_args = array('product_id', 'member_id', 'sale_price', 'source');
        $necessary_args = array('product_id', 'member_id', 'source');
        $this->_checkArgs($necessary_args);

    	$product_id = $this->_params['product_id'];
        $member_id = $this->_params['member_id'];
        //$sale_price = $this->_params['sale_price'];
        $source = $this->_params['source'];
        #$source = 'app';

    	$prodModel = new \Model\ProductModel();
    	$products = $prodModel->getProductInfo($product_id);
        if (!$products) {
            $this->_error(404, 4001, 'product not exists');  
        }
    	$product = $products[0];

        $memModel = M('member');
        $member = $memModel->find($member_id);
        if (!$member) {
            $this->_error(404, 4001, 'member not exists');  
        }

        $member_product_id = $product_id . md5($member_id . microtime(true));

        $data = array();
        $data['order_id'] = date('YmdHis')."_".mt_rand();
        $data['member_id'] = $member_id;
        $data['product_id'] = $product_id;
        $data['member_product_id'] = $member_product_id;
        $data['product_type'] = $product['product_type'];
        $data['total_money'] = $product['total_money'];
        $data['sale_price'] = $product['sale_price'];
        $data['original_price'] = $product['original_price'];
        $data['pay_money'] = 0.00;
        $data['pay_type'] = '';
        $data['pay_account']= '';
        $data['order_status'] = 0;
        $data['source'] = $source;
        $data['operator'] = '';
        $data['pay_time'] = 0;

        $time = time();         
        $data['update_time'] = $time;
        $data['create_time'] = $time;
        $data['merchant_id'] = $product['merchant_id'];
        $data['mer_status'] = 0;
        
        $memOrderInfo = new \Model\MemberOrderInfoModel();          
        $memOrderResult = $memOrderInfo->addMemberOrderInfo($data);
        $this->_success(array('order' => $data));
    }

    public function Getlist(){
        $necessary_args = array('member_id');
        $this->_checkArgs($necessary_args);

        $member_id = $this->_params['member_id'];
        $orderModel = M("MemberOrderInfo");
        $res = $orderModel->where("member_id = %d", array($member_id))->select();
        $orders = array();
        if ($res) {
            $orders = $res;
        }
        $this->_success(array('orders' => $orders));
    }

    public function Getone(){
        $necessary_args = array('order_id');
        $this->_checkArgs($necessary_args);

        $order_id = $this->_params['order_id'];
        $orderModel = M("MemberOrderInfo");
        $orders = $orderModel->where("order_id = '%s'", array($order_id))->select();

        $order = array();
        if ($orders) {
            $order = $orders[0];
        }

        $this->_success(array('order' => $order));
    }
}
