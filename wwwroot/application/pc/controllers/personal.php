<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Personal extends MY_Controller {

    public $myMemberID, $myMemberEmail, $terminal;

    function __construct() {
        parent::__construct();
        $this->terminal = $this->session->userdata('isMobile');
        $this->load->model('template_model');
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
        $headView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'head');
        $this->page['head'] = $this->load->view($headView, $this->page, true);
        $footLogosView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'foot_logos');
        $this->page['footLogosView'] = $this->load->view($footLogosView, $this->page, true);
        $footView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'foot');
        $this->page['foot'] = $this->load->view($footView, $this->page, true);
        $shoppingcartView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'shoppingcart');
        $this->page['shoppingcart'] = $this->load->view($shoppingcartView, $this->page, true);
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
            $this->page['myData'] = md5($this->session->userdata('member_name') . $this->page['memberInfo']['member_birthday'] . $this->page['memberInfo']['member_phone'] . $this->page['memberInfo']['member_gender']);
            $this->load->helper('form');
            $this->page['resultMessage'] = $resultMessage;
            $accountPersonalView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'account-personal');
            $this->load->view($accountPersonalView, $this->page);
        }
    }

    public function update() {
        $member['member_firstName'] = $this->input->post('firstname', 1) ? trim($this->input->post('firstname', 1)) : '';
        $member['member_lastName'] = $this->input->post('lastname', 1) ? trim($this->input->post('lastname', 1)) : '';
        $member['member_name'] = trim($member['member_firstName'] . ' ' . $member['member_lastName']);

        $postMemberInfo['member_phone'] = $this->input->post('member_phone', '');
        $postMemberInfo['member_gender'] = $this->input->post('member_gender', TRUE) ? $this->input->post('member_gender', TRUE) : 3;
        $this->load->helper('language');
        $this->lang->load('sys_personal');
        if (!in_array($postMemberInfo['member_gender'], array(1, 2, 3))) {
            exit(json_encode(array('success' => false, 'resultMessage' => lang('personal_genderError'))));
        }
        $postMemberInfo['member_birthday'] = $this->input->post('member_birthday') ? strtotime($this->input->post('member_birthday')) : 0;

        $checkInfo = md5($member['member_name'] . $postMemberInfo['member_birthday'] . $postMemberInfo['member_phone'] . $postMemberInfo['member_gender']);


        $change = $checkInfo == $this->input->post('mydata') ? 0 : 1;

        $postMember['currentPassword'] = $this->input->post('currentPassword', '');
        $postMember['newPassword'] = $this->input->post('newPassword', '');
        $postMember['verifyPassword'] = $this->input->post('verifyPassword', '');

        $resultMessage = '';
        $this->load->library('form_validation');

        if (!($postMember['currentPassword'] == '' && $postMember['newPassword'] == '' && $postMember['verifyPassword'] == '')) {
            $change = $change ? 3 : 2;

            if ($change == 3) {
                $this->form_validation->set_rules('member_phone', 'lang:personal_memberPhone', 'alpha_dash|min_length[6]|max_length[20]');
            }
            $this->form_validation->set_rules('currentPassword', 'lang:personal_currentPassword', 'required|alpha_dash|min_length[5]|max_length[20]|callback_checkAuth');
            $this->form_validation->set_rules('newPassword', 'lang:personal_Password', 'required|alpha_dash|min_length[5]|max_length[20]');
            $this->form_validation->set_rules('verifyPassword', 'lang:personal_verifyPassword', 'required|matches[newPassword]');
            if ($this->form_validation->run()) {
                $this->load->model('member_model');
                if ($this->member_model->updatePersonal($this->page['country'], $this->myMemberID, $change, $postMember['newPassword'], $postMemberInfo, $member)) {
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
        } else {
            if ($change == 0) {
                $resultMessage = lang('personal_data_has_not_changed');
                $success = FALSE;
            } else {
                $this->form_validation->set_rules('member_phone', 'lang:personal_memberPhone', 'alpha_dash|min_length[6]|max_length[20]');

                if ($this->form_validation->run()) {
                    if ($postMemberInfo['member_phone'] == $this->session->userdata('member_phone') && $postMemberInfo['member_gender'] == $this->session->userdata('member_gender') && $postMemberInfo['member_birthday'] == $this->session->userdata('member_birthday')) {
                        $resultMessage = lang('personal_dbSuccess');
                        $success = FALSE;
                    }

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
            }
        }
//        sleep(2);
        exit(json_encode(array('success' => $success, 'resultMessage' => $resultMessage)));
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




        $this->load->model('countryzone_model');
        $this->page ['States'] = $this->countryzone_model->getZoneListByCountryCode($this->page['country']);
        $accountAddressView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'account-address');
        $this->load->helper('language');
        $this->lang->load('sys_address');
        $this->page ['addCountry'] ['state'] = lang($this->page['country'] . 'state');
        $this->page ['addCountry'] ['city'] = lang($this->page['country'] . 'city');
        $this->page ['addCountry'] ['zipcode'] = lang($this->page['country'] . 'zipcode');
        $this->load->view($accountAddressView, $this->page);
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
                exit(json_encode($insertData));
            } else {
                exit(json_encode(False));
            }
        } else {
            $insertData['member_id'] = $this->myMemberID;
            $count = $this->memberreceive_model->count($this->page['country'], $this->myMemberID);
            if ($this->memberreceive_model->insert($this->page['country'], $insertData, $count) == false) {
                $this->load->helper('language');
                $this->lang->load('sys_login');
                exit(json_encode(array('error' => lang('personal_Addresslimit'))));
            }
            $memberreceive_id = $this->db->insert_id();
            $insertData['receive_id'] = $memberreceive_id;
            if ($memberreceive_id) {
                exit(json_encode($insertData));
            } else {
                exit(json_encode(False));
            }
        }
    }

    //购物车添加账单地址
    public function billAddressInsert() {
        $this->load->model('memberreceive_model');
        $insertData = array(
            'receive_firstName' => $this->input->post('bill_firstname', TRUE),
            'receive_lastName' => $this->input->post('bill_lastname', TRUE),
            'receive_add1' => $this->input->post('bill_address', TRUE),
            'receive_add2' => $this->input->post('bill_apt', TRUE),
            'receive_city' => $this->input->post('bill_suburb', TRUE),
            'receive_zipcode' => $this->input->post('bill_postcode', TRUE),
            'receive_province' => $this->input->post('bill_state', TRUE),
            'receive_country' => $this->input->post('bill_country', TRUE),
        );

        $receive_id = (int) $this->input->post('billreceive_id');
        if ($receive_id) {
            if ($this->memberreceive_model->updateBillAddress($this->page['country'], $this->myMemberID, $receive_id, $insertData)) {
                exit(json_encode($insertData));
            } else {
                exit(json_encode(False));
            }
        } else {
            $insertData['member_id'] = $this->myMemberID;
            $count = $this->memberreceive_model->billCount($this->page['country'], $this->myMemberID);
            if ($this->memberreceive_model->BillAddressinsert($this->page['country'], $insertData, $count) == false) {
                $this->load->helper('language');
                $this->lang->load('sys_login');
                exit(json_encode(array('error' => lang('personal_Addresslimit'))));
            }
            $memberreceive_id = $this->db->insert_id();
            $insertData['receive_id'] = $memberreceive_id;
            if ($memberreceive_id) {
                exit(json_encode($insertData));
            } else {
                exit(json_encode(False));
            }
        }
    }

    // 设置收货默认地址
    public function addressDefault() {
        $this->load->model('memberreceive_model');
        $receive_id = (int) $this->input->post('receive_id', true);
        if ($this->memberreceive_model->addressDefault($this->page ['country'], $this->myMemberID, $receive_id)) {
            exit(json_encode(TRUE));
        } else {
            exit(json_encode(False));
        }
    }

    //设置账单默认地址
    public function billAddressDefault() {
        $this->load->model('memberreceive_model');
        $receive_id = (int) $this->input->post('billreceive_id', true);
        if ($this->memberreceive_model->billAddressDefault($this->page ['country'], $this->myMemberID, $receive_id)) {
            exit(json_encode(TRUE));
        } else {
            exit(json_encode(False));
        }
    }

    //删除收货地址
    function addressDelete() {
        $this->load->model('memberreceive_model');
        $receive_id = (int) $this->input->post('receive_id');
        if ($this->memberreceive_model->delete($this->page['country'], $this->myMemberID, $receive_id)) {
            exit(json_encode(TRUE));
        } else {
            exit(json_encode(False));
        }
    }

    //删除账单地址
    function addressBillDelete() {
        $this->load->model('memberreceive_model');
        $receive_id = (int) $this->input->post('billreceive_id');
        if ($this->memberreceive_model->deleteBill($this->page['country'], $this->myMemberID, $receive_id)) {
            exit(json_encode(TRUE));
        } else {
            exit(json_encode(False));
        }
    }

    //获取需要修改的收货地址信息
    function getAddressInfo() {
        $this->load->model('memberreceive_model');
        $receive_id = (int) $this->input->post('receive_id');
        $fields = 'member_id,receive_firstName,receive_lastName,receive_company,receive_country,receive_province,receive_city,receive_add1,receive_add2,receive_zipcode,receive_phone';
        $result = $this->memberreceive_model->getInfoById($this->page['country'], $this->myMemberID, $receive_id, $fields);
        if ($result) {
            $result['success'] = TRUE;
            exit(json_encode($result));
        } else {
            exit(json_encode(array('success' => false, 'error' => 'address load error!')));
        }
    }

    //获取需要修改的账单地址信息
    function getBillAddressInfo() {
        $this->load->model('memberreceive_model');
        $receive_id = (int) $this->input->post('billreceive_id');
        $fields = 'member_id,receive_firstName,receive_lastName,receive_company,receive_country,receive_province,receive_city,receive_add1,receive_add2,receive_zipcode,receive_phone';
        $result = $this->memberreceive_model->getBillInfoById($this->page['country'], $this->myMemberID, $receive_id, $fields);
        if ($result) {
            $result['success'] = TRUE;
            exit(json_encode($result));
        } else {
            exit(json_encode(array('success' => false, 'error' => 'address load error!')));
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
        $this->page['openComment'] = 10;
        $this->load->model('order_model');
        $this->load->model('Product_model');
        $this->page['listOrders'] = $this->order_model->getOrder($this->page['country'], $this->myMemberID);

        if ($this->page['listOrders']) {

            //获取第一个订单所有信息
            $this->page['orders'] = $this->order_model->getInfoByNumber($this->page['country'], $this->page['listOrders'][0]['order_number']);
            //获取订单留言
            $this->page['note'] = $this->order_model->getNoteByNumber($this->page['country'], $this->page['listOrders'][0]['order_number'], 'order_guestbook');
            
            
            //获取创建时间 计算出能否进行退货申请
            $this->page['applyStatus'] = 0;
            $applyTime = strtotime('+' . 35 . 'day', $this->page['listOrders'][0]['create_time']);
            if (time() < $applyTime) {
                $this->page['applyStatus'] = 1;
                $this->page['times'] = date('M, d Y', $applyTime);
            } else {
                $this->page['times'] = 0;
            }

            $this->load->model('ordersend_model'); //获取进度
            $send = $this->ordersend_model->getProgressById($this->page['country'], $this->page['listOrders'][0]['order_number'], /*  1, */ 'send_status,create_time');
            $sendCount = count($send);
            switch ($sendCount) {
                case 0:
                    $orderInfo['send']['first'] = '';
                    $orderInfo['send']['last'] = '';
                    break;
                case 1:
                    if ($send[0]['send_status'] == 1) {
                        $orderInfo['send']['first'] = date('M, d Y', $send[0]['create_time']);
                        $orderInfo['send']['last'] = date('M, d Y', $send[0]['create_time']);
                    } else {
                        $orderInfo['send']['first'] = date('M, d Y', $send[0]['create_time']);
                        $orderInfo['send']['last'] = '';
                    }
                    break;
                default:
                    if ($send[0]['send_status'] == 1) {
                        $orderInfo['send']['first'] = date('M, d Y', $send[0]['create_time']);
                        $orderInfo['send']['last'] = date('M, d Y', $send[0]['create_time']);
                    } else if ($send[$sendCount - 1]['send_status'] == 1) {
                        $orderInfo['send']['first'] = date('M, d Y', $send[0]['create_time']);
                        $orderInfo['send']['last'] = date('M, d Y', $send[$sendCount - 1]['create_time']);
                    } else {
                        $orderInfo['send']['first'] = date('M, d Y', $send[0]['create_time']);
                        $orderInfo['send']['last'] = '';
                    }
                    break;
            }


            if ($this->page['listOrders'][0]['send_status'] == 3) {
                $orderInfo['send']['first'] = date('M, d Y', $send[0]['create_time']);
                $orderInfo['send']['last'] = date('M, d Y', $send[0]['create_time']);
            }


            $this->load->model('ordership_model');
            $orderInfo['ship'] = $this->ordership_model->getInfoById($this->page['country'], $this->page['listOrders'][0]['order_number']);
            $this->load->model('orderbill_model');
            $orderInfo['bill'] = $this->orderbill_model->getInfoById($this->page['country'], $this->page['listOrders'][0]['order_number']);
            $this->load->model('orderdetails_model');
            $orderInfo['details'] = $this->orderdetails_model->listByOrderNumber($this->page['country'], $this->page['listOrders'][0]['order_number']);


            $this->page['sendUrl'] = 'javascript:void(0);';
            $this->page['url'] = $this->ordersend_model->getSendUrl($this->page['country'], $this->page['listOrders'][0]['order_number']);
            if ($this->page['url']) {
                if ($this->page['url']['track_url']) {
                    $this->page['sendUrl'] = $this->page['url']['track_url'];
                }
            }

            $this->load->model('collection_model');
            foreach ($orderInfo['details'] as $key => $detail) {
                $pro = $this->Product_model->orderPics($this->page['country'], $detail['product_id']);
                $orderInfo['details'][$key]['collection_url'] = $this->collection_model->getCollectionUrl($this->page['country'], $detail['product_id']);
                $orderInfo['details'][$key]['freebies'] = $pro['freebies'];
                $orderInfo['details'][$key]['seo_url'] = $pro['seo_url'];
                $orderInfo['details'][$key]['image'] = $pro['image'];
            }
            $this->page['orderInfo'] = $orderInfo;
        }
//        echo '<pre>';
//        var_dump($orderInfo);
//        exit;
        //$this->page['listDispose'] = ['0' => 'Order Received', '1' => 'Completed', '2' => 'Dispatched', '3' => 'Dispatched'];
        $this->page['listDispose'] = ['0' => 'Dispatching', '1' => 'Completed', '2' => 'Dispatching', '3' => 'Completed'];

        $accountOrderView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'account-order');
        $this->load->view($accountOrderView, $this->page);
    }

    public function ajaxOrder() {
        if ($this->input->is_ajax_request()) {
            $this->page['openComment'] = 10; //收货后允许发表评论的间隔时间
            $this->load->model('order_model');
            $this->load->model('Product_model');
            $this->load->model('collection_model');

            $orderInfo['order'] = $this->order_model->getInfoByID($this->page['country'], (int) $this->input->post('order_id'));
            if ($orderInfo['order']['member_id'] == $this->myMemberID) {
                //获取创建时间 计算出能否进行退货申请
                $applyStatus = 0;
                $applyTime = strtotime('+' . 35 . 'day', $orderInfo['order']['create_time']);
                if (time() < $applyTime) {
                    $applyStatus = 1;
                    $times = date('M, d Y', $applyTime);
                } else {
                    $times = 0;
                }

                //获取订单留言
                $note = $this->order_model->getNoteByNumber($this->page['country'], $orderInfo['order']['order_number'], 'order_guestbook');

                $this->load->model('ordersend_model'); //获取进度
                $send = $this->ordersend_model->getProgressById($this->page['country'], $orderInfo['order']['order_number'], /* 1, */ 'send_status,create_time');
                $sendCount = count($send);
                switch ($sendCount) {
                    case 0:
                        $orderInfo['send']['first'] = '';
                        $orderInfo['send']['last'] = '';
                        break;
                    case 1:
                        if ($send[0]['send_status'] == 1) {
                            $orderInfo['send']['first'] = date('M, d Y', $send[0]['create_time']);
                            $orderInfo['send']['last'] = date('M, d Y', $send[0]['create_time']);
                        } else {
                            $orderInfo['send']['first'] = date('M, d Y', $send[0]['create_time']);
                            $orderInfo['send']['last'] = '';
                        }
                        break;
                    default:
                        if ($send[0]['send_status'] == 1) {
                            $orderInfo['send']['first'] = date('M, d Y', $send[0]['create_time']);
                            $orderInfo['send']['last'] = date('M, d Y', $send[0]['create_time']);
                        } else if ($send[$sendCount - 1]['send_status'] == 1) {
                            $orderInfo['send']['first'] = date('M, d Y', $send[0]['create_time']);
                            $orderInfo['send']['last'] = date('M, d Y', $send[$sendCount - 1]['create_time']);
                        } else {
                            $orderInfo['send']['first'] = date('M, d Y', $send[0]['create_time']);
                            $orderInfo['send']['last'] = '';
                        }
                        break;
                }


                if ($orderInfo['order']['send_status'] == 3) {
                    $orderInfo['send']['first'] = date('M, d Y', $send[0]['create_time']);
                    $orderInfo['send']['last'] = date('M, d Y', $send[0]['create_time']);
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


                /*
                  <div class="col-xs-4 bs-wizard-step ' . ($orderInfo['send']['first'] ? 'active' : 'disabled') . '">
                  <div class="text-center bs-wizard-stepnum">Dispatched</div>
                  <div class="progress">
                  <div class="progress-bar"></div>
                  <div class="progress-bar"></div>
                  </div>
                  <a href="#" class="bs-wizard-dot"></a>
                  <div class="bs-wizard-info text-center">' . $orderInfo['send']['first'] . ' </div>
                  </div>
                 */
                
                
                
                if($orderInfo['order']['pay_status']==2){
                	$isRefund='<div class="col-xs-4 bs-wizard-step  active">
                            <div class="text-center bs-wizard-stepnum">Cancelled</div>
                            <div class="progress"><div class="progress-bar"></div></div>
                            <a href="javascript:void(0);" class="bs-wizard-dot"></a>
                         </div>';
                }else{
                	$isRefundStyle= $orderInfo['send']['last'] ? 'active' : 'disabled' ;
                	$isRefund='<div class="col-xs-4 bs-wizard-step '.$isRefundStyle.'">
                            <div class="text-center bs-wizard-stepnum">Shipped</div>
                            <div class="progress"><div class="progress-bar"></div></div>
                           	<a href="#" class="bs-wizard-dot"></a>
                            <div class="bs-wizard-info text-center">' . $orderInfo['send']['last'] . ' </div>
                         </div>';
                }


                $row1 = '
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="row bs-wizard" style="border-bottom:0;">
                                    <div class="col-xs-4 bs-wizard-step complete-full">
                                        <div class="text-center bs-wizard-stepnum">Order Received</div>
                                        <div class="progress"><div class="progress-bar"></div></div>
                                        <a href="#" class="bs-wizard-dot"></a>
                                        <div class="bs-wizard-info text-center">' . date('M, d Y', $orderInfo['order']['create_time']) . '<br/> ' . date('h:i:s', $orderInfo['order']['create_time']) . ' </div>
                                    </div>
                                        		
                                    <div class="col-xs-4 bs-wizard-step ' . ( $orderInfo['order']['pay_status']==2 ?'complete-full' : $orderInfo['send']['first'] ? 'complete-full' : 'complete') . '">
                                        <div class="text-center bs-wizard-stepnum">Dispatching</div>
                                        <div class="progress"><div class="progress-bar"></div></div>
                                        <a href="#" class="bs-wizard-dot"></a>
                                        <div class="bs-wizard-info text-center">' . date('M, d Y', $orderInfo['order']['create_time']) . '</div>
                                    </div>
                                    '.$isRefund.'
                                </div>        
                            </div>
                        </div>';

                /*  <button type="button" class="btn btn-default btn-md">
                  <span class="glyphicon glyphicon-transfer" aria-hidden="true"></span> Request Refund
                  </button> */
                $estimated = date("M, d Y", strtotime("+" . $orderInfo['order']["estimated_time"] . "day", $orderInfo['order']["create_time"]));
                $row2 = '
                       <div class="row">
                            <div class="col-xs-6">
                                
                                <div class="dg-main-account-content-address">
                                    <span class="dg-main-account-content-address-title">Tracking Number:</span><br/>
                                     ' . ($url ? ($url['track_code'] ? $url['track_code'] : 'Processing...') : 'Processing...') . '
                                </div>
                                            
                                <a href="' . $sendUrl . '"  target="_blank" onclick="trackthisorder($(this).attr(\'href\'));" id="order_' . $orderInfo['order']['order_number'] . '" type="button" class="btn btn-default btn-md">
                                    <span class="glyphicon glyphicon-plane" aria-hidden="true"></span> Track This Order
                                </a>
                            </div>
                            

                            <div class="col-xs-6 dg-main-account-content-buttons">
                        	
                                <div class="dg-main-account-content-address">
                                    <span class="dg-main-account-content-address-title">Estimated Time of Arrival:</span><br/>
                                    ' . $estimated . ' 
                                </div>
                                
                                <a href="' . ($applyStatus == 1 ? 'javascript:void(0);' : '/refund/orderLost/' . $orderInfo['order']["order_number"]) . '" onclick="orderLostApply($(this).attr(\'href\'), ' . "'" . $times . "'" . '  );  ">
                                   <button type="button" class="btn btn-default btn-md">
                                       <i class="fa fa-frown-o"></i> Still Haven t Received?
                                    </button>
                                </a> 
                            </div>                            
		
                              
               
                        </div>

                		<div class="row">
                                                            <div class="col-xs-6">
                                                                <div class="dg-main-account-content-orderrefund">
                                                                    <span class="dg-main-account-content-address-title">Shipping Address:</span><br/>' .
                        $orderInfo['ship']['receive_firstName'] . ' ' . $orderInfo['ship']['receive_lastName'] . '<br/>' .
                        $ship_address . '<br/>' .
                        $orderInfo['ship']['receive_city'] . ' , ' . $orderInfo['ship']['receive_zipcode'] . '<br/>' .
                        $orderInfo['ship']['receive_province'] . '<br/>' .
                        $orderInfo['ship']['receive_country'] . '<br/>' .
                        '</div>
                                                            </div>
                       <div class="col-xs-6">
                            <div class="dg-main-account-content-orderrefund">
                               	 <span class="dg-main-account-content-orderrefund-title">Order Summary:</span><br/>
                                 Item Price : ' . $this->page['currency'] . $orderInfo['order']['order_amount'] / 100 . ' <br/>
                                 Shipping Price : ' . $this->page['currency'] . $orderInfo['order']['freight_amount'] / 100 . ' <br/>
                                 ' . ($orderInfo['order']['order_insurance'] ? 'Shipping Insurance : ' . $this->page['currency'] . $orderInfo['order']['order_insurance'] / 100 . '<br/>' : '') . '		
    							 ' . ($orderInfo['order']['order_giftbox'] ? 'Shopping Bag : ' . $this->page['currency'] . $orderInfo['order']['order_giftbox'] / 100 . '<br/>' : '') . '
								 ' . ($orderInfo['order']['offers_amount'] ? 'Coupon : ' . '-' . $this->page['currency'] . $orderInfo['order']['offers_amount'] / 100 . '<br/>' : '') . '
								 <b>Total : ' . $this->page['currency'] . $orderInfo['order']['payment_amount'] / 100 . '</b>
                            </div>
                      </div>
                        		                                </div>';
                $row3 = '          <div class="dg-main-account-content-items">
        
                                                            <table class="table table-hover">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Image</th>
                                                                        <th>Product title</th>
                                                                        <th style="text-align: center">Qty</th>
                		                                                <th style="text-align: center">Price</th>
                                                                        <th style="text-align: center">Review</th>
                                                                        <th style="text-align: center">Support</th>
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
                            '<td><a href="/collections/' . $collection_url . '/products/' . $seo_url . '">' . htmlspecialchars_decode($orderDetails['product_name']) . '</a><span>' . $orderDetails['product_attr'] . '</span>' . ($pro['freebies'] ? "<div class='freebies'>+ " . $this->page['currency'] . " <span> " . ($orderDetails['payment_amount'] / 100) . " </span>  Additional Shipping Fee</div> " : "") . '</td>' .
                            '<td>' . $orderDetails['product_quantity'] . '</td>' .
                            '<td>' . $this->page['currency'] . ($pro['freebies'] ? "0" : ($orderDetails['payment_amount'] / 100)) . '</td>' .
                            '<td><input type="text" data-size="xxxs" data-toggle="modal" data-target="#account-order-comment" data-step="1" data-details_id="' . $orderDetails['details_id'] . '" data-order_number="' . $orderInfo['order']['order_number'] . '" data-product_sku="' . $orderDetails['product_sku'] . '" data-product_id="' . $orderDetails['product_id'] . '" data-product_name="' . $orderDetails['product_name'] . '" data-product_image="' . IMAGE_DOMAIN . $pro['image'] . '" data-whatever="@mdo" class="rating" value="' . $orderDetails['comments_star'] . '" id="star' . $orderDetails['details_id'] . '"></td>
                             <td>
                                <a href="/refund/index/' . $orderDetails['details_id'] . ' "> 
                            		<button class="btn btn-default">Support</button>
                            	</a>
                             </td>
                            </tr>  ';
                }



                // <th><!-- Operating -->Price</th>
                //'<td><input type="text" data-size="xxxs" data-toggle="modal" data-target="#account-order-comment" data-step="1" data-order_number="'.$orderInfo['order']['order_number'].'" data-product_sku="'. $orderDetails['product_sku'] .'" data-product_id="' . $orderDetails['product_id'] . '" data-product_name="' . $orderDetails['product_name'] . '" data-whatever="@mdo" class="rating" value="' . $orderDetails['comments_star'] . '"></td>' . 
                $row3 = $row3 . '

                                                                </tbody>
                                                            </table>
                											' . ($note['order_guestbook'] ? 'Note : ' . $note['order_guestbook'] : '') . '
                                                        </div>';

//                sleep(2);
                exit(json_encode(array('success' => TRUE, 'html' => $row1 . $row2 . $row3)));
            } else {
//                sleep(2);
                exit(json_encode(array('success' => FALSE, 'error' => 'order error！')));
            }
        } else {
//            sleep(2);
            exit(json_encode(array('success' => FALSE, 'error' => 'error！')));
        }
    }

}
