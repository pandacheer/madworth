<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class preview extends MY_Controller {

    private $terminal;

    public function __construct() {
        parent::__construct();
        $this->terminal = $this->session->userdata('isMobile');
        $this->load->model('template_model');
        $headView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'head');
        $this->page['head'] = $this->load->view($headView, $this->page, true);
        $footLogosView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'foot_logos');
        $this->page['footLogosView'] = $this->load->view($footLogosView, $this->page, true);
        $footView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'foot');
        $this->page['foot'] = $this->load->view($footView, $this->page, true);
        $shoppingcartView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'shoppingcart');
        $this->page['shoppingcart'] = $this->load->view($shoppingcartView, $this->page, true);
        $this->country = $this->page['country'];
    }

    public function index($pro_url) {
        $this->load->helper('form');
        // 运输方式 每个产品页面都有 相同的数据 可优化
        $this->load->model('shipping_model');
        $this->page ['shipping'] = $this->shipping_model->getShipping($this->page ['country']);
        // 获取产品信息
        $this->load->model('product_model');
        $this->load->model('collection_model');
        $this->page['pro'] = $this->product_model->findSeo($this->country, 0, $pro_url);
        if (!$this->page['pro']) {
            echo $pro_url . ' was not find!';
        }
        // 组装breadcrumb
        $this->page['breadcrumb'] = '<li><a href="/">DrGrab</a></li><li><a>Preview</a></li><li class="active">' . $this->page['pro']['title'] . '</li>';
        //判断属于哪种模式
        if ($this->page['pro']['bundletype']) {
            if ($this->page['pro']['children']) {
                $this->page['product_bundle'] = 3;
            } else {
                $this->page['product_bundle'] = 2;
            }
        } else {
            $this->page['product_bundle'] = 1;
        }
        if ($this->page['pro']['children']) {
            $is_variants = 1;
        } else {
            $is_variants = 0;
        }

        // 组合发送前端的数据
        $this->page ['data'] = array(
            'bundle_save' => 0,
            'is_variants' => $is_variants,
            '$is_bundleVariants' => 0
        );
        // 判断是否有属性
        if ($this->page ['pro'] ['children']) {
            $is_variants = 1;
        } else {
            $is_variants = 0;
        }


        // 获取倒计时的信息 有的话修改价格 并且启用倒计时
        $this->load->model('countdown_model');
        $countdown_id = $this->countdown_model->getInfoByProductId($this->country, $this->page['pro']['_id']);

        if ($countdown_id) {
            $countdownInfo = $this->countdown_model->getInfoById($countdown_id);
            if (is_array($countdownInfo) && $countdownInfo ['status'] == 2 && $countdownInfo['end'] > time()) {
                $this->page ['pro'] ['price'] = $this->countdown_model->getPrice($countdown_id, $this->page ['pro'] ['price']);
                $this->page ['pro'] ['endTime'] = $this->countdown_model->getEndTime($countdownInfo ['start'], $countdownInfo ['cycle']);
            }
        }


        // 计算商品价格省了多少钱 o(≥v≤)o
        if ($this->page['pro']['original']) {
            $this->page['save'] = ceil((($this->page['pro']['original'] - $this->page['pro']['price']) / $this->page['pro']['original']) * 100);
        } else {
            $this->page['save'] = 0;
        }
        $previewView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'preview');
        $this->load->view($previewView, $this->page);
    }

}

?>
