<?php

class Pc_Controller extends CI_Controller {

    protected $_mongo = 'mongodb://192.168.10.123';
    protected $_category = array(
        'active' => array(
            'dashboard' => '',
            'orders' => '',
            'sku_mapping' => '',
            'fulfil' => '',
            'refund' => '',
            'refundApply' => '',
            'complaints' => '',
            'pages' => '',
            'product' => '',
            'comment' => '',
            'contact' => '',
            'collection' => '',
            'category' => '',
            'countdown' => '',
            'coupons' => '',
            'discount'=>'',
            'shipping' => '',
        	'member' => '',
            'customers' => '',
            'navigation' => '',
            'shipping' => '',
            'pages' => '',
            'slideshow' => '',
            'setting' => ''
        )
    );
    protected $RMBtoAU;
    protected $page = array(
        'template' => '/template/'
    );

    public function __construct() {
        parent::__construct();
        /*
          初始化Mongodb
         */
        $CI = & get_instance();
        $m = new MongoClient($this->_mongo);
        $CI->mongo = $m->selectDB('pdq');
        $this->load->model('country_model');
        $this->_category['countryList'] = $this->country_model->getCountryList('name');

        $this->RMBtoAU = $this->country_model->getRMBtoAU();
        $coltrollers = $this->uri->segment(1);
        $nocol = array('productapi', 'api', 'riskqueue','sites');
        if (!in_array(strtolower($coltrollers), $nocol) && !$this->session->userdata('user_in')) {
            if ($_SERVER['REQUEST_SCHEME']) {
                $_tt = 'login?url=' . urlencode($_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
            } else {
                $_tt = 'login';
            }
            redirect($_tt);
        }
        if (strtolower($coltrollers) != 'productapi' && !$this->session->userdata('my_country')) {
            $this->session->set_userdata('my_country', 'US');
            $this->session->set_userdata('my_currency', '$');
            $this->session->set_userdata('my_countryName', 'United States');
            $this->session->set_userdata('my_currencyPayment', 'USD');
        }
        
        $domain = $this->country_model->getInfoByCode($this->session->userdata('my_country'), array('domain'));
        $this->session->set_userdata('domain',$domain['domain']);

    }

    public function _active($key) {
        $key = strtolower($key);
        $this->_category['active'][$key] = ' class="active"';
    }

}

?>
