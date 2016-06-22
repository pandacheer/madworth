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
    public $page = array();
    private $_config = 'mongodb://192.168.10.123';
    protected $fb = null;
    private $fbinfo = array();

    function __construct() {
        parent::__construct();
        $this->fbinfo = array(
//            'AU'=>array('481705555371178','1bbca9757579ed6f442fbb4f7a56e8db')
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
        $this->page['domain'] = 'http://' . self::$domain;


        // 国家与语言
        $this->load->model('country_model');
        self::$template_arr = $this->country_model->getInfoByDomain(self::$domain, array('flag_sort', 'country_code', 'language_code', 'currency_symbol', 'currency_payment', 'au_rate', 'service_mail', 'timezone', 'google', 'facebook', 'facebook_id'));
        if (!self::$template_arr) {
            exit("no domain");
        }
        $this->page['country'] = self::$template_arr['country_code'];
        $this->page['language'] = self::$template_arr['language_code'];
        $this->page['currency'] = self::$template_arr['currency_symbol'];
        $this->page['currency_payment'] = self::$template_arr['currency_payment'];
        $this->page['au_rate'] = self::$template_arr['au_rate']; //澳元对外币
        $this->page['service_mail'] = self::$template_arr['service_mail'];
        $this->page['flag_sort'] = explode(',', self::$template_arr['flag_sort']);
        $this->page['google'] = self::$template_arr['google'];
        $this->page['facebook'] = self::$template_arr['facebook'];
        $this->page['facebook_id'] = self::$template_arr['facebook_id'];
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
                $loginUrl = $_SESSION['fb_login'] = $helper->getLoginUrl($this->page['domain'] . '/reg/fbcallback/?redirecturl='.urlencode($this->get_page_url()), $permissions);
            }
            $this->page['fb_login'] = htmlspecialchars($loginUrl);
        } else {
            if (isset($_SESSION['fb_id']))
                unset($_SESSION['fb_id']);
            if (isset($_SESSION['fb_login']))
                unset($_SESSION['fb_login']);
        }
    }

    //获取完整的url
    function get_page_url() {
        $url = (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443') ? 'https://' : 'http://';
        $url .= $_SERVER['HTTP_HOST'];
        $url .= isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : urlencode($_SERVER['PHP_SELF']) . '?' . urlencode($_SERVER['QUERY_STRING']);
        return $url;
    }

    function createMenu($data, $domain) {
        $this->page['navigation'] = '';
        foreach ($data as $value) {
            preg_match("/{title:(.*?),/", $value['msg'], $vo);
            preg_match("/url:(.*?)}/", $value['msg'], $vl);
            $children = '';
            if (isset($value['children']) && count($value['children']) > 0) {
                foreach ($value['children'] as $vp) {
                    preg_match("/{title:(.*?),/", $vp['msg'], $title);
                    preg_match("/url:(.*?)}/", $vp['msg'], $url);
                    $children .= '<p><a href="' . $url[1] . '">' . $title[1] . '</a></p>';
                }
                $this->page['navigation'] .= '
                		<div class="dg-main-navslider-list">
                		    <div id="slider" class="dg-main-navslider-list-title">' . $vo[1] . '<span class="icon-arrow-d" style="float: right"></span></div>
                           
                           <div class="dg-main-navslider-content" style="display: none"><p><a href="' . $domain . $vl[1] . '" >All  ' . $vo[1] . ' Products</a></p>' . $children . '</div>
                        </div>';
            } else {
                $this->page['navigation'] .= '
                		 <div class="dg-main-navslider-list">
                			 <div id="slider" class="dg-main-navslider-list-title"><a href="' . $domain . $vl[1] . '" style="font-weight:600!important;color:#01C260!important">' . $vo[1] . '</a><span class="icon-arrow-d" style="float: right"></span></div>
                		 </div>';
            }
        }

        $this->page['myCarts'] = $this->_getCartCount($this->page['country'], $this->session->userdata('member_email'));
    }

    function _getCartCount($country_code, $member_email) {
        $count = 0;
        if ($member_email) {
            $this->load->model('cart_model');
            $products = $this->cart_model->getCount($country_code, $member_email);
            if ($products) {
                foreach ($products ['info'] as $info) {
                    $count += $info ['product_qty'];
                }
            }
        } else {
            $this->load->helper('cookie');
            $arr = $this->input->cookie('cart');

            if ($arr) {
                $products = unserialize($arr);
                foreach ($products as $info) {
                    $count += $info ['product_qty'];
                }
            }
        }

        return $count;
    }

}

?>
