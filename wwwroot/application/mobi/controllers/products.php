<?php

/**
 *  @说明  产品详情显示页面
 *  @作者  zhujian
 *  @qq    407284071
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class products extends MY_Controller {

    private $terminal;

    public function __construct() {
        parent::__construct();
        $this->terminal = $this->session->userdata('isMobile');
        $this->load->model('template_model');
        $headView = $this->template_model->getStyle($this->terminal, $this->page ['country'], 'head');
        $this->page ['head'] = $this->load->view($headView, $this->page, true);
        $footView = $this->template_model->getStyle($this->terminal, $this->page ['country'], 'foot');
        $this->page ['foot'] = $this->load->view($footView, $this->page, true);
        // $this->page['shoppingcart'] = $this->load->view('shoppingcart', $this->page, true);
        $this->country = $this->page ['country'];
    }

    public function index($pro_url) {
        $this->load->helper('form');

        // 运输方式 每个产品页面都有 相同的数据 可优化
        $this->load->model('shipping_model');
        $this->page ['shipping'] = $this->shipping_model->getShipping($this->page ['country']);

        // 获取产品信息
        $this->load->model('product_model');
        $this->load->model('collection_model');
        // $this->page['return'] = $this->collection_model->has_collection($this->country, $collection);
        // if(!is_array($this->page['return'])){
        // redirect('home/showError/E4004');
        // }
        $this->page ['pro'] = $this->product_model->findSeo($this->country, 1, $pro_url);
        if (!$this->page ['pro']) {
            redirect('home/showError404');
        }
        // 加入产品点击量
        $redisKey = 'T:' . $this->page ['datePRC'] . ':' . $this->page ['country'] . ':' . (string) $this->page ['pro'] ['_id'];
        $this->redis->hashSet($redisKey, array('sku' => $this->page['pro']['sku']));
        $this->redis->hashInc($redisKey, 'click', 1);
        $this->redis->timeOut($redisKey, 259200);
        $collection_name_array = $this->collection_model->getCollectionUrl($this->country, (string) $this->page ['pro'] ['_id'], true);
        // 组装SEO信息
        $this->page ['title'] = !empty($this->page ['pro'] ['seo'] ['title']) ? $this->page ['pro'] ['seo'] ['title'] : $this->page ['pro'] ['title'];

        $this->page ['description'] = $this->page ['pro'] ['seo'] ['description'];
        $this->page ['keywords'] = $this->page ['pro'] ['seo'] ['keyword'];
        $a = A(array(
            $collection_name_array [0],
            $collection_name_array [1]
                ), $this->page ['original_menu']);
        // 组装breadcrumb
        $this->page ['breadcrumb'] = '<li><a href="/">DrGrab</a></li>';
        if (!empty($a)) {
            foreach ($a as $ke => $va) {
                $this->page ['breadcrumb'] .= ' > <li><a href="' . $va [1] . '" style="color:#00B6C6">' . $va [0] . '</a></li>';
            }
        }
        // 判断属于哪种模式
        if ($this->page ['pro'] ['bundletype']) {
            if ($this->page ['pro'] ['children']) {
                $this->page ['product_bundle'] = 3;
            } else {
                $this->page ['product_bundle'] = 2;
            }
        } else {
            $this->page ['product_bundle'] = 1;
        }
        // 获取collection里的产品
        $productId = $this->collection_model->getListByProductId($this->country, $this->page ['pro'] ['_id']);
        $this->page ['return'] ['seo_url'] = $this->collection_model->getCollectionUrl($this->country, $this->page ['pro'] ['_id']);
        $this->page ['seoInfo'] = '<link rel="canonical" href="' . site_url('collections/' . $this->page ['return'] ['seo_url'] . '/products/' . $pro_url) . '" />';

        $this->page ['specialProduct'] = $this->product_model->specialProduct($this->country, $productId);
        // 组装绑定的商品 本商品+销量最高的产品
        if ($this->page ['pro'] ['sku'] == current($this->page ['specialProduct'])['sku']) {
            $bundles = next($this->page ['specialProduct']);
        } else {
            $bundles = current($this->page ['specialProduct']);
        }
        if ($bundles) {
            // 获取绑定的总价格 不和倒计时关联
            $bundle_price = $this->page ['pro'] ['bundle'] + $bundles ['bundle'];
            // 计算绑定价格省了多少钱 o(≥v≤)o
            $price = $this->page ['pro'] ['price'] + $bundles ['price'];
            $bundle_save = ceil((($price - $bundle_price) / $price) * 100);

            // 获取绑定商品的详细信息

            $this->page ['bundlePro'] = $this->product_model->findOne($bundles ['_id']);

            // 判断绑定是否有属性
            if ($this->page ['bundlePro'] ['children']) {
                $is_bundleVariants = 1;
            } else {
                $is_bundleVariants = 0;
            }
        } else {
            $bundle_save = 0;
            $is_bundleVariants = 0;
        }

        // 判断是否有属性
        if ($this->page ['pro'] ['children']) {
            $is_variants = 1;
        } else {
            $is_variants = 0;
        }

        // 组合发送前端的数据
        $this->page ['data'] = array(
            'bundle_save' => $bundle_save,
            'is_variants' => $is_variants,
            '$is_bundleVariants' => $is_bundleVariants
        );

        // 获取倒计时的信息 有的话修改价格 并且启用倒计时
        $this->load->model('countdown_model');
        $countdown_id = $this->countdown_model->getInfoByProductId($this->country, $this->page ['pro'] ['_id']);

        if ($countdown_id) {
            $countdownInfo = $this->countdown_model->getInfoById($countdown_id);
            // var_dump($countdownInfo);exit;
            $time = time();
            if (is_array($countdownInfo) && $countdownInfo ['status'] == 2 && $countdownInfo ['start'] < $time) {
                $this->page ['pro'] ['price'] = $this->countdown_model->getPrice($countdown_id, $this->page ['pro'] ['price']);
                if ($countdownInfo ['auto_recount'] == 2) {
                    $this->page ['pro'] ['endTime'] = $this->countdown_model->getEndTime($countdownInfo ['start'], $countdownInfo ['cycle']);
                } else {
                    $this->page ['pro'] ['endTime'] = $countdownInfo ['end'] >= time() ? $countdownInfo ['end'] * 1000 : '';
                }


                if ($this->page ['pro'] ['children']) {
                    foreach ($this->page ['pro'] ['details'] as $key => $productDetails) {
                        unset($this->page ['pro'] ['details'][$key]['cost']);
                        $this->page ['pro'] ['details'][$key]['price'] = $this->countdown_model->getPrice($countdown_id, $productDetails ['price']);
                        if ($this->page ['pro']['freebies']) {
                            $this->page ['pro'] ['details'][$key]['save'] = 100;
                        } else {
                            $this->page ['pro'] ['details'][$key]['save'] = ceil((($productDetails['original'] - $this->page ['pro'] ['details'][$key]['price']) / $productDetails['original']) * 100);
                        }
                    }
                }
            }
            // echo date('Y-m-d H:i:s');exit;
        }

        // 计算商品价格省了多少钱 o(≥v≤)o
        if ($this->page ['pro'] ['original']) {
            $this->page ['save'] = ceil((($this->page ['pro'] ['original'] - $this->page ['pro'] ['price']) / $this->page ['pro'] ['original']) * 100);
        } else {
            $this->page ['save'] = 0;
        }
        if ($this->page ['pro'] ['freebies'] == 1) {
            $this->page ['pro'] ['oprice'] = $this->page ['pro'] ['price'];
            $this->page ['pro'] ['price'] = 0;
            $this->page ['save'] = 100;
        }
        if ($this->page['country'] == 'AU') {
            $this->page['countrySEO'] = '<script type="text/javascript" src="//static.criteo.net/js/ld/ld.js" async="true"></script>
                                         <script type="text/javascript">
                                         window.criteo_q = window.criteo_q || [];
                                         window.criteo_q.push(
                                         { event: "setAccount", account: 22926 },
                                         { event: "setEmail",  email: "' . $this->session->userdata('member_email') . '" },
                                         { event: "setSiteType", type: "m" },
                                         { event: "viewItem", item: "' . $this->page ['pro']['sku'] . '" }
                                         );
                                         </script>';
        }
        // 获取评论
        $this->load->model('comment_model');
        $this->page ['comments'] = $this->comment_model->getInfoByProductId($this->page ['pro'] ['_id']);
        // 读取指定page
        $this->load->model('page_model');
        $this->page ['desc_shipping'] = $this->page_model->_findSeo($this->country, 'Product-Tab-Shipping');
        $this->page ['desc_payment'] = $this->page_model->_findSeo($this->country, 'Product-Tab-Payment');
        $productView = $this->template_model->getStyle($this->terminal, $this->page ['country'], 'product');
        $this->load->view($productView, $this->page);
    }

}

?>
