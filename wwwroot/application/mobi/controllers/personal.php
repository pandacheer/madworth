<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Personal extends MY_Controller {

    public $myMemberID, $myMemberEmail;
    private $terminal;

    function __construct() {
        parent::__construct();

        $_action = $this->uri->segment(2) ? strtolower($this->uri->segment(2)) : 'index';
        switch ($_action) {
            case "index":
                $title = "Personal Details";
                break;
            case "order":
                $title = "My Orders";
                break;
            case "coupon":
                $title = "My Coupons";
                break;
            case "address":
                $title = "Address ";
                break;
            default:
                $title = "Personal Details";
                break;
        }
        $this->page['title'] = $title;
        $this->terminal = $this->session->userdata('isMobile');
        $this->load->model('template_model');
        $headView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'head');
        $this->page['head'] = $this->load->view($headView, $this->page, true);
        $footView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'foot');
        $this->page['foot'] = $this->load->view($footView, $this->page, true);
    }

    public function _remap($method) {
        /* 登录检测 */
        $key = $this->config->item('encryption_key');
        $mail = $this->session->userdata('member_email');
        $auth = $this->session->userdata('auth');
        if ($auth != md5($key . $mail)) {
            $loginView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'login');
            $this->load->view($loginView, $this->page);
        } else {
            $this->myMemberID = $this->session->userdata('member_id');
            $this->myMemberEmail = $this->session->userdata('member_email');
            $this->$method();
        }
    }

    public function index($resultMessage = '') {
        if (!$this->session->userdata('member_email')) {
            $this->load->helper('form');
            $regView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'reg');
            $this->load->view($regView, $this->page);
        } else {
            $this->load->model('member_model');
            $this->load->model('memberinfo_model');

            $this->page['member'] = $this->member_model->getInfo($this->page['country'], $this->myMemberID, 'member_firstName,member_lastName');
            $this->page['memberInfo'] = $this->memberinfo_model->getInfo($this->page['country'], $this->myMemberID, 'member_id,member_gender,member_birthday,member_phone');
            $this->session->set_userdata($this->page['memberInfo']);
            $this->load->helper('form');
            $this->page['resultMessage'] = $resultMessage;
            $accountPersonalView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'account-personal');
            $this->load->view($accountPersonalView, $this->page);
        }
    }

    public function update() {
        $this->load->helper('language');
        $this->lang->load('sys_personal');
        $resultMessage = '';
        $this->load->library('form_validation');

        $this->form_validation->set_rules('member_phone', 'lang:personal_memberPhone', 'alpha_dash|min_length[6]|max_length[20]');

        if ($this->form_validation->run()) {
            $member['member_firstName'] = $this->input->post('firstname', 1) ? trim($this->input->post('firstname', 1)) : '';
            $member['member_lastName'] = $this->input->post('lastname', 1) ? trim($this->input->post('lastname', 1)) : '';
            $member['member_name'] = trim($member['member_firstName'] . ' ' . $member['member_lastName']);


            $postMemberInfo['member_phone'] = $this->input->post('member_phone', '');
            $postMemberInfo['member_gender'] = $this->input->post('member_gender', 2) ? (int) $this->input->post('member_gender', true) : 3;
            if (!in_array($postMemberInfo['member_gender'], array(1, 2, 3))) {
                exit(json_encode(array('success' => false, 'resultMessage' => lang('personal_genderError'))));
            }
            $postMemberInfo['member_birthday'] = $this->input->post('member_birthday') ? strtotime($this->input->post('member_birthday')) : 0;

            $this->load->model('memberinfo_model');
            if ($this->memberinfo_model->update($this->page['country'], $this->myMemberID, $member, $postMemberInfo)) {
                $this->session->set_userdata('member_name', $member['member_name']);
                $resultMessage = lang('personal_dbSuccess');
                $success = TRUE;
            } else {
                $resultMessage = lang('personal_dbFail');
                $success = FALSE;
            }
        } else {
            $resultMessage = validation_errors();
            $success = FALSE;
        }
        exit(json_encode(array('success' => $success, 'resultMessage' => $resultMessage)));
    }

    public function changepassword() {
        $changepasswordView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'changepassword');
        $this->load->view($changepasswordView, $this->page);
    }

    public function updatepassword() {
        $this->load->model('member_model');
        $data = $this->input->post();
        $member_id = $this->session->userdata('member_id');
        $result = $this->member_model->checkAuth($this->page['country'], $member_id, $data['current']);
        $this->load->helper('language');
        $this->lang->load('sys_personal');
        if ($result) {

            $this->load->library('form_validation');
            $this->form_validation->set_rules('new', 'lang:personal_Password', 'required|alpha_dash|min_length[5]|max_length[20]');
            $this->form_validation->set_rules('confirm', 'lang:personal_verifyPassword', 'required|matches[new]');
            if ($this->form_validation->run()) {
                $uprs = $this->member_model->updatePersonal($this->page['country'], $member_id, 2, $data['new']);
                $uprs ? exit(json_encode(array('success' => TRUE, 'message' => lang('personal_dbSuccess')))) : exit(json_encode(array('success' => FALSE, 'message' => lang('personal_dbFail'))));
            } else {
                exit(json_encode(array('success' => FALSE, 'message' => validation_errors())));
            }
        } else {

            exit(json_encode(array('success' => FALSE, 'message' => lang('personal_password_error'))));
        }
    }

    public function checkAuth($currentPassword) {
        $this->load->model('member_model');
        return $this->member_model->checkAuth($this->page['country'], $this->myMemberID, $currentPassword);
    }

    //调出地址
    public function address() {
        $this->load->model('memberreceive_model');

        //获取收货地址
        $this->page['listAddress'] = $this->memberreceive_model->listAddsByMbId($this->page['country'], $this->myMemberID);
        $this->page['count'] = count($this->page['listAddress']);

        //获取账单地址
        $this->page['billAddress'] = $this->memberreceive_model->getBillAddressById($this->page['country'], $this->myMemberID);
        $this->page['billCount'] = count($this->page['billAddress']);

        $accountAddressView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'account-address');
        $this->load->view($accountAddressView, $this->page);
    }

    //添加地址页面
    public function add_address() {
        $this->load->helper('form');
        $this->load->model('countryzone_model');
        $this->page ['States'] = $this->countryzone_model->getZoneListByCountryCode($this->page['country']);
        $addAddressView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'add-address');
        $this->load->helper('language');
        $this->lang->load('sys_address');
        $this->page ['addCountry'] ['state'] = lang($this->page['country'] . 'state');
        $this->page ['addCountry'] ['city'] = lang($this->page['country'] . 'city');
        $this->page ['addCountry'] ['zipcode'] = lang($this->page['country'] . 'zipcode');
        $this->load->view($addAddressView, $this->page);
    }

    //添加账单地址页面
    public function add_billAddress() {
        $this->load->helper('form');
        $this->load->model('countryzone_model');
        $this->page ['States'] = $this->countryzone_model->getZoneListByCountryCode($this->page['country']);
        $addAddressView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'add-Billaddress');
        $this->load->helper('language');
        $this->lang->load('sys_address');
        $this->page ['addCountry'] ['state'] = lang($this->page['country'] . 'state');
        $this->page ['addCountry'] ['city'] = lang($this->page['country'] . 'city');
        $this->page ['addCountry'] ['zipcode'] = lang($this->page['country'] . 'zipcode');
        $this->load->view($addAddressView, $this->page);
    }

    //购物车添加地址
    public function addressInsert() {
        $this->load->model('memberreceive_model');
        $insertData = array(
            'receive_firstName' => $this->input->post('firstname', TRUE),
            'receive_lastName' => $this->input->post('lastname', TRUE),
            'receive_phone' => $this->input->post('phone', TRUE),
            'receive_add1' => $this->input->post('address1', TRUE),
            'receive_add2' => $this->input->post('apt', TRUE),
            'receive_city' => $this->input->post('suburb', TRUE),
            'receive_zipcode' => $this->input->post('postcode', TRUE),
            'receive_province' => $this->input->post('state', TRUE),
            'receive_country' => $this->input->post('country', TRUE),
        );
        $receive_id = (int) $this->input->post('receive_id');
        if ($receive_id) {
            if ($this->memberreceive_model->update($this->page['country'], $this->myMemberID, $receive_id, $insertData)) {
                redirect("personal/address");
            } else {
                redirect("home/showError/E1201");
            }
        } else {
            $insertData['member_id'] = $this->myMemberID;
            $count = $this->memberreceive_model->count($this->page['country'], $this->myMemberID);
            if ($this->memberreceive_model->insert($this->page['country'], $insertData, $count) == false) {
                $this->load->helper('language');
                $this->lang->load('sys_login');
                redirect("home/showError/E1203");
            }
            $memberreceive_id = $this->db->insert_id();
            $insertData['receive_id'] = $memberreceive_id;
            if ($memberreceive_id) {
                redirect("personal/address");
            } else {
                redirect("home/showError/E1201");
            }
        }
    }

    //购物车添加账单地址
    public function billAddressInsert() {
        $this->load->model('memberreceive_model');
        $insertData = array(
            'receive_firstName' => $this->input->post('firstname', TRUE),
            'receive_lastName' => $this->input->post('lastname', TRUE),
            'receive_add1' => $this->input->post('address1', TRUE),
            'receive_add2' => $this->input->post('apt', TRUE),
            'receive_city' => $this->input->post('suburb', TRUE),
            'receive_zipcode' => $this->input->post('postcode', TRUE),
            'receive_province' => $this->input->post('state', TRUE),
            'receive_country' => $this->input->post('country', TRUE),
        );
        $receive_id = (int) $this->input->post('receive_id');
        if ($receive_id) {
            if ($this->memberreceive_model->updateBillAddress($this->page['country'], $this->myMemberID, $receive_id, $insertData)) {
                redirect("personal/address");
            } else {
                redirect("home/showError/E1201");
            }
        } else {
            $insertData['member_id'] = $this->myMemberID;
            $count = $this->memberreceive_model->billCount($this->page['country'], $this->myMemberID);
            if ($this->memberreceive_model->BillAddressinsert($this->page['country'], $insertData, $count) == false) {
                $this->load->helper('language');
                $this->lang->load('sys_login');
                redirect("home/showError/E1203");
            }
            $memberreceive_id = $this->db->insert_id();
            $insertData['receive_id'] = $memberreceive_id;
            if ($memberreceive_id) {
                redirect("personal/address");
            } else {
                redirect("home/showError/E1201");
            }
        }
    }

    // 设置默认地址
    public function addressDefault() {
        $this->load->model('memberreceive_model');
        $receive_id = (int) $this->input->post('receive_id', true);
        if ($this->memberreceive_model->addressDefault($this->page ['country'], $this->myMemberID, $receive_id)) {
//            sleep(1);
            exit(json_encode(TRUE));
        } else {
            exit(json_encode(False));
        }
    }

    function addressDelete() {
        $this->load->model('memberreceive_model');
        $receive_id = (int) $this->input->post('receive_id');
        if ($this->memberreceive_model->delete($this->page['country'], $this->myMemberID, $receive_id)) {
//            sleep(1);
            exit(json_encode(TRUE));
        } else {
            exit(json_encode(False));
        }
    }

    //获取需要修改的地址信息
    function getAddressInfo() {
        $this->load->helper('form');
        $this->load->model('memberreceive_model');
        $receive_id = $this->uri->segment(3);

        $fields = 'receive_id,member_id,receive_firstName,receive_lastName,receive_company,receive_country,receive_province,receive_city,receive_add1,receive_add2,receive_zipcode,receive_phone';
        $this->page['result'] = $this->memberreceive_model->getInfoById($this->page['country'], $this->myMemberID, $receive_id, $fields);
        if ($this->page['result']) {
            $this->load->model('countryzone_model');
            $this->page ['States'] = $this->countryzone_model->getZoneListByCountryCode($this->page['country']);
            $updateAddressView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'update-address');
            $this->load->helper('language');
            $this->lang->load('sys_address');
            $this->page ['addCountry']['state'] = lang($this->page['country'] . 'state');
            $this->page ['addCountry']['city'] = lang($this->page['country'] . 'city');
            $this->page ['addCountry']['zipcode'] = lang($this->page['country'] . 'zipcode');
            $this->load->view($updateAddressView, $this->page);
        } else {
            redirect("personal/address");
        }
    }

    //获取需要修改的账单地址信息
    function getBillAddressInfo() {
        $this->load->helper('form');
        $this->load->model('memberreceive_model');
        $receive_id = $this->uri->segment(3);

        $fields = 'receive_id,member_id,receive_firstName,receive_lastName,receive_company,receive_country,receive_province,receive_city,receive_add1,receive_add2,receive_zipcode,receive_phone';
        $this->page['result'] = $this->memberreceive_model->getBillInfoById($this->page['country'], $this->myMemberID, $receive_id, $fields);
        if ($this->page['result']) {
            $this->load->model('countryzone_model');
            $this->page ['States'] = $this->countryzone_model->getZoneListByCountryCode($this->page['country']);
            $updateAddressView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'update-Billaddress');
            $this->load->helper('language');
            $this->lang->load('sys_address');
            $this->page ['addCountry']['state'] = lang($this->page['country'] . 'state');
            $this->page ['addCountry']['city'] = lang($this->page['country'] . 'city');
            $this->page ['addCountry']['zipcode'] = lang($this->page['country'] . 'zipcode');
            $this->load->view($updateAddressView, $this->page);
        } else {
            redirect("personal/address");
        }
    }

    //我的优惠券
    public function coupon() {
        $this->load->model('coupons_model');
        $this->page['myCoupons'] = $this->coupons_model->getMyCoupons($this->page['country'], $this->myMemberEmail);
        $beenUsed_coupons = $this->coupons_model->getBeenUsedCoupons($this->page['country'], $this->myMemberEmail);


        foreach ($this->page['myCoupons'] as $key => $myCoupons) {
            foreach ($beenUsed_coupons as $beenUsed) {
                if (strtoupper($key) == strtoupper($beenUsed['coupons_id'])) {
                    unset($this->page['myCoupons'][$key]);
                }
            }
        }
        $accountCouponView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'account-coupon');
        $this->load->view($accountCouponView, $this->page);
    }

