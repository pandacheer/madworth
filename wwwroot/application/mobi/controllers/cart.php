<?php

/**
 *  @说明  购物车控制器
 *  @作者  zhujian
 *  @qq    407284071
 */
class cart extends MY_Controller {

    private $terminal;

    public function __construct() {


        parent::__construct();

        $this->terminal = $this->session->userdata('isMobile');
        $this->page['title'] = 'My Cart';
        $this->load->model('template_model');
        $headView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'head');
        $this->page['head'] = $this->load->view($headView, $this->page, true);
        $footView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'foot');
        $this->page['foot'] = $this->load->view($footView, $this->page, true);
        $this->country = $this->page ['country'];
        $this->member_email = $this->session->userdata('member_email');
        $this->load->model('cart_model');
        $this->load->helper('cookie');
        $this->load->helper('language');
        $this->lang->load('sys_cart');
        $this->load->helper('form');
    }

    // 显示购物车
    public function index() {

        //http转化为https
//        if ($_SERVER["HTTPS"] <> "on") {
//            $xredir = "https://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
//            header("Location: " . $xredir);
//        }

        $this->lang->load('sys_address');
        $this->page['addCountry']['state'] = lang($this->country . 'state');
        $this->page['addCountry']['city'] = lang($this->country . 'city');
        $this->page['addCountry']['zipcode'] = lang($this->country . 'zipcode');
        $this->load->model('collection_model');
        if ($this->member_email) {
            // 获取地址
            $this->load->model('memberReceive_model');
            $this->page ['shippingAddress'] = $this->memberReceive_model->listAddsByMbId($this->country, $this->session->userdata('member_id'));
            $this->page ['shippingAddressCount'] = count($this->page ['shippingAddress']);


            // 获取账单地址
            $this->page ['billAddress'] = $this->memberReceive_model->getBillAddressById($this->country, $this->session->userdata('member_id'));
            $this->page ['billAddressCount'] = count($this->page ['billAddress']);

            //组装优惠卷信息
            $this->page ['myCoupons'] = $this->getCoupon();

            // 获取购物车表的产品信息
            $pro = $this->cart_model->getCart($this->country, $this->member_email);
            if ($pro) {
                // 获取产品信息并且组装
                $this->load->model('product_model');
                $products = $this->product_model->cartPro($this->country, $pro ['info']);

                if ($products) {
                    // 获取姓名
                    $this->load->model('member_model');
                    $this->page ['member'] = $this->member_model->getInfo($this->country, $this->session->userdata('member_id'), 'member_firstName,member_lastName');

                    foreach ($products as $key => $value) {
                        if ($value['status'] != 1 || $value['product_DetailsStatus'] != 1) {
                            unset($products[$key]);
                            $this->cart_model->delCart($this->country, $this->member_email, $value['product_dsku'], 1, 1);
                        } else {
                            $products [$key] ['collection_url'] = $this->collection_model->getCollectionUrl($this->country, $value ['product_id']);
                        }
                    }
                } else {
                    $this->cart_model->delCart($this->country, $this->member_email, 1, 1, 2);
                }
            } else {
                $products = 0;
            }
        } else {
            $arr = $this->input->cookie('cart');
            if ($arr) {
                $product = unserialize($arr);
                // 获取产品信息并且组装
                $this->load->model('product_model');
                $products = $this->product_model->cartPro($this->country, $product);
                if (!$products) {
                    delete_cookie("cart");
                    redirect("cart");
                } else {
                    foreach ($products as $key => $value) {
                        if ($value['status'] != 1 || $value['product_DetailsStatus'] != 1) {
                            unset($products[$key]);
                            $this->cart_model->delCart($this->country, $this->member_email, $value['product_dsku'], 1, 1);
                        } else {
                            $products [$key] ['collection_url'] = $this->collection_model->getCollectionUrl($this->country, $value ['product_id']);
                        }
                    }
                }
            } else {
                $products = 0;
            }
        }


        if ($products) {
            $this->page['collection_offer'] = 0;
            // 判断是否为倒计时 (ps: 绑定商品不享受倒计时)
            $this->load->model('countdown_model');
            $itemTmp = '';
            //2016-2-25 计算collection折扣
            $productList = []; //已计算折扣的产品列表
            $collectionList = []; //已知Collection的产品数量，产品总价列表
            $this->load->model('collection_model');
            //2016-2-25 end
            foreach ($products as $key => $product) {
                $countdown_id = $this->countdown_model->getInfoByProductId($this->country, $product ['product_id']);
                if ($countdown_id) {
                    $countdownInfo = $this->countdown_model->getInfoById($countdown_id);
                    if (is_array($countdownInfo) && $countdownInfo ['status'] == 2) {
                        if ($product ['bundle_type'] != 4) {
                            $products [$key] ['product_price'] = $this->countdown_model->getPrice($countdown_id, $product ['product_price']);
                        }
                    }
                }
                if ($this->page['country'] == 'AU') {
                    $itemTmp.='{ id: "' . strtok($product['product_dsku'], '/') . '",price:' . ($products [$key]['product_price'] / 100) . ',quantity:' . $product['product_qty'] . '},';
                }
                //2016-2-25 计算collection折扣
                if ($product['bundle_type'] == 1) {//不属于绑定产品才使用Collection绑定
                    $collectionTmp = $this->collection_model->getInfoByProID($this->page ['country'], $product['product_id'], '_id'); //获取产品所在collection
                    $collectionIDs = array_keys(iterator_to_array($collectionTmp));
                    $productList[$product['product_id']] = $collectionIDs;
                    foreach ($collectionIDs as $value) {
                        if (array_key_exists($value, $collectionList)) {
                            $collectionList[$value]['quantity']+=$product['product_qty'];
                            $collectionList[$value]['price']+=($product['product_qty'] * $product['product_price']);
                        } else {
                            $collectionList[$value] = array('quantity' => $product['product_qty'], 'price' => $product['product_qty'] * $products [$key]['product_price']);
                        }
                    }
                }
                //2016-2-25 计算collection折扣 end
                //加入产品checkOut数量
                $redisKey = 'T:' . $this->page['datePRC'] . ':' . $this->page ['country'] . ':' . $product ['product_id'];
                $tmp = explode('/', $product['product_dsku']);
                $this->redis->hashSet($redisKey, array('sku' => $tmp[0]));
                $this->redis->hashInc($redisKey, 'checkOut', 1);
                $this->redis->timeOut($redisKey, 259200);
            }
            if ($this->page['country'] == 'AU') {
                $itemTmp = substr($itemTmp, 0, strlen($itemTmp) - 1);
                $this->page['countrySEO'] = '<script type="text/javascript" src="//static.criteo.net/js/ld/ld.js" async="true"></script>
                                        <script type="text/javascript">
                                        window.criteo_q = window.criteo_q || [];
                                        window.criteo_q.push(
                                        { event: "setAccount", account: 22926 },
                                        { event: "setEmail", email: "' . $this->session->userdata('member_email') . '" },
                                        { event: "setSiteType", type: "m" },
                                        { event: "viewBasket", item: [' . $itemTmp . '
                                        ]}
                                        );
                                        </script>';
            }
            if ($this->input->cookie('webSite_checkOut') !== md5($this->page['datePRC'] . 'checkOut')) {//统计当天checkOut次数
                $this->input->set_cookie('webSite_checkOut', md5($this->page['datePRC'] . 'checkOut'), 2592000);
                $this->load->model('website_model');
                $this->website_model->checkOut($this->page['country']);
            }
            //2016-2-25 计算collection折扣
            $this->load->model('discount_model');
            $discountCollectinArr = $this->discount_model->getDiscountSet($this->page ['country']);
            foreach ($discountCollectinArr as $discountCollectinID) {
                if (array_key_exists($discountCollectinID, $collectionList)) {
                    $discountInfo = $this->discount_model->getInfoById($this->page ['country'], $discountCollectinID);
                    $offer = $this->calcula_offer($discountInfo, $collectionList[$discountCollectinID]);
                    if ($offer > $this->page['collection_offer']) {
                        $this->page['collection_offer'] = $offer;
                    }
                }
            }
            //2016-2-25 计算collection折扣 end 
        }

        $this->load->model('countryzone_model');
        $this->page ['States'] = $this->countryzone_model->getZoneListByCountryCode($this->page ['country']);
        $this->load->model('shipping_model');
        $this->page ['shipping'] = $this->shipping_model->getShipping($this->page ['country']);

        $this->page ['products'] = $products;

        $cartView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'cart');
        $this->load->view($cartView, $this->page);
    }

    //2016-2-25 计算collection折扣
    //重新获取购物车
    function getCartAgain() {
        if ($this->member_email) {

            // 获取购物车表的产品信息
            $pro = $this->cart_model->getCart($this->country, $this->member_email);
            if ($pro) {
                // 获取产品信息并且组装
                $this->load->model('product_model');
                $products = $this->product_model->cartPro($this->country, $pro ['info']);
            } else {
                $products = [];
            }
        } else {
            $arr = $this->input->cookie('cart');
            if ($arr) {
                $product = unserialize($arr);
                // 获取产品信息并且组装
                $this->load->model('product_model');
                $products = $this->product_model->cartPro($this->country, $product);
            } else {
                $products = [];
            }
        }
        return $products;
    }

    //根据购物车算折扣
    private function getDiscount() {
        $products = $this->getCartAgain();
        $collection_offer = 0;
        $this->load->model('collection_model');
        $this->load->model('countdown_model');
        if ($products) {
            $productList = []; //已计算折扣的产品列表
            $collectionList = []; //已知Collection的产品数量，产品总价列表 
            foreach ($products as $key => $product) {
                $countdown_id = $this->countdown_model->getInfoByProductId($this->country, $product ['product_id']);
                if ($countdown_id) {
                    $countdownInfo = $this->countdown_model->getInfoById($countdown_id);
                    if (is_array($countdownInfo) && $countdownInfo ['status'] == 2) {
                        if ($product ['bundle_type'] != 4) {
                            $products [$key] ['product_price'] = $this->countdown_model->getPrice($countdown_id, $product ['product_price']);
                        }
                    }
                }

                if ($product['bundle_type'] == 1) {//不属于绑定产品才使用Collection绑定
                    $collectionTmp = $this->collection_model->getInfoByProID($this->page ['country'], $product['product_id'], '_id'); //获取产品所在collection
                    $collectionIDs = array_keys(iterator_to_array($collectionTmp));
                    $productList[$product['product_id']] = $collectionIDs;
                    foreach ($collectionIDs as $value) {
                        if (array_key_exists($value, $collectionList)) {
                            $collectionList[$value]['quantity']+=$product['product_qty'];
                            $collectionList[$value]['price']+=($product['product_qty'] * $product['product_price']);
                        } else {
                            $collectionList[$value] = array('quantity' => $product['product_qty'], 'price' => $product['product_qty'] * $products [$key]['product_price']);
                        }
                    }
                }
            }
            $this->load->model('discount_model');
            $discountCollectinArr = $this->discount_model->getDiscountSet($this->page ['country']);
            foreach ($discountCollectinArr as $discountCollectinID) {
                if (array_key_exists($discountCollectinID, $collectionList)) {
                    $discountInfo = $this->discount_model->getInfoById($this->page ['country'], $discountCollectinID);
                    $offer = $this->calcula_offer($discountInfo, $collectionList[$discountCollectinID]);
                    if ($offer > $collection_offer) {
                        $collection_offer = $offer;
                    }
                }
            }
        }
        return $collection_offer;
    }

    //计算discount折扣金额，单位元
    private function calcula_offer($discountInfo, $colletcionProduct) {
        $level = json_decode(preg_replace('/(\w+):/is', '"$1":', $discountInfo['detail']), TRUE);
        krsort($level);
        $currValue = $discountInfo['condition'] == 1 ? $colletcionProduct['price'] / 100 : $colletcionProduct['quantity'];
        foreach ($level as $index => $value) {
            if ($index <= $currValue) {
                break;
            }
            $value = 0;
        }
        return $value ? ($discountInfo['type'] == 1 ? $value : round($currValue * $value / 100, 2)) : 0;
    }

    //2016-2-25 计算collection折扣 end 
    // 购物车添加商品 start
    public function addCart() {
        if ($this->input->is_ajax_request()) {
            $this->load->model('product_model');

            $product_type = $this->input->post('p_bundle', TRUE);
            $product_id = $this->input->post('p_id', TRUE);
            $product_sku = $this->input->post('p_sku', TRUE);
            $product_qty = $this->input->post('p_qty', TRUE);
            if (!preg_match("/^\d*$/", $product_qty) || $product_qty <= 0) {
                $product_qty = 1;
            }
            $product_attr = $this->input->post('p_attr', TRUE);

            switch ($product_type) {
                case 1 :
                    $this->_addCart_1($product_type, $product_id, $product_sku, $product_qty, $product_attr, $this->member_email);
                    break;
                case 2 :
                    $this->_addCart_2($product_type, $product_id, $product_sku, $product_qty, $product_attr, $this->member_email);
                    break;
                case 3 :
                    $this->_addCart_3($product_type, $product_id, $product_sku, $product_qty, $product_attr, $this->member_email);
                    break;
                default :
                    break;
            }
        }
    }

    public function _addCart_1($product_type, $product_id, $product_sku, $product_qty, $product_attr) {
        if ($product_attr) {
            $sku = $product_sku . '/' . $product_attr;
        } else {
            $sku = $product_sku;
        }

        $data = array(
            'product_id' => $product_id,
            'bundle_type' => (int) $product_type,
            'product_sku' => $sku,
            'product_qty' => (int) $product_qty
        );

        $status = $this->product_model->exist($this->country, $product_id, $sku, 1);


        if ($status) {

            if ($status === 4) {
                $this->addCart_result(4);
            }

            if ($this->member_email) {
                $this->addCart_result($this->cart_model->addCart($this->country, $this->member_email, $data), $product_id);
            } else {
                $this->addCart_result($this->addCartCookie($data), $product_id);
            }
        } else {
            $this->addCart_result(0);
        }
    }

    public function _addCart_2($product_type, $product_id, $product_sku, $product_qty, $product_attr) {
        $data = array(
            'product_id' => $product_id,
            'bundle_type' => (int) $product_type,
            'product_sku' => $product_sku . ',' . $product_attr,
            'product_qty' => (int) $product_qty
        );

        if ($this->product_model->exist($this->country, $product_id, $data ['product_sku'], 2)) {
            if ($this->member_email) {
                $this->addCart_result($this->cart_model->addCart($this->country, $this->member_email, $data));
            } else {
                $this->addCart_result($this->addCartCookie($data));
            }
        } else {
            $this->addCart_result(0);
        }
    }

    public function _addCart_3($product_type, $product_id, $product_sku, $product_qty, $product_attr) {
        $bundle_attr = explode('/', $product_attr);
        $data = array(
            'product_id' => $product_id,
            'bundle_type' => (int) $product_type,
            'product_sku' => $product_sku . '/' . $bundle_attr [0] . ',' . $product_sku . '/' . $bundle_attr [1],
            'product_qty' => (int) $product_qty
        );

        if ($this->product_model->exist($this->country, $product_id, $data ['product_sku'], 3)) {
            if ($this->member_email) {
                $this->addCart_result($this->cart_model->addCart($this->country, $this->member_email, $data));
            } else {
                $this->addCart_result($this->addCartCookie($data));
            }
        } else {
            $this->addCart_result(0);
        }
    }

    public function addCartCookie($data) {
        // 首先判断是否存在
        $arr = $this->input->cookie('cart');
        if ($arr) {
            $products = unserialize($arr);

            // 循环判断是否有值 有的话证明有商品 只加数量
            $state = 1;
            foreach ($products as $k => $v) {
                if (strtolower($data ['product_sku']) == strtolower($v ['product_sku'])) {
                    $products [$k] ['product_qty'] += $data ['product_qty'];
                    $state = 0;
                }
            }

            if (count($products) >= 15) {
                return 3;
            }

            // 没找到对应的商品 就添加此商品
            if ($state) {
                array_push($products, $data);
            }

            $this->input->set_cookie("cart", serialize($products), 864000);
        } else {
            $products [] = $data;
            $this->input->set_cookie("cart", serialize($products), 864000);
        }
        return 1;
    }

    //  $state==3 代表购物车只能加15个   ==4表示没有库存
    public function addCart_result($state = 0, $product_id = 0) {
        if ($state) {
            if ($state === 3) {
                exit(json_encode(array('success' => false, 'resultMessage' => lang('cart_NumberError'))));
            } else if ($state === 4) {
                exit(json_encode(array('success' => false, 'resultMessage' => lang('cart_statusError'))));
            } else {
                //加入站点加入购物车次数
                if ($this->input->cookie('webSite_addToCart') !== md5($this->page['datePRC'] . 'addToCart')) {//统计当天执行加入购物车次数
                    $this->input->set_cookie('webSite_addToCart', md5($this->page['datePRC'] . 'addToCart'), 2592000);
                    $this->load->model('website_model');
                    $this->website_model->addToCart($this->page['country']);
                }
                //加入产品点击量
                $redisKey = 'T:' . $this->page['datePRC'] . ':' . $this->page ['country'] . ':' . $product_id;
                $this->redis->hashInc($redisKey, 'addToCart', 1);
                $this->redis->timeOut($redisKey, 259200);
                exit(json_encode(array('success' => true, 'resultMessage' => lang('cart_Success'))));
            }
        } else {
            exit(json_encode(array('success' => false, 'resultMessage' => lang('cart_Error'))));
        }
    }

    // 购物车添加商品 end
    // buy_now 购物车添加商品
    /*
     * public function buy_now() {
     * $this->addCart();
     * redirect ( "cart" );
     * }
     */

    // 修改购物车上商品数量 $state 为0=>数量加1 为1=>数量减1 为2=>输入数量
    public function updateCart() {
        if ($this->input->is_ajax_request()) {
            $product_sku = $this->input->post('p_sku', TRUE);
            $product_qty = (int) $this->input->post('p_qty', 1);
            $state = $this->input->post('state', TRUE);
            $product_bundle = 1;

            // 判断用户是否登录
            if ($this->member_email) {

                $result = $this->cart_model->updateCart($this->country, $this->member_email, $product_sku, $product_qty, $state);

                if ($result) {
//                    usleep(500000);
                    exit(json_encode(array(
                        'success' => true, 'discountNumber' => $this->getDiscount()
                    )));
                }
            } else {

                $arr = $this->input->cookie('cart');
                if ($arr) {

                    $products = unserialize($arr);
                    // 查找商品
                    foreach ($products as $k => $v) {
                        if (strtolower($v['product_sku']) == strtolower($product_sku)) {
                            if ($state == 0) {
                                $products [$k] ['product_qty'] += 1;
                            } else if ($state == 1) {
                                if ($products [$k] ['product_qty'] == 1) {
                                    $products [$k] ['product_qty'] == 1;
                                } else {
                                    $products [$k] ['product_qty'] -= 1;
                                }
                            } else {
                                $products [$k] ['product_qty'] = $product_qty;
                            }
                        }
                    }

                    $this->input->set_cookie("cart", serialize($products), 864000);
                    $_COOKIE['cart'] = serialize($products);
                    exit(json_encode(array(
                        'success' => true, 'discountNumber' => $this->getDiscount()
                    )));
                }
            }
        }
    }

    // end
    // 删除购物车 $state 为0=>删除所有 为1=>删除单个
    public function delCart() {
        if ($this->input->is_ajax_request()) {
            $product_sku = 0;
            $product_bundle = 1;
            $state = 1;

            if ($state == 1) {
                $product_sku = $this->input->post('p_sku', TRUE);
            }

            if ($this->member_email) {

                $result = $this->cart_model->delCart($this->country, $this->member_email, $product_sku, $product_bundle, $state);
                if ($result) {
//                    usleep(500000);
                    exit(json_encode(array(
                        'success' => true, 'discountNumber' => $this->getDiscount()
                    )));
                }
            } else {

                $arr = $this->input->cookie('cart');
                if ($arr) {
                    $products = unserialize($arr);

                    // 判断删除状态
                    if ($state == 1) {

                        // 查找商品
                        if ($product_bundle == 1) {
                            foreach ($products as $k => $v) {
                                if (strtolower($v ['product_sku']) == strtolower($product_sku)) {
                                    //unset($products [$k]);
                                    array_splice($products, $k, 1);
                                }
                            }
                        } else {
                            foreach ($products as $k => $v) {
                                if ($v ['product_bundle'] == $product_bundle) {
                                    //unset($products [$k]);
                                    array_splice($products, $k, 1);
                                }
                            }
                        }

                        $this->input->set_cookie("cart", serialize($products), 864000);
                        $_COOKIE['cart'] = serialize($products);
                        exit(json_encode(array(
                            'success' => true, 'discountNumber' => $this->getDiscount()
                        )));
                    } else {
                        delete_cookie("cart");
                    }
                }
            }
        }
    }

    // end
    // 检测优惠券
    public function checkCoupon() {
        if ($this->input->is_ajax_request()) {
            $this->lang->load('sys_coupon');
            $coupons_id = $this->input->post('coupon_id', TRUE);
            $this->load->model('coupons_model');

            $result = $this->coupons_model->checkCouponsId($this->page ['country'], $coupons_id, $this->member_email);

            if (!$result ['success']) {
                $result ['error'] = lang($result ['error']);
            }
//            sleep(1);
            exit(json_encode($result));
        }
    }

    //获取此用户的优惠卷
    public function getCoupon() {
        $this->load->model('coupons_model');
        $myCoupons = $this->coupons_model->getMyCoupons($this->page['country'], $this->member_email);
        $beenUsed_coupons = $this->coupons_model->getBeenUsedCoupons($this->page['country'], $this->member_email);


        $couponsId = array();
        foreach ($myCoupons as $key => $myCoupon) {
            foreach ($beenUsed_coupons as $beenUsed) {
                if (strtoupper($key) == strtoupper($beenUsed['coupons_id'])) {
                    unset($myCoupons[$key]);
                }
            }
        }

        return $myCoupons;
    }

}
?>