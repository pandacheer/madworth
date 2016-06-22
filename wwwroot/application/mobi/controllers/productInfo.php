<?php

/**
 *  @说明  产品详情显示页面
 *  @作者  zhujian
 *  @qq    407284071
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class productInfo extends MY_Controller {

    private $terminal;

    public function __construct() {
        parent::__construct();
        $this->terminal = $this->session->userdata('isMobile');
        $this->load->model('template_model');
        $headView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'head');
        $this->page['head'] = $this->load->view($headView, $this->page, true);
        $footView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'foot');
        $this->page['foot'] = $this->load->view($footView, $this->page, true);
        //$this->page['shoppingcart'] = $this->load->view('shoppingcart', $this->page, true);
        $this->country = $this->page ['country'];
    }

    // ajax获取产品
    public function getProduct() {
        if ($this->input->is_ajax_request()) {
            $product_id = $this->input->post('product_id');
            $this->load->model('product_model');
            $this->product_model->table($this->country);
            $product = $this->product_model->findOne($product_id);

            $result = array(
                'sku' => $product ['sku'],
                'title' => htmlspecialchars_decode($product ['title']),
                'price' => $product ['price'],
                'original' => $product ['original'],
                'weight' => $product ['weight'],
                'image' => $product ['image'],
                'variants' => $product ['variants'],
                'details' => count($product ['details']),
                'children' => $product ['children'],
                'plural' => $product ['plural']
            );

            // 获取倒计时的信息 有的话修改价格 并且启用倒计时
            $this->load->model('countdown_model');
            $countdown_id = $this->countdown_model->getInfoByProductId($this->country, $product_id);
            if ($countdown_id) {
                $countdownInfo = $this->countdown_model->getInfoById($countdown_id);
                if (is_array($countdownInfo) && $countdownInfo ['status'] == 2) {
                    $result ['price'] = $this->countdown_model->getPrice($countdown_id, $result ['price']);
                }
            }
            $result ['success'] = TRUE;

            exit(json_encode($result));
        }
    }

    // 根据属性获取价钱
    public function getAttr() {
        if ($this->input->is_ajax_request()) {
            $product_bundle = $this->input->post('product_bundle');
            $post_product_id = $this->input->post('product_id', TRUE);
            $post_product_attr = $this->input->post('product_attr', TRUE);
            $post_product_sku = $this->input->post('product_sku', TRUE);
            switch ($product_bundle) {
                case 1 :
                    $this->_getAttr_1($post_product_id, $post_product_sku, $post_product_attr);
                    break;
                case 2 :
                    $this->_getAttr_2($post_product_id, $post_product_sku, $post_product_attr);
                    break;
                case 3 :
                    $this->_getAttr_3($post_product_id, $post_product_sku, $post_product_attr);
                    break;
                default :
                    break;
            }
        }
    }

    function _getAttr_1($product_id, $post_product_sku, $post_product_attr) {
        $product_sku = $post_product_attr ? $post_product_sku . '/' . $post_product_attr : $post_product_sku;
        $this->load->helper('language');
        $this->lang->load('sys_cart');
        $this->load->model('product_model');
        $data = $this->product_model->skuPrice($this->country, $product_id, $product_sku);

        if ($data) {
            $this->load->model('countdown_model');
            $countdown_id = $this->countdown_model->getInfoByProductId($this->country, $product_id);
            if ($countdown_id) {
                $countdownInfo = $this->countdown_model->getInfoById($countdown_id);
                if (is_array($countdownInfo) && $countdownInfo ['status'] == 2) {
                    $data ['price'] = $this->countdown_model->getPrice($countdown_id, $data ['price']);
                }
            }

            $data ['save'] = $data ['original'] ? ceil((($data ['original'] - $data ['price']) / $data ['original']) * 100) : 0;
            exit(json_encode(array(
                'success' => true,
                'resultMessage' => $data
            )));
        } else {
            exit(json_encode(array(
                'success' => false,
                'resultMessage' => lang('cart_Error')
            )));
        }
    }

    function _getAttr_2($product_id, $post_product_sku, $post_product_attr) {
        $product_sku = $post_product_sku;
        $this->load->helper('language');
        $this->lang->load('sys_cart');
        $this->load->model('product_model');
        $data = $this->product_model->skuPrice($this->country, $product_id, $product_sku);

        if ($data) {
            $this->load->model('countdown_model');
            $countdown_id = $this->countdown_model->getInfoByProductId($this->country, $product_id);
            if ($countdown_id) {
                $countdownInfo = $this->countdown_model->getInfoById($countdown_id);
                if (is_array($countdownInfo) && $countdownInfo ['status'] == 2) {
                    $data ['price'] = $this->countdown_model->getPrice($countdown_id, $data ['price']);
                }
            }
            $bundleInfo = $this->product_model->findSelfBundle($product_id);
            foreach ($bundleInfo['plural'] as $pluralList) {
                if ($pluralList['number'] == $post_product_attr) {
                    $data['price'] = $data['price'] * $post_product_attr - $pluralList['price'];
                    $data['original'] = $data['original'] * $pluralList['number'];
                    break;
                }
            }

            $data ['save'] = $data ['original'] ? ceil((($data ['original'] - $data ['price']) / $data ['original']) * 100) : 0;
            exit(json_encode(array(
                'success' => true,
                'resultMessage' => $data
            )));
        } else {
            exit(json_encode(array(
                'success' => false,
                'resultMessage' => lang('cart_Error')
            )));
        }
    }

    function _getAttr_3($product_id, $post_product_sku, $post_product_attr) {
        $detailsSkuArr = explode("/", $post_product_attr);
        $product_sku = $post_product_sku;
        $this->load->helper('language');
        $this->lang->load('sys_cart');
        //提取倒计时
        $haveCountdown = false;

        $this->load->model('countdown_model');
        $countdown_id = $this->countdown_model->getInfoByProductId($this->country, $product_id);
        if ($countdown_id) {
            $countdownInfo = $this->countdown_model->getInfoById($countdown_id);
            if (is_array($countdownInfo) && $countdownInfo ['status'] == 2) {
                $haveCountdown = TRUE;
            }
        }
        $bundlePrice = 0;
        $bundleOriginal = 0;
        $this->load->model('product_model');
        foreach ($detailsSkuArr as $detailsSku) {
            $detailsSku = $product_sku . '/' . $detailsSku;
            $data = $this->product_model->skuPrice($this->country, $product_id, $detailsSku);
            if ($data) {
                if ($haveCountdown) {
                    $data ['price'] = $this->countdown_model->getPrice($countdown_id, $data ['price']);
                }
                $bundlePrice = $bundlePrice + $data ['price'];
                $bundleOriginal = $bundleOriginal + $data ['original'];
            } else {
                exit(json_encode(array(
                    'success' => false,
                    'resultMessage' => lang('cart_Error')
                )));
            }
        }
        $data ['price'] = $bundlePrice;
        $data ['original'] = $bundleOriginal;
        $bundleInfo = $this->product_model->findSelfBundle($product_id);
        foreach ($bundleInfo['plural'] as $pluralList) {
            if ($pluralList['number'] == count($detailsSkuArr)) {
                $data['price'] = $data['price'] - $pluralList['price'];
                break;
            }
        }

        $data ['save'] = $data ['original'] ? ceil((($data ['original'] - $data ['price']) / $data ['original']) * 100) : 0;
        exit(json_encode(array(
            'success' => true,
            'resultMessage' => $data
        )));
    }

    // 根据绑定属性获取价钱 =.= 感觉问题很大 待修改优化~~~~
    public function getBundleAttr() {
        $bundle_index = $this->input->post('bundle_index', TRUE);
        $product_id = $this->input->post('bundle_' . $bundle_index . '_product_id', TRUE);
        $product_sku = $this->input->post('bundle_' . $bundle_index . '_product_sku', TRUE) . $this->input->post('bundle_' . $bundle_index . '_product_attr', TRUE);

        $this->load->model('product_model');
        $data = $this->product_model->skuPrice($this->country, $product_id, $product_sku);

        exit(json_encode($data));
    }

}

?>
