<?php

/**
 * @文件： MY_controller.php
 * @时间： 2015-6-8 22:12:11 
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明： 控制器
 */
class MY_Controller extends CI_Controller {

    public static $domain;
    public static $template_arr;
    public $page = array(
        'navigation' => '',
    );
    private $_config = 'mongodb://192.168.10.123';
    protected $fb = null;
    private $fbinfo = array();

    function __construct() {
        parent::__construct();
        $this->fbinfo = array(
//            'AU'=>array('481705555371178','1bbca9757579ed6f442fbb4f7a56e8db'),
//            'GB'=>array('481705555371178','1bbca9757579ed6f442fbb4f7a56e8db')
        );



        $this->load->library('user_agent');

        //对url进行保存
        if (!$this->session->userdata('landing_page')) {
            $this->session->set_userdata('landing_page', $this->get_page_url());
        }


        if (!$this->session->userdata('refer_site')) {
            $data = @$_SERVER['HTTP_REFERER'];
            if ($data) {
                $this->session->set_userdata('refer_site', $data);
            } else {
                $data = '/';
                $this->session->set_userdata('refer_site', $data);
            }
        }


        // 获取域名
        self::$domain = $this->input->server('HTTP_HOST');
        $otherDomain = array(
            'drgrab.com' => 'www.drgrab.com',
            'drgrab.ca' => 'www.drgrab.ca',
            'drgrab.com.au' => 'www.drgrab.com.au',
            'drgrab.co.nz' => 'www.drgrab.co.nz',
            'drgrab.co.uk' => 'www.drgrab.co.uk',
            'drgrab.sg' => 'www.drgrab.sg'
        );
        if (array_key_exists(self::$domain, $otherDomain)) {
            $xredir = "http://" . $otherDomain[self::$domain] . $_SERVER["REQUEST_URI"];
            header("Location: " . $xredir);
            self::$domain = $otherDomain[self::$domain];
        }
        $this->page['domain'] = 'http://' . self::$domain;


        // 国家与语言
        $this->load->model('country_model');


        self::$template_arr = $this->country_model->getInfoByDomain(self::$domain, array('flag_sort', 'country_code', 'language_code', 'currency_symbol', 'currency_payment', 'au_rate', 'service_mail', 'timezone', 'google', 'facebook', 'facebook_id'));
        if (!self::$template_arr) {
            exit("no domain");
        }
        $this->page['country'] = self::$template_arr['country_code'];
        $this->page['language'] = self::$template_arr['language_code'];
        $this->page['currency'] = self::$template_arr['currency_symbol']; //货币符号
        $this->page['currency_payment'] = self::$template_arr['currency_payment']; //支付的货币类型
        $this->page['au_rate'] = self::$template_arr['au_rate']; //澳元对外币
        $this->page['service_mail'] = self::$template_arr['service_mail'];
        $this->page['flag_sort'] = explode(',', self::$template_arr['flag_sort']);
        $this->page['google'] = self::$template_arr['google'];
        $this->page['facebook_id'] = self::$template_arr['facebook_id'];
        $this->page['facebook'] = self::$template_arr['facebook'];
        $this->page['cdn'] = STATIC_DOMAIN . '/template_' . $_SESSION['isMobile'] . '/' . self::$template_arr['language_code'] . '/';


        date_default_timezone_set(self::$template_arr['timezone']); //设置国家时区
        $this->config->set_item('language', $this->page['language']); //设置语言包
        $this->load->switch_theme($this->page['language']); //获取本站模板
        $this->load->model('template_model');
        $this->template_model->init($_SESSION['isMobile'], $this->page['country']); //初始化模板
        $this->page['countryList'] = $this->country_model->getCountryList(array('language_code', 'name', 'domain')); //获取各国分站点
        $dateTimePRC = new DateTime('@' . (time() + 28800), new DateTimeZone("PRC"));
        $this->page['datePRC'] = $dateTimePRC->format("Ymd");
        if ($this->uri->segment(1) !== 'mail') {
            if (!$this->session->userdata('webSite_click')) {
                $this->session->set_userdata('webSite_click', $this->page['datePRC']);
                $this->load->model('website_model');
                $this->website_model->clickSite($this->page['country']);
            }

            if ($this->input->cookie('webSite_click') !== md5($this->page['datePRC'] . 'click')) {//统计当天进站人数
                $this->input->set_cookie('webSite_click', md5($this->page['datePRC'] . 'click'), 2592000);
                $this->load->model('website_model');
                $this->website_model->UVSite($this->page['country'], md5($_SERVER['HTTP_USER_AGENT'] . $_SERVER['HTTP_ACCEPT_LANGUAGE'] . $_SERVER['REMOTE_ADDR']), $_SERVER['REMOTE_ADDR']);
            }
        }


        $CI = & get_instance();
        $m = new MongoClient($this->_config);
        $CI->mongo = $m->selectDB('pdq');
        $tmp = $CI->mongo->Navigation->findOne(array('_id' => $this->page['country']));

        unset($tmp['_id']);
        $this->page['original_menu'] = $tmp;

        $this->createMenu($tmp, $this->page['domain']);

        //获取最近购买商品



        $tmpC = ['US', 'GB', 'AU', 'CA', 'IE', 'NZ', 'SG'];
        $i = rand(1, 1316 - 20);
        $productInfo = iterator_to_array($CI->mongo->{$this->page['country'] . '_product'}->find(array('status' => 1), array('title' => true, 'image' => true, 'seo_url' => true))->limit(20)->skip($i));
        foreach ($productInfo as $productOne) {
            $productOne['product_id'] = (string) $productOne['_id'];
            unset($productOne['_id']);
            $productOne['country_code'] = $tmpC[rand(0, 6)];
            $productOne['buy_time'] = mt_rand(1, 2) . ' minutes'; // time() - $index * 60;
            $this->page['buyList'][] = $productOne;
        }
        $buyListInfo = $this->redis->listGet('Buy_List', 0, 19);
        $this->page['buyList'] = array();
        foreach ($buyListInfo as $productJson) {
            $productInfo = json_decode($productJson, true);
            $productMongo = $CI->mongo->{$this->page['country'] . '_product'}->findOne(array('_id' => new mongoId($productInfo['product_id'])), array('_id' => false, 'title' => true, 'image' => true, 'seo_url' => true));
            if ($productMongo) {
                $productMongo['country_code'] = $productInfo['country_code'];
                $productMongo['buy_time'] = mt_rand(1, 2) . ' minutes'; // $productInfo['buy_time'];
                $this->page['buyList'][] = $productMongo;
            }
        }
        $this->page['fb_login'] = '';
        if (!$this->session->userdata('member_email') && isset($this->fbinfo[$this->page['country']])) {
            $cols = $this->uri->segment(1);
            require_once dirname(__DIR__) . '/Facebook/autoload.php';
            $this->fb = new Facebook\Facebook([
                'app_id' => $this->fbinfo[$this->page['country']][0],
                'app_secret' => $this->fbinfo[$this->page['country']][1],
                'default_graph_version' => 'v2.5',
            ]);
            $helper = $this->fb->getRedirectLoginHelper();
            $permissions = ['email']; // Optional permissions
            if ((isset($_SESSION['fb_login']) && !empty($_SESSION['fb_login'])) && (strtolower($cols) == 'reg')) {
                $loginUrl = $_SESSION['fb_login'];
            } else {
                $loginUrl = $_SESSION['fb_login'] = $helper->getLoginUrl($this->page['domain'] . '/reg/fbcallback/?redirecturl=' . urlencode($this->get_page_url()), $permissions);
            }
            $this->page['fb_login'] = htmlspecialchars($loginUrl);
        } else {
            if (isset($_SESSION['fb_id']))
                unset($_SESSION['fb_id']);
            if (isset($_SESSION['fb_login']))
                unset($_SESSION['fb_login']);
        }

//        echo '<pre>';
//        print_r($this->page['buyList']);
//        exit;
//        exit;
    }