//获取用户订单信息
    public function order() {
        $this->load->model('order_model');
        $this->load->model('Product_model');
        $this->page['listOrders'] = $this->order_model->getOrder($this->page['country'], $this->myMemberID);
        if ($this->page['listOrders']) {
            $this->load->model('ordersend_model'); //获取进度
            $this->load->model('orderdetails_model');
            $this->load->model('collection_model');

            foreach ($this->page['listOrders'] as $key => $orders) {
                $orderInfo[$key] = $this->orderdetails_model->listByOrderNumber($this->page['country'], $orders['order_number']);


                //获取订单留言
                $note = $this->order_model->getNoteByNumber($this->page['country'], $orders['order_number'], 'order_guestbook');
                $this->page['listOrders'][$key]['note'] = $note['order_guestbook'];

                /*  $this->page['sendUrl'] = 'javascript:void(0);';
                  $this->page['url'] = $this->ordersend_model->getSendUrl($this->page['country'], $orders['order_number']);
                  if ($this->page['url']) {
                  if ($this->page['url']['track_url']) {
                  $this->page['sendUrl'] = $this->page['url']['track_url'];
                  }
                  } */


                $url = $this->ordersend_model->getSendUrl($this->page['country'], $orders['order_number']);

                if (count($url)) {
                    if ($url['track_url']) {
                        $sends[$key]['sendUrl'] = $url['track_url'];
                        $sends[$key]['track_code'] = $url['track_code'];
                    } else {
                        $sends[$key]['sendUrl'] = 'javascript:void(0);';
                        $sends[$key]['track_code'] = 0;
                    }
                } else {
                    $sends[$key]['sendUrl'] = 'javascript:void(0);';
                    $sends[$key]['track_code'] = 0;
                }


                foreach ($orderInfo[$key] as $k => $detail) {
                    $pro = $this->Product_model->orderPics($this->page['country'], $detail['product_id']);
                    $orderInfo[$key][$k]['collection_url'] = $this->collection_model->getCollectionUrl($this->page['country'], $detail['product_id']);
                    $orderInfo[$key][$k]['freebies'] = $pro['freebies'];
                    $orderInfo[$key][$k]['seo_url'] = $pro['seo_url'];
                    $orderInfo[$key][$k]['image'] = $pro['image'];
                }
            }

            $this->page['sends'] = $sends;
            $this->page['orderInfo'] = $orderInfo;
        }

        //$this->page['listDispose'] = ['0' => 'Order Received', '1' => 'Completed', '2' => 'Dispatched', '3' => 'Dispatched'];
        $this->page['listDispose'] = ['0' => 'Dispatching', '1' => 'Completed', '2' => 'Dispatching', '3' => 'Completed'];
        $accountOrderView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'account-order');
        $this->load->view($accountOrderView, $this->page);
    }

    public function ajaxOrder() {
        if ($this->input->is_ajax_request()) {
            $this->load->model('order_model');
            $this->load->model('Product_model');
            $this->load->model('collection_model');

            $orderInfo['order'] = $this->order_model->getInfoByID($this->page['country'], (int) $this->input->post('order_id'), 'order_number,member_id,create_time');
            if ($orderInfo['order']['member_id'] == $this->myMemberID) {
                $this->load->model('ordersend_model'); //获取进度
                $send = $this->ordersend_model->getProgressById($this->page['country'], $orderInfo['order']['order_number'], 1, 'send_status,create_time');
                $sendCount = count($send);
                switch ($sendCount) {
                    case 0:
                        $orderInfo['send']['first'] = '';
                        $orderInfo['send']['last'] = '';
                        break;
                    case 1:
                        if ($send[0]['send_status'] == 1) {
                            $orderInfo['send']['first'] = date('d-m-Y', $send[0]['create_time']);
                            $orderInfo['send']['last'] = date('d-m-Y', $send[0]['create_time']);
                        } else {
                            $orderInfo['send']['first'] = date('d-m-Y', $send[0]['create_time']);
                            $orderInfo['send']['last'] = '';
                        }
                        break;
                    default:
                        if ($send[$sendCount - 1]['send_status'] == 1) {
                            $orderInfo['send']['first'] = date('d-m-Y', $send[0]['create_time']);
                            $orderInfo['send']['last'] = date('d-m-Y', $send[$sendCount - 1]['create_time']);
                        } else {
                            $orderInfo['send']['first'] = date('d-m-Y', $send[0]['create_time']);
                            $orderInfo['send']['last'] = '';
                        }
                        break;
                }

                $this->load->model('ordership_model'); //订单收货地址
                $orderInfo['ship'] = $this->ordership_model->getInfoById($this->page['country'], $orderInfo['order']['order_number']);
                $this->load->model('orderbill_model'); //订单帐单地址
                $orderInfo['bill'] = $this->orderbill_model->getInfoById($this->page['country'], $orderInfo['order']['order_number']);
                $this->load->model('orderdetails_model'); //订单详情
                $orderInfo['details'] = $this->orderdetails_model->listByOrderNumber($this->page['country'], $orderInfo['order']['order_number']);

                $url = $this->ordersend_model->getSendUrl($this->page['country'], $orderInfo['order']['order_number']);
                $sendUrl = 'javascript:void(0);';
                if ($url) {
                    if ($url['track_url']) {
                        $sendUrl = $url['track_url'];
                    }
                }


                if ($orderInfo['ship']['receive_add2']) {
                    $ship_address = $orderInfo['ship']['receive_add2'] . ' / ' . $orderInfo['ship']['receive_add1'];
                } else {
                    $ship_address = $orderInfo['ship']['receive_add1'];
                }



                $row1 = '
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="row bs-wizard" style="border-bottom:0;">
                                    <div class="col-xs-4 bs-wizard-step complete">
                                        <div class="text-center bs-wizard-stepnum">Order Received</div>
                                        <div class="progress"><div class="progress-bar"></div></div>
                                        <a href="#" class="bs-wizard-dot"></a>
                                        <div class="bs-wizard-info text-center">' . date('d-m-Y', $orderInfo['order']['create_time']) . '</div>
                                    </div>

                                    <div class="col-xs-4 bs-wizard-step ' . ($orderInfo['send']['first'] ? 'active' : 'disabled') . '">
                                        <div class="text-center bs-wizard-stepnum">Dispatched</div>
                                        <div class="progress">
                                            <div class="progress-bar"></div>
                                            <div class="progress-bar"></div>
                                        </div>
                                        <a href="#" class="bs-wizard-dot"></a>
                                        <div class="bs-wizard-info text-center">' . $orderInfo['send']['first'] . ' </div>
                                    </div>

                                    <div class="col-xs-4 bs-wizard-step ' . ($orderInfo['send']['last'] ? 'active' : 'disabled') . '">
                                        <div class="text-center bs-wizard-stepnum">Completed</div>
                                        <div class="progress"><div class="progress-bar"></div></div>
                                        <a href="#" class="bs-wizard-dot"></a>
                                        <div class="bs-wizard-info text-center">' . $orderInfo['send']['last'] . ' </div>
                                    </div>
                                </div>        
                            </div>
                        </div>';

                /*  <button type="button" class="btn btn-default btn-md">
                  <span class="glyphicon glyphicon-transfer" aria-hidden="true"></span> Request Refund
                  </button> */
                $row2 = '             <div class="row">
                                                            <div class="col-xs-6">
                                                                <div class="dg-main-account-content-address">
                                                                    <span class="dg-main-account-content-address-title">Shipping Address:</span><br/>' .
                        $orderInfo['ship']['receive_firstName'] . $orderInfo['ship']['receive_lastName'] . '<br/>' .
                        $ship_address . '<br/>' .
                        $orderInfo['ship']['receive_city'] . ' , ' . $orderInfo['ship']['receive_zipcode'] . '<br/>' .
                        $orderInfo['ship']['receive_province'] . '<br/>' .
                        '</div>
                                                            </div>
                                                            <div class="col-xs-6 dg-main-account-content-buttons">
                        		
                                                        <a href="' . $sendUrl . '" target="_blank" onclick="trackthisorder($(this).attr(\'href\'));" id="order_' . $orderInfo['order']['order_number'] . '" type="button" class="btn btn-default btn-md">
                                                            <span class="glyphicon glyphicon-plane" aria-hidden="true"></span> Track This Order
                                                        </a>
                                                    </div>

                                                </div>';
                $row3 = '          <div class="dg-main-account-content-items">
                                                            <span class="dg-main-account-content-items-title">Order Items:</span>
                                                            <table class="table table-hover">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Image</th>
                                                                        <th>Product title</th>
                                                                        <th>Quantity</th>
                		                                                <th>Price</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>';
                foreach ($orderInfo['details'] as $orderDetails) {


                    $pro = $this->Product_model->orderPics($this->page['country'], $orderDetails['product_id']);
                    $collection_url = $this->collection_model->getCollectionUrl($this->page['country'], $orderDetails['product_id']);
                    $seo_url = $pro['seo_url'];
                    $row3 = $row3 .
                            '<tr>' .
                            '<td><img src="' . IMAGE_DOMAIN . $pro['image'] . '" alt="" width="50"></td>' .
                            '<td><a href="/collections/' . $collection_url . 'products/' . $seo_url . '">' . htmlspecialchars_decode($orderDetails['product_name']) . '</a><span>' . $orderDetails['product_attr'] . '</span></td>' .
                            '<td>' . $orderDetails['product_quantity'] . '</td>' .
                            '<td>' . $this->page['currency'] . ($orderDetails['payment_amount'] / 100) . '</td>' .
                            '</tr>  ';
                }
// <th><!-- Operating -->Price</th>
//'<td><input type="text" data-size="xxs" data-toggle="modal" data-target="#account-order-comment" data-step="1" data-order_number="'.$orderInfo['order']['order_number'].'" data-product_sku="'. $orderDetails['product_sku'] .'" data-product_id="' . $orderDetails['product_id'] . '" data-product_name="' . $orderDetails['product_name'] . '" data-whatever="@mdo" class="rating" value="' . $orderDetails['comments_star'] . '"></td>' . 
                $row3 = $row3 . '

                                                                </tbody>
                                                            </table>
                                                        </div>';

//                sleep(2);
                exit(json_encode(array('success' => TRUE, 'html' => $row1 . $row2 . $row3)));
            } else {
//                sleep(2);
                exit(json_encode(array('success' => FALSE, 'error' => '订单不存在！')));
            }
        } else {
//            sleep(2);
            exit(json_encode(array('success' => FALSE, 'error' => '数据错误！')));
        }
    }

}
