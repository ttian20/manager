<?php
namespace Api\Controller;
require_once 'Common/Model/AdModel.class.php';
class AdController extends ApiController {

    public function Homepage(){
        $adModel = new \Model\AdModel();
        $top = $adModel->getPublishedAdByPosition("homepage_top");
        $bottom = $adModel->getPublishedAdByPosition("homepage_bottom");

        $ad = array('top' => array(), 'bottom' => array());

        if ($top) {
            foreach ($top as $k => &$v) {
                $v['ad_pic'] = 'http://' . $_SERVER['HTTP_HOST'] . '/Public/Uploads/Ad/' . $v['ad_pic'];
            }
            $ad['top'] = $top;
        }

        if ($bottom) {
            foreach ($bottom as $k => &$v) {
                $v['ad_pic'] = 'http://' . $_SERVER['HTTP_HOST'] . '/Public/Uploads/Ad/' . $v['ad_pic'];
            }
            $ad['bottom'] = $bottom;
        }
        
        $this->_success($ad); 
    }

    public function Get(){
        $product_id = $this->_params['product_id'];
        $Product = M('Product'); 
        $data = $Product->find($product_id);
        if ($data) {
            $Merchant = M('Merchant');
            $mer = $Merchant->find($data['merchant_id']);
            $data['merchant'] = $mer;

            $data['stores'] = array();
            if ($data['store_id_list']) {
                $storeModel = M('Store');
                $filter = array('store_id' => array('in', $data['store_id_list']));
                $stores = $storeModel->where($filter)->order('main_store_id asc')->select(); 
                $data['stores'] = $stores;
            }
        }
        else {
            $data = array();
        }
        $this->_success(array('product' => $data));
    }

    public function Free() {
        $allow_types = array('today_free', 'free');
        $type = $this->_params['type'];
        $page_size = isset($this->_params['page_size']) ? $this->_params['page_size'] : 1;
        $page_no = isset($this->_params['page_no']) ? $this->_params['page_no'] : 1;
        
        $Product = M('Product');

        $filter_str = '';
        switch ($type) {
            case 'free':
                //break;
            case 'today_free':
                //break;
            default:
                $filter_str = 'status = 1';
        }
       
        $offset = ($page_no - 1) * $page_size; 
        $products = $Product->where($filter_str)->limit("{$offset}, {$page_size}")->select();
        $total_results = $Product->where($filter_str)->count('product_id');

        $data = array(
            'products' => $products,
            'total_results' => $total_results,  
        );
        
        $this->_success($data);
    }

    public function Toplist() {
        $allow_types = array('hot', 'free', 'new');
        $type = $this->_params['type'];
        $page_size = isset($this->_params['page_size']) ? $this->_params['page_size'] : 1;
        $page_no = isset($this->_params['page_no']) ? $this->_params['page_no'] : 1;
        
        $Product = M('Product');

        $filter_str = '';
        switch ($type) {
            case 'hot':
                //break;
            case 'free':
                //break;
            case 'new':
                //break;
            default:
                $filter_str = 'status = 1';
        }
       
        $offset = ($page_no - 1) * $page_size; 
        $products = $Product->where($filter_str)->limit("{$offset}, {$page_size}")->select();
        $total_results = $Product->where($filter_str)->count('product_id');

        $data = array(
            'products' => $products,
            'total_results' => $total_results,  
        );
        
        $this->_success($data);
    }

    public function Recommend() {
        $allow_types = array('weekly_new', 'user_recommend', 'weekly_recommend');
        $type = $this->_params['type'];
        $page_size = isset($this->_params['page_size']) ? $this->_params['page_size'] : 1;
        $page_no = isset($this->_params['page_no']) ? $this->_params['page_no'] : 1;
        
        $Product = M('Product');

        $filter_str = '';
        switch ($type) {
            case 'weekly_new':
                //break;
            case 'user_recommend':
                //break;
            case 'weekly_recommend':
                //break;
            default:
                $filter_str = "status = '1'";
        }
       
        $offset = ($page_no - 1) * $page_size;
        //exit($filter_str); 
        $products = $Product->where($filter_str)->limit("{$offset}, {$page_size}")->select();
        $total_results = $Product->where($filter_str)->count('product_id');

        /*if ($products) {
            foreach ($products as $prod) {
            }
        }*/

        $data = array(
            'products' => $products,
            'total_results' => $total_results,  
        );
        
        $this->_success($data);
    }

    public function Consume() {
        $member_name = $this->_params['name'];
        $member_product_id = $this->_params['mem_prod_id'];

        $memberProductInfoModel = new \Model\MemberProductInfoModel();

        
    }

    public function Allcategories() {
        $ProductCategoryModel = M("ProductCategory");
        $results = $ProductCategoryModel->field('category_id,category_name,category_eng,category_logo,status')->where("status = '1'")->select();
        if (!$results) {
            $results = array();
        }
        else {
            foreach ($results as $k => &$v) {
                if (!empty($v['category_logo'])) {
                    $v['category_logo'] = 'http://' . $_SERVER['HTTP_HOST'] . '/Public/Uploads/ProductCate/' . $v['category_logo'];
                }
            }
        }
        $this->_success(array('categories' => $results));
    }
}