    //获取完整的url
    function get_page_url() {
        $url = (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443') ? 'https://' : 'http://';
        $url .= $_SERVER['HTTP_HOST'];
        $url .= isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : urlencode($_SERVER['PHP_SELF']) . '?' . urlencode($_SERVER['QUERY_STRING']);
        return $url;
    }

    function createMenu($data, $domain) {

        foreach ($data as $value) {
            preg_match("/{title:(.*?),/", $value['msg'], $vo);
            preg_match("/url:(.*?)}/", $value['msg'], $v1);
            // if(isset($value['children'])) {
            //     $this->page['navigation'] .= ' <li class="dropdown"><a href="'.$vo['1'].'" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" role="button" aria-expanded="false">' . $vo['1'] . '<span class="caret"></span></a>';
            // }
            if (isset($value['children'])) {
                $this->page['navigation'] .= ' <li class="dropdown"><a href="' . $domain . $v1['1'] . '" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" role="button" aria-expanded="false">' . $vo['1'] . '<span class="caret"></span></a>';
                preg_match("/{title:(.*?),/", $value['msg'], $vo1);
                $this->page['navigation'] .= '<ul class="dropdown-menu" role="menu">';
                $this->createMenu($value['children'], $domain);
                $this->page['navigation'] .= '</ul>';
            } else {
                $this->page['navigation'].='<li><a href="' . $domain . ($v1['1'] ? $v1['1'] : 'javascript:void(0);') . '">' . $vo['1'] . '</a>';
            }
            $this->page['navigation'] .= '</li>';
        }
        $this->page['myCarts'] = $this->_getCart($this->page['country'], $this->session->userdata('member_email'));
        return $this->page['navigation'];
    }

    private function _getCart($country_code, $member_email) {
        $this->load->model('cart_model');
        $this->load->model('collection_model');
        if ($member_email) {

            //获取购物车表的产品信息
            $pro = $this->cart_model->getCart($country_code, $member_email);


            //获取产品信息并且组装
            $this->load->model('product_model');
            $products = $this->product_model->cartPro($country_code, $pro['info']);
        } else {

            $arr = $this->input->cookie('cart');
            $products = unserialize($arr);

            //获取产品信息并且组装
            $this->load->model('product_model');
            $products = $this->product_model->cartPro($country_code, $products);
        }


        if ($products) {
            foreach ($products as $key => $value) {
                $products[$key]['collection_url'] = $this->collection_model->getCollectionUrl($country_code, $value['product_id']);
            }
        }

        return $products;
    }

}

?>
