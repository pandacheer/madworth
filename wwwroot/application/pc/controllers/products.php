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
        $footLogosView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'foot_logos');
        $this->page['footLogosView'] = $this->load->view($footLogosView, $this->page, true);
        $footView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'foot');
        $this->page['foot'] = $this->load->view($footView, $this->page, true);
        $shoppingcartView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'shoppingcart');
        $this->page['shoppingcart'] = $this->load->view($shoppingcartView, $this->page, true);

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
//        $this->page['return'] = $this->collection_model->has_collection($this->country, $collection);
//        if(!is_array($this->page['return'])){
//             redirect('home/showError/E4004');
//        }
        $this->page['pro'] = $this->product_model->findSeo($this->country, 1, $pro_url);

        if (!$this->page['pro']) {
            redirect('home/showError404');
        }
        //加入产品点击量
        $redisKey = 'T:' . $this->page['datePRC'] . ':' . $this->page ['country'] . ':' . (string) $this->page['pro']['_id'];
        $this->redis->hashSet($redisKey, array('sku' => $this->page['pro']['sku']));
        $this->redis->hashInc($redisKey, 'click', 1);
        $this->redis->timeOut($redisKey, 259200);
        $collection_name_array = $this->collection_model->getCollectionUrl($this->country, (string) $this->page['pro']['_id'], true);
        // 组装SEO信息
        $this->page['title'] = !empty($this->page['pro']['seo']['title']) ? $this->page['pro']['seo']['title'] : $this->page['pro']['title'];
        $this->page['description'] = $this->page['pro']['seo']['description'];
        $this->page['keywords'] = $this->page['pro']['seo']['keyword'];

        $a = A(array($collection_name_array[0], $collection_name_array[1]), $this->page['original_menu']);
        // 组装breadcrumb
        //$this->page['breadcrumb'] = '<li><a href="/">DrGrab</a></li><li class="active">' . $this->page['pro']['title'] . '</li>';
        $this->page['breadcrumb'] = '<li><a href="/">DrGrab</a></li>';
        if (!empty($a)) {
            foreach ($a as $ke => $va) {
                $this->page['breadcrumb'] .= '<li><a href="' . $va[1] . '">' . $va[0] . '</a></li>';
            }
        }
        $this->page['breadcrumb'] .= '<li class="active">' . $this->page['pro']['title'] . '</li>';
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
        // 获取collection里的产品
        $productId = $this->collection_model->getListByProductId($this->country, $this->page['pro']['_id']);
        $this->page['return']['seo_url'] = $this->collection_model->getCollectionUrl($this->country, $this->page['pro']['_id']);
        $this->page['seoInfo'] = '<link rel="canonical" href="' . site_url('collections/' . $this->page['return']['seo_url'] . '/products/' . $pro_url) . '" />';
        $bundles = array();
        if ($productId) {
            $this->page['specialProduct'] = $this->product_model->specialProduct($this->country, $productId);
            //推荐产品
            if (isset($this->page['pro']['relativeproduct']) && !empty($this->page['pro']['relativeproduct'])) {
                $relativePro = $this->product_model->relativeProduct($this->country, $this->page['pro']['relativeproduct']);
                if (!empty($this->page['specialProduct']) && !empty($relativePro)) {
                    $this->page['specialProduct'] = $relativePro + $this->page['specialProduct'];
                } elseif (!empty($relativePro)) {
                    $this->page['specialProduct'] = $relativePro;
                }
            }
            // 组装绑定的商品 本商品+销量最高的产品
            if ($this->page ['pro']['sku'] == current($this->page ['specialProduct'])['sku']) {
                $bundles = next($this->page ['specialProduct']);
            } else {
                $bundles = current($this->page ['specialProduct']);
            }
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
        $countdown_id = $this->countdown_model->getInfoByProductId($this->country, $this->page['pro']['_id']);

        if ($countdown_id) {
            $countdownInfo = $this->countdown_model->getInfoById($countdown_id);
//            var_dump($countdownInfo);exit;
            $time = time();
            if (is_array($countdownInfo) && $countdownInfo ['status'] == 2 && $countdownInfo['start'] < $time) {
                $this->page ['pro'] ['price'] = $this->countdown_model->getPrice($countdown_id, $this->page ['pro'] ['price']);
                if ($countdownInfo['auto_recount'] == 2) {
                    $this->page ['pro'] ['endTime'] = $this->countdown_model->getEndTime($countdownInfo ['start'], $countdownInfo ['cycle']);
                } else {
                    $this->page ['pro'] ['endTime'] = $countdownInfo['end'] >= time() ? $countdownInfo['end'] * 1000 : '';
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
//            echo date('Y-m-d H:i:s');exit;
        }

        // 计算商品价格省了多少钱 o(≥v≤)o
        if ($this->page['pro']['original']) {
            $this->page['save'] = ceil((($this->page['pro']['original'] - $this->page['pro']['price']) / $this->page['pro']['original']) * 100);
        } else {
            $this->page['save'] = 0;
        }

        if ($this->page['pro']['freebies'] == 1) {
            $this->page ['pro'] ['oprice'] = $this->page ['pro'] ['price'];
            $this->page ['pro'] ['price'] = 0;
            $this->page['save'] = 100;
        }

        if ($this->page['country'] == 'AU') {
            $this->page['countrySEO'] = '<script type="text/javascript" src="//static.criteo.net/js/ld/ld.js" async="true"></script>
                                         <script type="text/javascript">
                                         window.criteo_q = window.criteo_q || [];
                                         window.criteo_q.push(
                                         { event: "setAccount", account: 22926 },
                                         { event: "setEmail",  email: "' . $this->session->userdata('member_email') . '" },
                                         { event: "setSiteType", type: "d" },
                                         { event: "viewItem", item: "' . $this->page ['pro']['sku'] . '" }
                                         );
                                         </script>';
        }
        //获取评论
        $this->load->model('comment_model');
        $this->page ['comments'] = $this->comment_model->getInfoByProductId($this->page['pro']['_id']);
        $this->page['site_url'] = urlencode(site_url("products/" . $this->uri->segment(2)));


        //读取指定page
        $this->load->model('page_model');
        $this->page['desc_shipping'] = $this->page_model->_findSeo($this->country, 'Product-Tab-Shipping');
        $this->page['desc_payment'] = $this->page_model->_findSeo($this->country, 'Product-Tab-Payment');
        $headView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'head');
        $this->page['head'] = $this->load->view($headView, $this->page, true);

        if ($this->page ['pro']['diy']) {
            $this->page['token'] = time() . mt_rand(1, 9999999999);
            $this->page['tokenCheck'] = md5($this->page['token'] . '305046350@qq.com');
            $productView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'product_diy');
        } else {
            $productView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'product');
        }
        $this->load->view($productView, $this->page);
    }

    //上传订制产品图片
    public function uploadImg() {
        $token = $this->input->post('token');
        $tokenCheck = $this->input->post('tokenCheck');
        if (md5($token . '305046350@qq.com') != $tokenCheck) {
            exit(json_encode(array('success' => false, 'resultMessage' => 'Token error')));
        }

        //上传图片start
        $today = date('Ymd'); // time();
        $url = $_SERVER['DOCUMENT_ROOT'] . '/../uploads/diyImg/' . $today;

        if (!file_exists($url)) {
            mkdir($url, 0777, true);
        }

        $config['upload_path'] = $url;
        $config['overwrite'] = TRUE;
        $config['allowed_types'] = 'gif|jpg|jpeg|png';
//        $config['file_name'] = md5($_FILES['file']['name'] . $token);
        $config['file_name'] = $this->country . $token;
        $config['max_size'] = 6000;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) {
            $imgInfo = $this->upload->data();
            $imgInfo['url'] = '/diyImg/' . $today . '/' . $imgInfo['orig_name'];
            $subscription = $this->mongo->selectCollection('SYS_diyimg');
            $return = $subscription->update(array('imgurl' => $imgInfo['url']), array('imgurl' => $imgInfo['url'], 'upload' => (int) $today, 'cart' => 0, 'pay' => 0), array('upsert' => true));
            if ($return['ok']) {
                exit(json_encode(array('success' => true, 'imgUrl' => $imgInfo['url'])));
            } else {
                @unlink($url."/".$imgInfo['orig_name']);
                exit(json_encode(array('success' => false, 'resultMessage' => 'DB Error')));
            }
        } else {
            exit(json_encode(array('success' => false, 'resultMessage' => $this->upload->display_errors())));
        }
    }

}

?>
