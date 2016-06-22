<?php

class search extends MY_Controller {

    private $terminal;

    function __construct() {
        parent::__construct();
        $this->terminal = $this->session->userdata('isMobile');
        $this->load->model('template_model');
        $footView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'foot');
        $this->page['foot'] = $this->load->view($footView, $this->page, true);

        $this->country = $this->page ['country'];
    }

    function index($title, $sort = 'manual', $filter1 = 'ALL', $filter2 = 'ALL') {
        $prePage = 12;

        $title = htmlspecialchars(urldecode($this->uri->segment(2)));

        if (empty($title)) {
            redirect('/');
        }


        $this->page['search_word'] = $title;

        $filter1 = urldecode($filter1);
        $filter2 = urldecode($filter2);


        $this->page['doc']['sort'] = $sort;


        $mongoCondtion = array(
            '$or' => array(array('title' => new MongoRegex("/$title/i")), array("sku" => new MongoRegex("/$title/i"))),
            'status' => 1
        );

        //获取tag2下拉
        $this->load->model('dropdown_model');
        $doc['tag2'] = $this->dropdown_model->tag($this->country, $mongoCondtion, 'Tag2');
        $this->page['doc']['tag2'] = $doc['tag2']['values'];


        $productMongo = $this->mongo->{$this->country . '_product'};
        if ($filter1 && $filter1 != 'ALL') {
            $mongoCondtion['tag.Tag1'] = $filter1;
        }
        if ($filter2 && $filter2 != 'ALL') {
            $mongoCondtion['tag.Tag2'] = $filter2;
        }


        if ($sort == 'manual') {
            $productInfos = $productMongo->find($mongoCondtion)->limit($prePage);
            $this->page['doc']['allow'] = iterator_to_array($productInfos);
        } else {
            if (strpos($sort, ",") > 0) {
                $sortArr = explode(',', $sort);
                $sort = array($sortArr[0] => (int) $sortArr[1]);
                $productInfos = $productMongo->find($mongoCondtion)->sort($sort)->limit($prePage);
                $this->page['doc']['allow'] = iterator_to_array($productInfos);
            } else {
                $productInfos = $productMongo->find($mongoCondtion)->limit($prePage);
                $this->page['doc']['allow'] = iterator_to_array($productInfos);
            }
        }


        //倒计时
        $this->load->model('countdown_model');
        $this->load->model('collection_model');
        $itemTmp = '';
        foreach ($this->page['doc']['allow'] as $product_id => $productInfo) {
            unset($this->page['doc']['allow'][$product_id]['_id']);
            $countdown_id = $this->countdown_model->getInfoByProductId($this->page['country'], $product_id);
            $this->page['doc']['allow'][$product_id]['collection_url'] = $this->collection_model->getCollectionUrl($this->country, $product_id);
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
            if ($this->page['country'] == 'AU') {
                $itemTmp.='"' . $productInfo['sku'] . '",';
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
                                             { event: "setSiteType", type: "m" },
                                             { event: "viewList", item:[ ' . $itemTmp . ' ]}
                                             );
                                             </script>';
        }


        //条件
        $this->page['doc']['filter1'] = $filter1;
        $this->page['doc']['filter2'] = $filter2;



        $this->page['title'] = 'Search Results for ' . $title;
        $headView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'head');
        $this->page['head'] = $this->load->view($headView, $this->page, true);
        $searchView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'search');
        $this->load->view($searchView, $this->page);
    }

    //加载数据
    function loadPage() {
        $prePage = 12;
        $title = $this->input->post('seo_url');
        $sort = $this->input->post('sort') ? $this->input->post('sort') : 'manual';
        $filter1 = $this->input->post('tag1') ? $this->input->post('tag1') : 'ALL';
        $filter2 = $this->input->post('tag2') ? $this->input->post('tag2') : 'ALL';
        $offset = $this->input->post('offset') ? $this->input->post('offset') : 0;



        $productMongo = $this->mongo->{$this->country . '_product'};
        $mongoCondtion = array(
            'title' => new MongoRegex("/$title/i"),
            'status' => 1
        );

        if ($filter1 && $filter1 != 'ALL') {
            $mongoCondtion['tag.Tag1'] = $filter1;
        }
        if ($filter2 && $filter2 != 'ALL') {
            $mongoCondtion['tag.Tag2'] = $filter2;
        }


        if ($sort == 'manual') {
            $productInfos = $productMongo->find($mongoCondtion)->limit($prePage)->skip($offset);
            $this->page['doc']['allow'] = iterator_to_array($productInfos);
        } else {
            $sortArr = explode(',', $sort);
            $sort = array($sortArr[0] => (int) $sortArr[1]);
            $productInfos = $productMongo->find($mongoCondtion)->sort($sort)->limit($prePage)->skip($offset);
            $this->page['doc']['allow'] = iterator_to_array($productInfos);
        }


        $this->load->model('countdown_model');
        $this->load->model('collection_model');
        foreach ($this->page['doc']['allow'] as $product_id => $productInfo) {
            unset($this->page['doc']['allow'][$product_id]['_id']);
            $countdown_id = $this->countdown_model->getInfoByProductId($this->page['country'], $product_id);
            $this->page['doc']['allow'][$product_id]['collection_url'] = $this->collection_model->getCollectionUrl($this->country, $product_id);
            if ($countdown_id) {
                $countdownInfo = $this->countdown_model->getInfoById($countdown_id);
                $time = time();
                if (is_array($countdownInfo) && $countdownInfo['status'] == 2 && $countdownInfo['start'] < $time) {
                    $this->page['doc']['allow'][$product_id]['price'] = $this->countdown_model->getPrice($countdown_id, $productInfo['price']);
                    $this->page['doc']['allow'][$product_id]['endTime'] = $this->countdown_model->getEndTime($countdownInfo['start'], $countdownInfo['cycle']);
                }
            }
            if ($productInfo['freebies'] == 1) {
                $this->page['doc']['allow'][$product_id]['price'] = 0;
            }
        }


        $offset+=$prePage = 12;
        if (count($this->page['doc']['allow']) > 0) {
            exit(json_encode(array('success' => TRUE, 'productList' => $this->page['doc']['allow'], 'offset' => $offset, 'currency' => $this->page['currency'])));
        } else {
            exit(json_encode(array('success' => FALSE)));
        }
    }

}
