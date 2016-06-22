<?php

/**
 * @文件： collection
 * @时间： 2015-7-21 13:41:09
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：前台页面通过Collection的seo url调用产品
 */
class collections extends MY_Controller {

    protected $collectionMap;
    protected $prePage;
    private $terminal;

    function __construct() {
        parent::__construct();
        $this->terminal = $this->session->userdata('isMobile');
        $this->prePage = 12; //每页数量
        $this->load->model('template_model');
        $footLogosView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'foot_logos');
        $this->page['footLogosView'] = $this->load->view($footLogosView, $this->page, true);
        $footView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'foot');
        $this->page['foot'] = $this->load->view($footView, $this->page, true);
        $shoppingcartView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'shoppingcart');
        $this->page['shoppingcart'] = $this->load->view($shoppingcartView, $this->page, true);
        $this->country = $this->page ['country'];
        $this->collectionMap = array(
            'audio' => ['new' => 'Speaker-Headset', 'tag' => 1],
            'condoms' => ['new' => 'Adults', 'tag' => 1],
            'event' => ['new' => 'New-Deals', 'tag' => 1],
            'get-ready-for-winter-men' => ['new' => 'Mens-Fashion', 'tag' => 1],
            'get-ready-for-winter-women' => ['new' => 'Womens-Fashion', 'tag' => 1],
            'aircraft-cup' => ['new' => 'Adults', 'tag' => 1],
            'all' => ['new' => 'New-Deals', 'tag' => 1],
            'mens-summer-polo-shirts' => ['new' => 'mens-fashion', 'tag' => 1],
            'adult-deals' => ['new' => 'Adults', 'tag' => 1],
            'baby-kids' => ['new' => 'Kids', 'tag' => 1],
            'bedroom-furniture' => ['new' => 'Bedroom', 'tag' => 1],
            'car-electronics-accessories' => ['new' => 'camera-accessories', 'tag' => 1],
            'cats' => ['new' => 'cat', 'tag' => 1],
            'cinderalla' => ['new' => 'Cindereala-Series', 'tag' => 1],
            'dogs' => ['new' => 'Dog', 'tag' => 1],
            'fashion-accessories' => ['new' => 'Fashion', 'tag' => 1],
            'faucet' => ['new' => 'Faucet-Shower-Head', 'tag' => 1],
            'flash-drive-mp3-players' => ['new' => 'Flash-Drive-Media', 'tag' => 1],
            'frontpage' => ['new' => 'New-Deals', 'tag' => 1],
            'frozen' => ['new' => 'Frozen-Series', 'tag' => 1],
            'home-garden' => ['new' => 'Home', 'tag' => 1],
            'apple-device-cases' => ['new' => 'Mobile-Apple-Accessories', 'tag' => 1],
            'bikini-show' => ['new' => 'Swimsuit-Bikini', 'tag' => 1],
            'camera' => ['new' => 'Camera-Accessories', 'tag' => 1],
            'new-arrival-watches' => ['new' => 'Watch', 'tag' => 1],
            '15' => ['tag' => 0],
            '20' => ['tag' => 0],
            '5' => ['tag' => 0],
            'assassin-s-creed-theme-day-grab' => ['tag' => 0],
            'compression-tights' => ['tag' => 0],
            'fetish-play-toys' => ['tag' => 0],
            'lazybones-day' => ['tag' => 0],
            '9-14' => ['tag' => 0],
            'backpack' => ['tag' => 0],
            'freebies' => ['tag' => 0]
        );
    }

    function index($seo_url, $pageFun = 'page', $currentPage = 1, $sort = '', $filter1 = 'ALL', $filter2 = 'ALL', $offset = 0) {
        $seo_url = $this->uri->segment(2);
        if (!(int) $currentPage) {
            $currentPage = 1;
        }

        if ($this->uri->segment(3) == 'products') {
            $product_seo = $this->uri->segment(4);
            if (array_key_exists($seo_url, $this->collectionMap)) {
                if ($this->collectionMap[$seo_url]["tag"] === 1) {
                    redirect('collections/' . $this->collectionMap[$seo_url]["new"] . '/products/' . $product_seo, 'auto', 301);
                } else {
                    redirect('/', 'auto', 301);
                }
            }
            $this->_products($seo_url, $product_seo);
        } else {
            if (array_key_exists($seo_url, $this->collectionMap)) {
                if ($this->collectionMap[$seo_url]["tag"] === 1) {
                    redirect('collections/' . $this->collectionMap[$seo_url]["new"], 'auto', 301);
                } else {
                    redirect('/', 'auto', 301);
                }
            }

            $filter1 = urldecode($filter1);
            $filter2 = str_replace(utf8_encode('中'), '/', urldecode($filter2));
            $this->load->helper('form');
            $collection = $this->mongo->{$this->page['country'] . '_collection'};
            $doc = $collection->findOne(array('seo_url' => new MongoRegex('/^' . $seo_url . '$/i')));
            if (!is_array($doc)) {
                redirect('home/showError404');
            }
            if (isset($doc['description']) && ($doc['description'] == '<p><br></p>' || $doc['description'] == htmlspecialchars('<p><br></p>'))) {
                $doc['description'] = '';
            } elseif (!isset($doc['description'])) {
                $doc['description'] = '';
            }
            if (isset($doc['description2']) && ($doc['description2'] == '<p><br></p>' || $doc['description2'] == htmlspecialchars('<p><br></p>'))) {
                $doc['description2'] = '';
            } elseif (!isset($doc['description2'])) {
                $doc['description2'] = '';
            }
            $a = A(array($seo_url, $doc['seo_title']), $this->page['original_menu']);
            $this->page['title'] = $doc['seo_title'];
            $this->page['description'] = $doc['seo_description'];
            $this->page['keywords'] = $doc['seo_keyword'];

            if ($doc['show_comment'] == 2) {
                $this->load->model('comment_model');
                $this->page['collectionComment'] = $this->comment_model->getInfoByCollectionId($doc['_id']);
            } else {
                $this->page['collectionComment'] = [];
            }
            $this->page['doc'] = $doc;

            if ($sort) {
                $this->page['doc']['sort'] = $sort;
            }

            $collectionView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'product_list_col' . $this->page['doc']['columns']);


            //$this->page['breadcrumb'] = '<li><a href="/">DrGrab</a></li><li class="active"><a href="/collections/' . $this->page['doc']['seo_url'] . '">' . $this->page['doc']['title'] . '</a></li>';
            $this->page['breadcrumb'] = '<li><a href="/">DrGrab</a></li>';
            if (!empty($a)) {
                foreach ($a as $ke => $va) {
                    $class = count($a) - 1 == $ke ? ' class="active"' : '';
                    $this->page['breadcrumb'] .= '<li' . $class . '><a href="' . $va[1] . '">' . $va[0] . '</a></li>';
                }
            }
            $offset = ($currentPage - 1) * $this->prePage;
            $doc = $this->getPageProduct($this->page['country'], $this->page['doc'], $filter1, $filter2, $offset);
            $maxPage = ceil($doc['rows'] / $this->prePage);

            if ($currentPage > 1) {
                $this->page['seoInfo'] = '<link rel="canonical" href="' . site_url('collections/' . $seo_url . '/page/' . $currentPage) . '" />';
                $this->page['seoInfo'] .= '<link rel="prev" href="' . site_url('collections/' . $seo_url . '/page/' . ($currentPage - 1)) . '" />';
            } else {
                $this->page['seoInfo'] = '<link rel="canonical" href="' . site_url('collections/' . $seo_url) . '" />';
            }
            if ($currentPage < $maxPage) {
                $this->page['seoInfo'] .= '<link rel="next" href="' . site_url('collections/' . $seo_url . '/page/' . ($currentPage + 1)) . '" />';
            }
            $headView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'head');
            $this->page['head'] = $this->load->view($headView, $this->page, true);
            $this->page['doc'] = $doc;
            $this->load->model('countdown_model');
            $itemTmp = '';
            $i = 0;
            foreach ($this->page['doc']['allow'] as $product_id => $productInfo) {
                unset($this->page['doc']['allow'][$product_id]['_id']);
                $countdown_id = $this->countdown_model->getInfoByProductId($this->page['country'], $product_id);
                if ($countdown_id) {
                    $countdownInfo = $this->countdown_model->getInfoById($countdown_id);
                    $time = time();
                    if (is_array($countdownInfo) && $countdownInfo['status'] == 2 && $countdownInfo['start'] < $time) {
                        $this->page['doc']['allow'][$product_id]['price'] = $this->countdown_model->getPrice($countdown_id, $productInfo['price']);
                        if ($countdownInfo['auto_recount'] == 2) {
                            $this->page['doc']['allow'][$product_id]['endTime'] = $this->countdown_model->getEndTime($countdownInfo['start'], $countdownInfo['cycle']);
                        } else {
                            $this->page['doc']['allow'][$product_id]['endTime'] = $countdownInfo['end'] >= time() ? $countdownInfo['end'] * 1000 : '';
                        }
                    }
                }
                if ($productInfo['freebies'] == 1) {
                    $this->page['doc']['allow'][$product_id]['price'] = 0;
                }
                if ($this->page['country'] == 'AU' && $i < 3) {
                    $itemTmp.='"' . $productInfo['sku'] . '",';
                    $i++;
                }
            }

            if ($this->page['country'] == 'AU') {
                $itemTmp = substr($itemTmp, 0, strlen($itemTmp) - 1);
                $this->page['countrySEO'] = '<script type="text/javascript" src="//static.criteo.net/js/ld/ld.js" async="true"></script>
                                             <script type="text/javascript">
                                             window.criteo_q = window.criteo_q || [];
                                             window.criteo_q.push(
                                             { event: "setAccount", account: 22926 },
                                             { event: "setEmail", email: "' . $this->session->userdata('member_email') . '" },
                                             { event: "setSiteType", type: "d" },
                                             { event: "viewList", item:[ ' . $itemTmp . ' ]}
                                             );
                                             </script>';
            }
            $this->page['currentPage'] = $currentPage;
            $this->page['canonical'] = site_url('collections/' . $seo_url);
            $this->load->view($collectionView, $this->page);
        }
    }

    function getPageProduct($coturny_code, $doc, $filter1, $filter2, $offset) {
//        echo "<pre>";
//        print_r($doc);exit;
        $filter1 = str_replace($this->page['currency'], '$', urldecode($filter1));
        //需要查找的字段
        $getFields = array('title' => true, 'sku' => true, 'seo_url' => true, 'price' => true, 'sold' => true, 'original' => TRUE, 'image' => TRUE, 'children' => TRUE, 'bundletype' => true, 'freebies' => TRUE, 'diy' => TRUE); //需要查找的字段

        $productMongo = $this->mongo->{$coturny_code . '_product'};
        $this->load->model('dropdown_model');

        if (empty($doc['allow'])) {
            $doc['allow'] = array();
        }
        if ($doc['model'] == 1) {//手动模式
            $mongoCondtion = array(
                '_id' => array('$in' => $doc['allow']),
                'status' => 1
            );
            //获取tag2下拉
            $doc['tag2'] = $this->dropdown_model->tag($coturny_code, $mongoCondtion, 'Tag2');

            if ($filter1 && $filter1 != 'ALL') {
                $mongoCondtion['tag.Tag1'] = $filter1;
            }
            if ($filter2 && $filter2 != 'ALL') {
                $mongoCondtion['tag.Tag2'] = $filter2;
            }

            $rows = $productMongo->find($mongoCondtion)->count(); //产品总数

            if ($rows > $offset) {

                if ($doc['sort'] == 'manual') {
                    $allowProduct = iterator_to_array($productMongo->find($mongoCondtion, $getFields));
                    foreach ($doc['allow'] as $value) {
                        if (array_key_exists((string) $value, $allowProduct)) {
                            $searchProduct[(string) $value] = $allowProduct[(string) $value];
                        }
                    }
                    $doc['allow'] = array_slice($searchProduct, $offset, $this->prePage);

                    /*
                      $productInfos = $productMongo->find($mongoCondtion, $getFields)->limit($this->prePage)->skip($offset);
                      $doc['allow'] = iterator_to_array($productInfos);
                     */
                } else {
                    $sortArr = explode(',', $doc['sort']);
                    $sort = array($sortArr[0] => (int) $sortArr[1]);
                    $productInfos = $productMongo->find($mongoCondtion, $getFields)->sort($sort)->limit($this->prePage)->skip($offset);
                    $doc['allow'] = iterator_to_array($productInfos);
                }
                if ($this->input->is_ajax_request()) {
                    if (!empty($doc['allow'])) {
                        foreach ($doc['allow'] as $kk => $vv) {
                            $img = 'http:' . IMAGE_DOMAIN . '/product/' . $vv['sku'] . '/' . $vv['sku'] . '.jpg';
                            if (!@fopen($img, 'r')) {
                                $img = IMAGE_DOMAIN . $vv['image'];
                            }
                            $doc['allow'][$kk]['img'] = $img;
                            if ($vv['freebies'] == 1) {
                                $doc['allow'][$kk]['price'] = 0;
                            }
                        }
                    }
                } else {
                    foreach ($doc['allow'] as $kk => $vv) {
                        if ($vv['freebies'] == 1) {
                            $doc['allow'][$kk]['price'] = 0;
                        }
                    }
                }
                $offset+=count($doc['allow']);
            } else {
                $doc['allow'] = array();
            }
            $doc['offset'] = $offset;
        } else {//条件模式
            //拼接条件
            if (count($doc['conditions']) == 1) {
                $mongoCondtion = array($doc['conditions'][0]['fields'] => $doc['conditions'][0]['values']);
            } else {
                $mongoCondtion = [];
                foreach ($doc['conditions'] as $condition) {
                    if ($condition['fields'] == 'type') {
                        $mongoCondtion[] = array($condition['fields'] => (int) $condition['values']);
                    } else {
                        if ($condition['link'] == 'contains') {
                            $mongoCondtion[] = array($condition['fields'] => new MongoRegex("/{$condition['values']}/"));
                        } else {
                            $mongoCondtion[] = array($condition['fields'] => $condition['values']);
                        }
                    }
                }
                if ($doc['relation'] == 'or') {
                    $mongoCondtion = array('$or' => $mongoCondtion);
                }
            }
            //获取tag2下拉
            $doc['tag2'] = $this->dropdown_model->tag($coturny_code, $mongoCondtion, 'Tag2');
            if ($filter1 && $filter1 != 'ALL') {
                $mongoCondtion['tag.Tag1'] = $filter1;
            }
            if ($filter2 && $filter2 != 'ALL') {
                $mongoCondtion['tag.Tag2'] = $filter2;
            }

            $rows = $productMongo->find($mongoCondtion)->count(); //产品总数
            if ($rows > $offset) {

                //手动排序，查找白名单里的商品信息
                if ($doc['sort'] == 'manual') {
                    $mongoCondtionAllow = array(
                        '_id' => array('$in' => $doc['allow']),
                        'status' => 1
                    );
                    $allowProduct = iterator_to_array($productMongo->find($mongoCondtionAllow, $getFields));
                    $searchProduct = iterator_to_array($productMongo->find($mongoCondtion, $getFields));
                    $same = array_intersect_key($allowProduct, $searchProduct); //交集
                    $diff = array_diff_key($searchProduct, $same); //差集
                    $doc['allow'] = array_slice(array_merge($same, $diff), $offset, $this->prePage);
                } else {
                    $sortArr = explode(',', $doc['sort']);
                    $sort = array($sortArr[0] => (int) $sortArr[1]);
                    $searchProduct = $productMongo->find($mongoCondtion, $getFields)->sort($sort)->limit($this->prePage)->skip($offset);
                    $doc['allow'] = iterator_to_array($searchProduct);
                }
                if ($this->input->is_ajax_request()) {
                    if (!empty($doc['allow'])) {
                        foreach ($doc['allow'] as $kk => $vv) {
                            $img = 'http:' . IMAGE_DOMAIN . '/product/' . $vv['sku'] . '/' . $vv['sku'] . '.jpg';
                            if (!@fopen($img, 'r')) {
                                $img = IMAGE_DOMAIN . $vv['image'];
                            }
                            $doc['allow'][$kk]['img'] = $img;
                            if ($vv['freebies'] == 1) {
                                $doc['allow'][$kk]['price'] = 0;
                            }
                        }
                    }
                } else {
                    foreach ($doc['allow'] as $kk => $vv) {
                        if ($vv['freebies'] == 1) {
                            $doc['allow'][$kk]['price'] = 0;
                        }
                    }
                }
                $offset+=count($doc['allow']);
            } else {
                $doc['allow'] = array();
            }
            $doc['offset'] = $offset;
        }
        unset($doc['model']);
        unset($doc['relation']);
        unset($doc['conditions']);
        unset($doc['status']);
        unset($doc['creator']);
        unset($doc['create_time']);
        unset($doc['disallow']);
        $doc['tag2'] = $doc['tag2']['values'];
        $filter1 = str_replace('$', $this->page['currency'], $filter1);
        $doc['filter1'] = $filter1;
        $doc['filter2'] = $filter2;
        $doc['rows'] = $rows;
        return $doc;
    }

    //加载数据
    function loadPage() {
        $seo_url = $this->input->post('seo_url');
        $sort = $this->input->post('sort') ? $this->input->post('sort') : 'manual';
        $filter1 = $this->input->post('tag1') ? $this->input->post('tag1') : 'ALL';
        $filter2 = $this->input->post('tag2') ? $this->input->post('tag2') : 'ALL';
        $offset = $this->input->post('offset') ? $this->input->post('offset') : 0;
        $filter2 = str_replace(utf8_encode('中'), '/', urldecode($filter2));
        $collection = $this->mongo->{$this->page['country'] . '_collection'};
        $doc = $collection->findOne(array('seo_url' => $seo_url));
        $doc['sort'] = $sort;

        $doc = $this->getPageProduct($this->page['country'], $doc, $filter1, $filter2, $offset);

        $this->load->model('countdown_model');
        foreach ($doc['allow'] as $product_id => $productInfo) {
            unset($doc['allow'][$product_id]['_id']);
            $doc['allow'][$product_id]['title'] = htmlspecialchars_decode($productInfo['title']);
            $countdown_id = $this->countdown_model->getInfoByProductId($this->page['country'], $product_id);
            if ($countdown_id) {
                $countdownInfo = $this->countdown_model->getInfoById($countdown_id);
                $time = time();
                if (is_array($countdownInfo) && $countdownInfo['status'] == 2 && $countdownInfo['start'] < $time) {
                    $doc['allow'][$product_id]['price'] = $this->countdown_model->getPrice($countdown_id, $productInfo['price']);
                    $doc['allow'][$product_id]['endTime'] = $this->countdown_model->getEndTime($countdownInfo['start'], $countdownInfo['cycle']);
                }
            }
            if ($productInfo['freebies'] == 1) {
                $doc['allow'][$product_id]['price'] = 0;
            }
        }
        if (count($doc['allow']) > 0) {
            exit(json_encode(array('success' => TRUE, 'productList' => $doc['allow'], 'offset' => $doc['offset'], 'collection_seo' => $seo_url, 'currency' => $this->page['currency'])));
        } else {
            exit(json_encode(array('success' => FALSE)));
        }
    }

    function _products($collection, $pro_url) {
        $this->load->helper('form');
        $doc = $this->product_model->findSeo($this->country, 1, $pro_url);
        if (!$doc) {
            redirect('home/showError404');
        }
        $this->page['pro'] = $doc;

        //加入产品点击量

        $redisKey = 'T:' . $this->page['datePRC'] . ':' . $this->page ['country'] . ':' . $this->page['pro']['_id'];
        $this->redis->hashSet($redisKey, array('sku' => $this->page['pro']['sku']));
        $this->redis->hashInc($redisKey, 'click', 1);
        $this->redis->timeOut($redisKey, 259200);
        // 组装SEO信息
        $this->page['title'] = !empty($doc['seo']['title']) ? $doc['seo']['title'] : $doc['title'];
        $this->page['description'] = $doc['seo']['description'];
        $this->page['keywords'] = $doc['seo']['keyword'];
        // 运输方式 每个产品页面都有 相同的数据 可优化
        $this->load->model('shipping_model');
        $this->page ['shipping'] = $this->shipping_model->getShipping($this->page ['country']);

        // 获取产品信息
        $this->load->model('product_model');
        $this->load->model('collection_model');
        $this->page['return'] = $this->collection_model->has_collection($this->country, $collection);
        if (!is_array($this->page['return'])) {
            redirect('home/showError404');
        }
        $this->page['seoInfo'] = '<link rel="canonical" href="' . site_url('collections/' . $this->page['return']['seo_url'] . '/products/' . $pro_url) . '" />';
        $headView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'head');
        $this->page['head'] = $this->load->view($headView, $this->page, true);
        $a = A(array($this->page['return']['seo_url'], $this->page['return']['seo_title']), $this->page['original_menu']);
        // 组装breadcrumb
        //$this->page['breadcrumb'] = '<li><a href="/">DrGrab</a></li><li><a href="/collections/' . $this->page['return']['seo_url'] . '">' . $this->page['return']['title'] . '</a></li><li class="active">' . $this->page['pro']['title'] . '</li>';
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
        if ($bundles) {
            // 获取绑定的总价格 不和倒计时关联
            $bundle_price = $this->page ['pro'] ['bundle'] + $bundles ['bundle'];
            // 计算绑定价格省了多少钱 o(≥v≤)o
            $price = $this->page ['pro'] ['price'] + $bundles ['price'];
            if ($price != 0) {
                $bundle_save = ceil((($price - $bundle_price) / $price) * 100);
            } else {
                $bundle_save = 100;
            }

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
            'is_bundleVariants' => $is_bundleVariants
        );

        // 获取倒计时的信息 有的话修改价格 并且启用倒计时
        $this->load->model('countdown_model');
        $countdown_id = $this->countdown_model->getInfoByProductId($this->country, $this->page['pro']['_id']);

        if ($countdown_id) {
            $countdownInfo = $this->countdown_model->getInfoById($countdown_id);
            $time = time();
//            var_dump($countdownInfo);exit;
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




        //获取评论
        $this->load->model('comment_model');
        $this->page ['comments'] = $this->comment_model->getInfoByProductId($this->page['pro']['_id']);
        $this->page['site_url'] = urlencode(site_url("collections/" . $this->uri->segment(2) . "/products/" . $this->uri->segment(4)));
        //读取指定page
        $this->load->model('page_model');
        $this->page['desc_shipping'] = $this->page_model->_findSeo($this->country, 'Product-Tab-Shipping');
        $this->page['desc_payment'] = $this->page_model->_findSeo($this->country, 'Product-Tab-Payment');
        if ($this->page ['pro'] ['diy']) {
//            echo 'DIY 产品 等paddy页面';
//            exit;
            $this->page['token']=time().mt_rand(1, 9999999999);
            $this->page['tokenCheck']=md5($this->page['token'].'305046350@qq.com');
            $productView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'product_diy');
        } else {
            $productView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'product');
        }


        $this->load->view($productView, $this->page);
    }

}
