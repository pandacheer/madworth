<?php

/**
 *  @说明  订单控制器(前台)
 *  @作者  zhujian
 *  @qq    407284071
 *  
 *  要大改啊啊啊啊啊  先实现功能啊啊啊
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class order extends MY_Controller {

    private $terminal;

    public function __construct() {
        parent::__construct();

        $this->country = $this->page ['country'];
        $this->terminal = $this->session->userdata('isMobile');
        $this->load->model('template_model');
        $this->load->model('order_model');
    }

    function createOrder() {
        if ($this->input->cookie('webSite_pay') !== md5($this->page['datePRC'] . 'pay')) {//统计当天执行了支付的次数
            $this->input->set_cookie('webSite_pay', md5($this->page['datePRC'] . 'pay'), 2592000);
            $this->load->model('website_model');
            $this->website_model->payment($this->page['country']);
        }
        // 获取运费信息$shippingInfo
        $shipping_id = (int) $this->input->post('shipping', TRUE);
        $shippingInfo = $this->_getFreightAmount($shipping_id);

        if (!$shippingInfo || $shippingInfo['country_code'] !== $this->country) {
            redirect("home/showError/P1002");
        }
        $logIn = $this->session->userdata('member_id') ? true : false;
        $actionEmail = $logIn ? $this->session->userdata('member_email') : '';
        // 内部编号(调用提供的方法处理)
        $this->load->model('Sequence_model');
        $orderNumber = $this->Sequence_model->CreateOrderNumber();
        //$couponID = strtoupper($this->input->post('coupon', TRUE));
        $couponID = $this->input->post('ss') == 'DISCOUNT' ? 'DISCOUNT' : strtoupper($this->input->post('coupon', TRUE));
        $cartOrderInfo = $logIn ? $this->_getMongodbCart($orderNumber, $shippingInfo['price'], $actionEmail, $couponID) : $this->_getCookieCart($orderNumber, $shippingInfo['price'], $couponID);
        if (!is_array($cartOrderInfo)) {
            if ($cartOrderInfo == 'CouponERR') {
                redirect("home/showError/P1105"); //优惠券无效（需要翻译）
            } else {
                redirect("home/showError/P1104"); //购物车商品为空  
            }
        }
        $cartOrderInfo['order_insurance'] = $this->input->post('insurance') ? 100 : 0;
        $cartOrderInfo['order_giftbox'] = $this->input->post('giftbox') ? 100 : 0;
        $cartOrderInfo['payment_amount'] = $cartOrderInfo['order_amount'] + $shippingInfo['price'] - $cartOrderInfo['offers_amount'] + $cartOrderInfo['order_insurance'] + $cartOrderInfo['order_giftbox'];

        $payType = (int) $this->input->post('pay_type', TRUE);
        $orderGuestbook = $this->input->post('note', TRUE);
        switch ($payType) {
            case 1://PayPal支付
                $this->session->unset_userdata('orderCreateSuccess');
                $this->session->unset_userdata('reshash');
                $cartOrderInfo['order_guestbook'] = $this->input->post('note', TRUE);
                $cartOrderInfo['express_type'] = $shippingInfo ['name'];
                $cartOrderInfo['estimated_time'] = $shippingInfo ['estimated_time'];
                $this->_paypalSend($this->page['currency_payment'], $cartOrderInfo, $orderNumber);
                break;
            case 2:// Paypal信用卡支付
                $address_id = (int) $this->input->post('address_id', TRUE);
                $actionID = $this->session->userdata('member_id');
                $actionName = $this->session->userdata('member_name');
                $addressInfo = $this->_getAddress($logIn, $this->country, $actionID, $address_id);
                if (!$addressInfo) {
                    redirect("home/showError/P1005"); //收货地址错误
                }
                //2016-01-04 增加帐单地址*****************************************************************
                $billing = (int) $this->input->post('billing', TRUE);
                $billaddress_id = (int) $this->input->post('billaddress_id', TRUE);

                if ($billing) {
                    $bill_addressInfo = $addressInfo;
                } else {
                    $bill_addressInfo = $this->_getBillAddress($logIn, $this->country, $actionID, $billaddress_id);
                    if (!$bill_addressInfo) {
                        redirect("home/showError/P1011"); //帐单地址错误
                    }
                }
                //2016-01-04 增加帐单地址结束*************************************************************
                if (!$logIn) {
                    $actionEmail = strtolower($this->input->post('emailaddress', true));
                    $result = $this->_register($this->country, $actionEmail, $addressInfo['receive_firstName']);
                    if (!$result) {
                        redirect("home/showError/P1006"); //快速注册失败
                    } else {
                        $actionID = $result['member_id'];
                        $actionName = $result['member_name'];
                    }
                }
                if ($actionName === '') {
                    $actionName = $addressInfo['receive_firstName'] . ' ' . $addressInfo['receive_lastName'];
                }
                //2016-01-04 _ppCreditCardCreateOrder方法增加帐单地址参数*************************************************************
                $orderInfo = $this->_ppCreditCardCreateOrder($actionID, $actionEmail, $actionName, $orderNumber, $cartOrderInfo, $orderGuestbook, $addressInfo, $bill_addressInfo, $shippingInfo);

                if ($orderInfo) {
                    $creditCardInfo = array(
                        'cardholderName' => $this->input->post('credit_card_name'),
                        'cvv' => $this->input->post('credit_card_cvv'),
                        'firstName' => $this->input->post('credit_card_first_name'),
                        'lastName' => $this->input->post('credit_card_last_name'),
                        'number' => $this->input->post('credit_card_number'),
                        'expDateYear' => 2000 + $this->input->post('credit_card_expiry_yy'),
                        'expDateMonth' => $this->input->post('credit_card_expiry_mm')
                    );
                    //2016-01-04 _ppCreditCardPayment 方法增加帐单地址参数*************************************************************
                    $this->_ppCreditCardPayment($this->page['currency_payment'], $logIn, $actionID, $actionEmail, $orderInfo, $billaddress_id, $address_id, $creditCardInfo);
                } else {
                    redirect("home/showError/P1007"); //订单生成失败
                }
                break;
            case 3://Braintree支付
                $address_id = (int) $this->input->post('address_id', TRUE);
                $actionID = $this->session->userdata('member_id');
                $actionName = $this->session->userdata('member_name');
                $addressInfo = $this->_getAddress($logIn, $this->country, $actionID, $address_id);
                if (!$addressInfo) {
                    redirect("home/showError/P1005"); //收货地址错误
                }
                if (!$logIn) {
                    $actionEmail = strtolower($this->input->post('emailaddress', true));
                    $result = $this->_register($this->country, $actionEmail, $addressInfo['receive_firstName']);
                    if (!$result) {
                        redirect("home/showError/P1006"); //快速注册失败
                    } else {
                        $actionID = $result['member_id'];
                        $actionName = $result['member_name'];
                    }
                }
                if ($actionName === '') {
                    $actionName = $addressInfo['receive_firstName'] . ' ' . $addressInfo['receive_lastName'];
                }
                $orderInfo = $this->_braintreeCreateOrder($actionID, $actionEmail, $actionName, $orderNumber, $cartOrderInfo, $orderGuestbook, $addressInfo, $shippingInfo);
                if ($orderInfo) {
                    $creditCardInfo = array(
                        'cardholderName' => $this->input->post('credit_card_name'),
                        'cvv' => $this->input->post('credit_card_cvv'),
                        'number' => $this->input->post('credit_card_number'),
                        'expirationDate' => $this->input->post('credit_card_expiry_mm') . '/' . $this->input->post('credit_card_expiry_yy')
                    );
                    $this->_braintreePayment($logIn, $actionID, $actionEmail, $orderInfo, $address_id, $creditCardInfo);
                } else {
                    redirect("home/showError/P1007"); //订单生成失败
                }
                break;
            default:
                break;
        }
    }

    /*     * ************************************
     * Paypal 信用卡支付
     * ************************************ */

    function _ppCreditCardCreateOrder($actionID, $actionEmail, $actionName, $orderNumber, $cartOrderInfo, $orderGuestbook, $addressInfo, $insertBillInfo, $shippingInfo) {
        $create_time = time();
        $insertBillInfo['order_number'] = $orderNumber;

        $insertShipInfo = $addressInfo;
        $insertShipInfo['order_number'] = $orderNumber;
        $insertShipInfo['express_type'] = $shippingInfo['name'];
        $insertOrderDetailsInfo = $cartOrderInfo['products'];
        $insertOrderAppendInfo = array(
            'order_number' => $orderNumber,
            'order_guestbook' => $orderGuestbook,
            'landing_page' => $this->session->userdata('landing_page'),
            'refer_site' => $this->session->userdata('refer_site'),
            'order_weight' => $cartOrderInfo['order_weight']
        );

        $this->load->helper('common');
        $insertOrderInfo = array(
            'order_number' => $orderNumber,
            'member_id' => $actionID,
            'member_email' => $actionEmail,
            'member_name' => $actionName,
            'order_quantity' => $cartOrderInfo['order_quantity'],
            'order_insurance' => $cartOrderInfo['order_insurance'],
            'order_giftbox' => $cartOrderInfo['order_giftbox'],
            'order_amount' => $cartOrderInfo['order_amount'],
            'payment_amount' => $cartOrderInfo['payment_amount'],
            'offers_amount' => $cartOrderInfo['offers_amount'],
            'coupons_id' => $cartOrderInfo['coupons_id'],
            'freight_amount' => $cartOrderInfo['freight_amount'],
            'receive_name' => $addressInfo['receive_firstName'] . ' ' . $addressInfo['receive_lastName'],
            'create_time' => $create_time,
            'send_status' => 0,
            'is_resend' => 1,
            'pay_status' => 0,
            'doc_status' => 1,
            'update_time' => $create_time,
            'pay_type' => 2,
            'estimated_time' => $shippingInfo['estimated_time'],
            'transaction_id' => 0,
            'operator' => '', 'ip_address' => getIP(), 'terminal' => $this->session->userdata('isMobile') == 'pc' ? 1 : 2
        );
        // 掉用方法进行订单添加 同时对5张表进行
        $order_id = $this->order_model->addOrder($this->country, $insertOrderInfo, $insertOrderAppendInfo, $insertBillInfo, $insertShipInfo, $insertOrderDetailsInfo);
        if ($order_id) {
            return array('OrderInfo' => $insertOrderInfo, 'OrderDetailsInfo' => $insertOrderDetailsInfo, 'OrderShipInfo' => $insertShipInfo, 'OrderBillInfo' => $insertBillInfo);
        } else {
            return false;
        }
    }

    function _ppCreditCardPayment($currencyCodeType, $logIn, $actionID, $actionEmail, $orderInfo, $bill_address_id, $address_id, $creditCardInfo) {
        $this->load->helper('callerservice');
        $countryCode = $this->page['country'];
        $paymentType = 'Sale';
        $firstName = urlencode($creditCardInfo['firstName']);
        $lastName = urlencode($creditCardInfo['lastName']);

        $creditCardNumber = urlencode($creditCardInfo['number']);
        $expDateMonth = urlencode($creditCardInfo['expDateMonth']);
        $padDateMonth = str_pad($expDateMonth, 2, '0', STR_PAD_LEFT);
        $expDateYear = urlencode($creditCardInfo['expDateYear']);
        $cvv2Number = urlencode($creditCardInfo['cvv']);

        $address1 = urlencode($orderInfo['OrderShipInfo']['receive_add1']);
        $address2 = urlencode($orderInfo['OrderShipInfo']['receive_add2']);
        $city = urlencode($orderInfo['OrderShipInfo']['receive_city']);
        $state = urlencode($orderInfo['OrderShipInfo']['receive_province']);
        $zip = urlencode($orderInfo['OrderShipInfo']['receive_zipcode']);
        $orderNumber = urlencode($orderInfo['OrderInfo']['order_number']);
        $amount = urlencode($orderInfo['OrderInfo']['payment_amount'] / 100);
        $productTotal = urlencode(($orderInfo['OrderInfo']['payment_amount'] - $orderInfo['OrderInfo']['freight_amount']) / 100);
        $shippingAmt = urlencode($orderInfo['OrderInfo']['freight_amount'] / 100);
        $currencyCode = $currencyCodeType;
        $serverName = $this->input->server('SERVER_NAME');
        $serverPort = $this->input->server('SERVER_PORT');
        $url = dirname('http://' . $serverName . ':' . $serverPort . $this->input->server('REQUEST_URI'));
//        $url = dirname('https://' . $serverName . $this->input->server('REQUEST_URI'));
        $notifyURL = urlencode($url . '/callBackIPN');

        $nvpstr = "&PAYMENTACTION=$paymentType&INVNUM=$orderNumber&AMT=$amount&ITEMAMT=$productTotal&SHIPPINGAMT=$shippingAmt&ACCT=$creditCardNumber&EXPDATE=" . $padDateMonth . $expDateYear . "&CVV2=$cvv2Number&FIRSTNAME=$firstName&LASTNAME=$lastName&STREET=$address1&STREET2=$address2&CITY=$city&STATE=$state" .
                "&ZIP=$zip&COUNTRYCODE=$countryCode&CURRENCYCODE=$currencyCode&NOTIFYURL=$notifyURL";
        $resArray = hash_call("doDirectPayment", $nvpstr);

        $ack = strtoupper($resArray["ACK"]);

        if ($ack == "SUCCESS") {
            $create_time = time();
            $transaction_id = $resArray['TRANSACTIONID'];
            if ($resArray['AMT'] != $amount || $resArray['CURRENCYCODE'] != $currencyCode) {//判断实际支付金额与订单金额是否一致
                redirect("home/showError/P1100");
            } else {
                if ($this->input->cookie('webSite_purchase') !== md5($this->page['datePRC'] . 'purchase')) {//统计当天支付成功数
                    $this->input->set_cookie('webSite_purchase', md5($this->page['datePRC'] . 'purchase'), 2592000);
                    $this->load->model('website_model');
                    $this->website_model->purchased($this->page['country']);
                }
                $this->order_model->payment($this->page['country'], $actionID, $actionEmail, $orderInfo['OrderInfo']['coupons_id'], $orderInfo['OrderInfo']['order_number'], $orderInfo['OrderInfo']['payment_amount'], $transaction_id, $create_time, $address_id, $bill_address_id);
                // 删除购物车表里的信息
                if ($logIn) {
                    $this->load->model('cart_model');
                    $this->cart_model->delCart($this->country, $actionEmail, 1, 1, 2);
                } else {
                    $this->load->helper('cookie');
                    delete_cookie('cart');
                }

                //添加风险评估
                $this->_risk($this->page['country'], $orderInfo['OrderInfo'], $orderInfo['OrderShipInfo'], 1, $creditCardNumber);

                //发生订单信息邮件给客户
                $this->orderEmail($actionEmail, $create_time, $this->page ['currency'], $orderInfo['OrderInfo'], $orderInfo['OrderDetailsInfo'], $orderInfo['OrderShipInfo'], $orderInfo['OrderBillInfo']);

                if ($logIn && $this->session->userdata('member_name') == '') {
                    $this->session->set_userdata('member_name', $orderInfo['OrderInfo']['member_name']);
                }
                $this->session->set_userdata('orderCreateSuccess', $orderInfo);
                $this->session->set_userdata('orderNumber', $orderInfo['OrderInfo']['order_number']);
                $this->session->set_userdata('orderEmail', $orderInfo['OrderInfo']['member_email']);
                redirect("order/success");
            }
        } else {
            if (!$address_id) {
                $orderInfo['OrderShipInfo']['receive_eamil'] = $actionEmail;
                $this->session->set_userdata('cartInputAddress', $orderInfo['OrderShipInfo']);
            }
            //2016-1-5加入保存部分信用卡号
            $resArray['creditCardNumber'] = substr($creditCardNumber, 0, 6) . '********' . substr($creditCardNumber, -4);
            $this->load->model('paymentlog_model');
            $payLogInfo = array(
                'transactionid' => 'error',
                'ordernumber' => $orderNumber,
                'currencycode' => $currencyCode,
                'amount' => $amount,
                'paytype' => 'PPCreditCard',
                'status' => '错误',
                'REASON' => json_encode($resArray),
                'ordertime' => time(),
                'createtime' => time()
            );
            $this->paymentlog_model->insert($this->page['country'], $payLogInfo);
            $this->load->helper('language');
            $this->lang->load('sys_pay');
            if (lang('P' . $resArray['L_ERRORCODE0']) == '') {
                $msg = $resArray['L_LONGMESSAGE0'] ? $resArray['L_LONGMESSAGE0'] : $resArray['L_SHORTMESSAGE0'];
                redirect("home/showError/P" . $resArray['L_ERRORCODE0'] . '/' . urlencode($msg));
            } else {
                redirect("home/showError/P" . $resArray['L_ERRORCODE0']);
            }
//            redirect("home/showError/P1100");
        }
    }

    /*     * *************************************
     *          PayPal支付
     * ************************************ */

    private function _paypalSend($currencyCodeType, $cartOrderInfo, $order_number) {
        $this->load->helper('callerservice');
        $nvpHeader = nvpHeader();

        $serverName = $this->input->server('SERVER_NAME');
        $serverPort = $this->input->server('SERVER_PORT');
        $url = dirname('http://' . $serverName . ':' . $serverPort . $this->input->server('REQUEST_URI'));
//        $url = dirname('https://' . $serverName . $this->input->server('REQUEST_URI'));

        $paymentType = 'Sale';

        $payment_amount = round($cartOrderInfo['payment_amount'] / 100, 2); //支付总额（含运费的总价-优惠券）
        $order_amount = round(($cartOrderInfo['payment_amount'] - $cartOrderInfo['freight_amount']) / 100, 2); //商品总价
        $freight_amount = round($cartOrderInfo['freight_amount'] / 100, 2); //运费总额

        $L_NAME0 = 'DrGrab Items';
        $L_AMT0 = $order_amount;
        $L_QTY0 = 1;
        $desc = '';
        foreach ($cartOrderInfo ['products'] as $productInfo) {
            $desc.=htmlspecialchars_decode($productInfo['product_name']) . ';';
        }

        $desc = mb_substr($desc, 0, 127, 'utf-8');
        $note = '&PAYMENTREQUEST_0_NOTETEXT=' . $cartOrderInfo['order_guestbook'];

        $returnURL = urlencode($url . '/reviewOrder');
        $cancelURL = urlencode(site_url('cart'));

        $product = "&PAYMENTREQUEST_0_INVNUM=" . $order_number . "&L_PAYMENTREQUEST_0_NAME0=" . $L_NAME0 . "&L_PAYMENTREQUEST_0_AMT0=" . $L_AMT0 . "&L_PAYMENTREQUEST_0_QTY0=" . $L_QTY0 . "&L_PAYMENTREQUEST_0_DESC0=" . urlencode($desc);
        $nvpstr = "&ADDRESSOVERRIDE=0" . $note . $product . "&PAYMENTREQUEST_0_PAYMENTACTION=" . $paymentType . "&PAYMENTREQUEST_0_CURRENCYCODE=" . $currencyCodeType . "&PAYMENTREQUEST_0_AMT=" . $payment_amount . "&PAYMENTREQUEST_0_ITEMAMT=" . $order_amount . "&PAYMENTREQUEST_0_SHIPPINGAMT=" . $freight_amount . "&RETURNURL=" . $returnURL . "&CANCELURL=" . $cancelURL;
        $nvpstr = $nvpHeader . $nvpstr;

        $resArray = hash_call("SetExpressCheckout", $nvpstr);
        
        $_SESSION ['reshash'] = $resArray;

        $ack = strtoupper($resArray ["ACK"]);

        if ($ack == "SUCCESS") {
            $token = urldecode($resArray ["TOKEN"]);
            if ($this->redis->set('PayPal:' . $this->page['country'] . $order_number, json_encode($cartOrderInfo), 0, 0, 3600, 0)) {
                $payPalURL = PAYPAL_URL . $token;
                header("Location: " . $payPalURL);
            } else {
                redirect("home/showError/P1004"); //Redis写入错误
            }
        } else {
            $this->load->model('paymentlog_model');
            $payLogInfo = array(
                'transactionid' => 'error',
                'ordernumber' => $order_number,
                'currencycode' => $currencyCodeType,
                'amount' => $order_amount,
                'paytype' => 'PayPal',
                'status' => '第一次调用错误',
                'REASON' => json_encode($resArray),
                'ordertime' => time(),
                'createtime' => time()
            );
            $this->paymentlog_model->insert($this->page['country'], $payLogInfo);
            $this->load->helper('language');
            $this->lang->load('sys_pay');
            if (lang('P' . $resArray['L_ERRORCODE0']) == '') {
                $msg = $resArray['L_LONGMESSAGE0'] ? $resArray['L_LONGMESSAGE0'] : $resArray['L_SHORTMESSAGE0'];
                redirect("home/showError/P" . $resArray['L_ERRORCODE0'] . '/' . urlencode($msg));
            } else {
                redirect("home/showError/P" . $resArray['L_ERRORCODE0']);
            }
//            redirect("home/showError/P1010"); //PayPal失败
        }
    }

    //接收PayPay第一次传过来的数据
    //收货人信息
    //支付人信息
    function reviewOrder() {
        $headView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'head');
        $this->page['head'] = $this->load->view($headView, $this->page, true);
        $footView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'foot');
        $this->page['foot'] = $this->load->view($footView, $this->page, true);

        $this->load->helper('callerservice');
        $nvpHeader = nvpHeader();
        $token = urlencode($this->input->get('token'));
        $nvpstr = "&TOKEN=" . $token;
        $nvpstr = $nvpHeader . $nvpstr;

        $resArray = hash_call("GetExpressCheckoutDetails", $nvpstr);
        $_SESSION['reshash'] = $resArray;
        $ack = strtoupper($resArray["ACK"]);
        if ($ack == 'SUCCESS' || $ack == 'SUCCESSWITHWARNING') {
            $this->page['paypalResult'] = $resArray;
            //PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE:
            $this->load->model('countryzone_model');
            $this->page ['States'] = $this->countryzone_model->getZoneListByCountryCode($this->page['country']);
            $paypalconfirmView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'paypalconfirm');
            $this->load->helper('language');
            $this->lang->load('sys_address');
            $this->page ['addCountry'] ['state'] = lang($this->page['country'] . 'state');
            $this->page ['addCountry'] ['city'] = lang($this->page['country'] . 'city');
            $this->page ['addCountry'] ['zipcode'] = lang($this->page['country'] . 'zipcode');
            $this->load->view($paypalconfirmView, $this->page);
        } else {
            $this->load->model('paymentlog_model');
            $payLogInfo = array(
                'transactionid' => 'error',
                'ordernumber' => 0,
                'currencycode' => $this->page['country'],
                'amount' => 0,
                'paytype' => 'PayPal',
                'status' => '接收PayPay返回信息错误',
                'REASON' => json_encode($resArray),
                'ordertime' => time(),
                'createtime' => time()
            );
            $this->paymentlog_model->insert($this->page['country'], $payLogInfo);
            $this->load->helper('language');
            $this->lang->load('sys_pay');
            if (lang('P' . $resArray['L_ERRORCODE0']) == '') {
                $msg = $resArray['L_LONGMESSAGE0'] ? $resArray['L_LONGMESSAGE0'] : $resArray['L_SHORTMESSAGE0'];
                redirect("home/showError/P" . $resArray['L_ERRORCODE0'] . '/' . urlencode($msg));
            } else {
                redirect("home/showError/P" . $resArray['L_ERRORCODE0']);
            }
//            redirect("home/showError/P1010"); //PayPal失败
        }
    }

    //PayPal最终支付 $countryNames[$countryList[$country]]
    function checkoutPayment() {
        $order_number = $this->session->userdata('reshash')['PAYMENTREQUEST_0_INVNUM'];
        $insertBillInfo = array(
            'order_number' => $order_number,
            'receive_firstName' => $this->input->post('firstname'),
            'receive_lastName' => '',
            'receive_company' => '', //$this->input->post('company'),
            'receive_country' => $this->page['countryList'][$this->country]['name'], //$this->input->post('country'),
            'receive_province' => $this->input->post('state'),
            'receive_city' => $this->input->post('suburb'),
            'receive_add1' => $this->input->post('address'),
            'receive_add2' => $this->input->post('apt'),
            'receive_zipcode' => $this->input->post('postcode'),
            'receive_phone' => $this->input->post('phone'),
        );
        $orderCreateSuccess = $this->_paypalCreateOrder($this->session->userdata('member_id'), $order_number, $insertBillInfo);
        if ($orderCreateSuccess) {
            $this->session->set_userdata('orderCreateSuccess', $orderCreateSuccess);

            $this->load->helper('callerservice');
            $token = urlencode($this->session->userdata('reshash')['TOKEN']);
            $paymentAmount = urlencode($this->session->userdata('reshash')['PAYMENTREQUEST_0_AMT']);
            $paymentType = urlencode('SALE');
            $currCodeType = urlencode($this->session->userdata('reshash')['CURRENCYCODE']);
            $payerID = urlencode($this->session->userdata('reshash')['PAYERID']);
            $serverName = urlencode($this->input->server('SERVER_NAME'));

            $serverPort = $this->input->server('SERVER_PORT');
            $url = dirname('http://' . $this->input->server('SERVER_NAME') . ':' . $serverPort . $this->input->server('REQUEST_URI'));
//            $url = dirname('https://' . $this->input->server('SERVER_NAME') . $this->input->server('REQUEST_URI'));
            $notifyURL = urlencode($url . '/callBackIPN');

            $nvpstr = '&TOKEN=' . $token . '&PAYERID=' . $payerID . '&PAYMENTACTION=' . $paymentType . '&AMT=' . $paymentAmount . '&CURRENCYCODE=' . $currCodeType . '&IPADDRESS=' . $serverName . '&PAYMENTREQUEST_0_NOTIFYURL=' . $notifyURL;

            $resArray = hash_call("DoExpressCheckoutPayment", $nvpstr);
            $ack = strtoupper($resArray["ACK"]);

            if ($ack != 'SUCCESS' && $ack != 'SUCCESSWITHWARNING') {
                $this->load->model('paymentlog_model');
                $payLogInfo = array(
                    'transactionid' => 'error',
                    'ordernumber' => $orderCreateSuccess['OrderInfo']['order_number'],
                    'currencycode' => $currCodeType,
                    'amount' => $paymentAmount,
                    'paytype' => 'PayPal',
                    'status' => '支付错误',
                    'REASON' => json_encode($resArray),
                    'ordertime' => time(),
                    'createtime' => time()
                );
                $this->paymentlog_model->insert($this->page['country'], $payLogInfo);
                $this->load->helper('language');
                $this->lang->load('sys_pay');
                if (lang('P' . $resArray['L_ERRORCODE0']) == '') {
                    $msg = $resArray['L_LONGMESSAGE0'] ? $resArray['L_LONGMESSAGE0'] : $resArray['L_SHORTMESSAGE0'];
                    redirect("home/showError/P" . $resArray['L_ERRORCODE0'] . '/' . urlencode($msg));
                } else {
                    redirect("home/showError/P" . $resArray['L_ERRORCODE0']);
                }
//                redirect("home/showError/P1008");
            } else {
                if ($resArray['PAYMENTINFO_0_PAYMENTSTATUS'] == 'Completed') {//交易成功
                    if ($resArray['AMT'] != $paymentAmount || $resArray['CURRENCYCODE'] != $currCodeType) {//判断实际支付金额与订单金额是否一致
                        redirect("home/showError/P1100");
                    } else {
                        if ($this->input->cookie('webSite_purchase') !== md5($this->page['datePRC'] . 'purchase')) {//统计当天支付成功数
                            $this->input->set_cookie('webSite_purchase', md5($this->page['datePRC'] . 'purchase'), 2592000);
                            $this->load->model('website_model');
                            $this->website_model->purchased($this->page['country']);
                        }
                        $orderCreateSuccess = $this->session->userdata('orderCreateSuccess');
                        $this->order_model->payment($this->page['country'], $orderCreateSuccess['OrderInfo']['member_id'], $orderCreateSuccess['OrderInfo']['member_email'], $orderCreateSuccess['OrderInfo']['coupons_id'], $this->session->userdata('reshash')['PAYMENTREQUEST_0_INVNUM'], $this->session->userdata('reshash')['PAYMENTREQUEST_0_AMT'] * 100, $resArray['PAYMENTINFO_0_TRANSACTIONID'], strtotime($resArray['PAYMENTINFO_0_ORDERTIME']));
                        if ($this->session->userdata('member_id')) { // 删除购物车表里的信息
                            if ($this->session->userdata('member_name') == '') {
                                $this->session->set_userdata('member_name', $orderCreateSuccess['OrderInfo']['member_name']);
                            }
                            $this->load->model('cart_model');
                            $this->cart_model->delCart($this->country, $orderCreateSuccess['OrderInfo']['member_email'], 1, 1, 2);
                        } else {
                            $this->load->helper('cookie');
                            delete_cookie('cart');
                        }
                        $this->redis->delete('PayPal:' . $this->page['country'] . $this->session->userdata('reshash')['PAYMENTREQUEST_0_INVNUM']);
//                        $this->session->unset_userdata('orderCreateSuccess');
                        $this->session->unset_userdata('reshash');

                        //添加风险评估
                        $this->_risk($this->page['country'], $orderCreateSuccess['OrderInfo'], $orderCreateSuccess['ShipInfo'], 2);
                        //发生订单信息邮件给客户
                        $this->orderEmail($orderCreateSuccess['OrderInfo']['member_email'], time(), $this->page ['currency'], $orderCreateSuccess['OrderInfo'], $orderCreateSuccess['OrderDetailsInfo'], $orderCreateSuccess['ShipInfo'], $orderCreateSuccess['BillInfo']);
                        $this->session->set_userdata('orderNumber', $orderCreateSuccess['OrderInfo']['order_number']);
                        $this->session->set_userdata('orderEmail', $orderCreateSuccess['OrderInfo']['member_email']);
                        redirect("order/success");
                    }
                } else {
                    $this->load->model('paymentlog_model');
                    $payLogInfo = array(
                        'transactionid' => $resArray['PAYMENTINFO_0_TRANSACTIONID'],
                        'ordernumber' => $orderCreateSuccess['OrderInfo']['order_number'],
                        'currencycode' => $resArray['PAYMENTINFO_0_CURRENCYCODE'],
                        'amount' => $resArray['PAYMENTINFO_0_AMT'],
                        'paytype' => 'PayPal',
                        'status' => $resArray['PAYMENTINFO_0_PAYMENTSTATUS'],
                        'REASON' => $resArray['PAYMENTINFO_0_PENDINGREASON'],
                        'ordertime' => $resArray['PAYMENTINFO_0_ORDERTIME'],
                        'createtime' => time()
                    );
                    $this->paymentlog_model->insert($this->page['country'], $payLogInfo);
                    $this->load->helper('language');
                    $this->lang->load('sys_pay');
                    if (lang('P' . $resArray['L_ERRORCODE0']) == '') {
                        $msg = $resArray['L_LONGMESSAGE0'] ? $resArray['L_LONGMESSAGE0'] : $resArray['L_SHORTMESSAGE0'];
                        redirect("home/showError/P" . $resArray['L_ERRORCODE0'] . '/' . urlencode($msg));
                    } else {
                        redirect("home/showError/P" . $resArray['L_ERRORCODE0']);
                    }
//                    redirect("home/showError/P1009");
                }
            }
        } else {
            redirect("home/showError/P1001"); //echo '订单生成失败!';
        }
    }

    function _paypalCreateOrder($member_id, $order_number, $insertBillInfo) {
        $create_time = time();
        $orderJson = $this->redis->get('PayPal:' . $this->page['country'] . $order_number);
        if ($orderJson) {//如果redis订单数据不存在则已经过期
            $orderInfo = json_decode($orderJson, TRUE);

            $insertShipInfo = $insertBillInfo;
            $insertShipInfo['express_type'] = $orderInfo['express_type'];
            $insertOrderDetailsInfo = $orderInfo['products'];
            $insertOrderAppendInfo = array(
                'order_number' => $order_number,
                'order_guestbook' => $orderInfo['order_guestbook'],
                'landing_page' => $this->session->userdata('landing_page'),
                'refer_site' => $this->session->userdata('refer_site'),
                'order_weight' => $orderInfo['order_weight']
            );

            $insertOrderInfo = array(
                'order_number' => $order_number,
                'order_quantity' => $orderInfo['order_quantity'],
                'order_insurance' => $orderInfo['order_insurance'],
                'order_giftbox' => $orderInfo['order_giftbox'],
                'order_amount' => $orderInfo['order_amount'],
                'payment_amount' => $orderInfo['payment_amount'],
                'offers_amount' => $orderInfo['offers_amount'],
                'coupons_id' => $orderInfo['coupons_id'],
                'freight_amount' => $orderInfo['freight_amount'],
                'receive_name' => $this->session->userdata('reshash')['PAYMENTREQUEST_0_SHIPTONAME'],
                'create_time' => $create_time,
                'send_status' => 0,
                'is_resend' => 1,
                'pay_status' => 0,
                'doc_status' => 1,
                'update_time' => $create_time,
                'pay_type' => 1,
                'estimated_time' => $orderInfo['estimated_time'],
                'transaction_id' => 0,
                'operator' => '', 'ip_address' => getIP(), 'terminal' => $this->session->userdata('isMobile') == 'pc' ? 1 : 2
            );
            if ($member_id) { // 对已登录的用户添加订单
                $insertOrderInfo['member_id'] = $member_id;
                $insertOrderInfo['member_email'] = $this->session->userdata('member_email');
                $insertOrderInfo['member_name'] = $this->session->userdata('member_name') ? $this->session->userdata('member_name') : $insertShipInfo['receive_firstName'];
            } else {// 获取地址
                $member_email = $this->session->userdata('reshash')['EMAIL'];
                $this->load->model('member_model');
                $memberInfo = $this->member_model->insert($this->country, $member_email, 0, true);
                if ($memberInfo) {
                    $insertOrderInfo['member_id'] = $memberInfo['member_id'];
                    $insertOrderInfo['member_email'] = $member_email;
                    $insertOrderInfo['member_name'] = $memberInfo['member_name'] ? $memberInfo['member_name'] : $insertBillInfo['receive_firstName'];
                    if (array_key_exists('member_pwd', $memberInfo)) {// 发送用户注册成功邮件
                        $this->registeredEmail($member_email, $memberInfo['member_pwd'], $memberInfo['member_name'] ? $memberInfo['member_name'] : $insertBillInfo['receive_firstName']);
                    }
                } else {
                    return false;
                }
            }
            // 掉用方法进行订单添加 同时对5张表进行
            $is_order = $this->order_model->addOrder($this->country, $insertOrderInfo, $insertOrderAppendInfo, $insertBillInfo, $insertShipInfo, $insertOrderDetailsInfo);
            if ($is_order) {
                return array('OrderInfo' => $insertOrderInfo, 'OrderDetailsInfo' => $insertOrderDetailsInfo, 'ShipInfo' => $insertShipInfo, 'BillInfo' => $insertBillInfo);
            } else {
                return false;
            }
        } else {
            return FALSE;
        }
    }

    //IPN处理
    function callBackIPN() {
        define("DEBUG", 0);
        define("USE_SANDBOX", 0);


        define("LOG_FILE", "./ipn.log");

        $raw_post_data = file_get_contents('php://input');
        $raw_post_array = explode('&', $raw_post_data);
        $myPost = array();
        foreach ($raw_post_array as $keyval) {
            $keyval = explode('=', $keyval);
            if (count($keyval) == 2)
                $myPost[$keyval[0]] = urldecode($keyval[1]);
        }
        $req = 'cmd=_notify-validate';
        if (function_exists('get_magic_quotes_gpc')) {
            $get_magic_quotes_exists = true;
        }
        foreach ($myPost as $key => $value) {
            $value = ($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) ? urlencode(stripslashes($value)) : urlencode($value);
            $req .= "&$key=$value";
        }
        $paypal_url = USE_SANDBOX == true ? "https://www.sandbox.paypal.com/cgi-bin/webscr" : "https://www.paypal.com/cgi-bin/webscr";

        $ch = curl_init($paypal_url);
        if ($ch == FALSE) {
            return FALSE;
        }

        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);

        if (DEBUG == true) {
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
        }


        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));

        $res = curl_exec($ch);
        if (curl_errno($ch) != 0) { // cURL error
            if (DEBUG == true) {
                error_log(date('[Y-m-d H:i e] ') . "Can't connect to PayPal to validate IPN message: " . curl_error($ch) . PHP_EOL, 3, LOG_FILE);
            }
            curl_close($ch);
            exit;
        } else {
            // Log the entire HTTP response if debug is switched on.
            if (DEBUG == true) {
                error_log(date('[Y-m-d H:i e] ') . "HTTP request of validation request:" . curl_getinfo($ch, CURLINFO_HEADER_OUT) . " for IPN payload: $req" . PHP_EOL, 3, LOG_FILE);
                error_log(date('[Y-m-d H:i e] ') . "HTTP response of validation request: $res" . PHP_EOL, 3, LOG_FILE);
            }
            curl_close($ch);
        }

        $tokens = explode("\r\n\r\n", trim($res));
        $res = trim(end($tokens));

        if (strcmp($res, "VERIFIED") == 0) {

//            $item_name = $this->input->post('item_name');
            $item_number = $this->input->post('item_number');
            $payment_status = $this->input->post('payment_status');
            $payment_amount = $this->input->post('mc_gross');
            $payment_currency = $this->input->post('mc_currency');
            $payment_date = $this->input->post('payment_date');
            $txn_id = $this->input->post('txn_id');
            $business = $this->input->post('business');

            //下面一句为测试
//            $this->order_model->ipn($data = array('msg' => json_encode($_POST)));
            if (($business === PAYPAL_BUSINESS) and ( $payment_currency === $this->page['currency_payment'])) {
                if ($payment_status == 'Completed') {//：付款已完成，资金已成功增加到您的账户余额中
                    $orderCreateSuccess['OrderInfo'] = $this->order_model->getInfoByNumber($item_number); //获取订单信息
                    if ($orderCreateSuccess['OrderInfo']) {
                        if ($orderCreateSuccess['OrderInfo']['pay_status'] == 0 && $orderCreateSuccess['OrderInfo']['payment_amount'] == $payment_amount * 100 && $payment_currency == $this->page['currency_payment']) {//未付款
                            $this->load->model('ordership_model');
                            $orderCreateSuccess['ShipInfo'] = $this->ordership_model->getInfoById($this->page['country'], $item_number);
                            $this->load->model('orderbill_model');
                            $orderCreateSuccess['BillInfo'] = $this->orderbill_model->getInfoById($this->page['country'], $item_number);
                            $this->load->model('orderdetails_model');
                            $orderCreateSuccess['OrderDetailsInfo'] = $this->orderdetails_model->listByOrderNumber($this->page['country'], $item_number);
                            $this->load->model('website_model');
                            $this->website_model->purchased($this->page['country']);
                            $this->order_model->payment($this->page['country'], $orderCreateSuccess['OrderInfo']['member_id'], $orderCreateSuccess['OrderInfo']['member_email'], $orderCreateSuccess['OrderInfo']['coupons_id'], $item_number, $payment_amount * 100, $txn_id, strtotime($payment_date));
                            $this->orderEmail($orderCreateSuccess['OrderInfo']['member_email'], time(), $this->page ['currency'], $orderCreateSuccess['OrderInfo'], $orderCreateSuccess['OrderDetailsInfo'], $orderCreateSuccess['ShipInfo'], $orderCreateSuccess['BillInfo']); //发生订单信息邮件给客户
                        }
                    }
                }
            }
            if (DEBUG == true) {
                error_log(date('[Y-m-d H:i e] ') . "Verified IPN: $req " . PHP_EOL, 3, LOG_FILE);
            }
        } else if (strcmp($res, "INVALID") == 0) {
            // log for manual investigation
            // Add business logic here which deals with invalid IPN messages
            if (DEBUG == true) {
                error_log(date('[Y-m-d H:i e] ') . "Invalid IPN: $req" . PHP_EOL, 3, LOG_FILE);
            }
        }
    }

    /*     * ***********************************************
     *      各支付方式通用方法
     * ************************************************ */

    //获取Cookie购物车
    function _getCookieCart($orderNumber, $freightAmount, $couponID) {
        $this->load->helper('cookie');
        $arr = $this->input->cookie('cart');
        $products = unserialize($arr);
        $this->load->model('product_model');
        // 获取产品信息并且组装
        $details = $this->product_model->cartPro($this->country, $products);
        $orderInfo = $this->_constructOrder($details, $orderNumber);

        if ($orderInfo) {
            // 支付总额 订单总价+运费
            $orderInfo ['freight_amount'] = $freightAmount;
            if ($couponID == 'DISCOUNT') {
                $orderInfo ['offers_amount'] = $this->getDiscount($details);
                $orderInfo ['coupons_id'] = 'DISCOUNT';
            } else {
                $orderInfo ['offers_amount'] = 0;
                $orderInfo ['coupons_id'] = 0;
            }

            return $orderInfo;
        } else {
            return FALSE;
        }
    }

    //获取MongoDB购物车
    function _getMongodbCart($orderNumber, $freightAmount, $actionEmail, $couponID) {

        // 获取购物车表里的信息
        $this->load->model('cart_model');
        $products = $this->cart_model->getCart($this->country, $actionEmail);

        // 获取产品信息并且组装
        $this->load->model('product_model');
        $details = $this->product_model->cartPro($this->country, $products ['info']);
        $orderInfo = $this->_constructOrder($details, $orderNumber);
        if (!$orderInfo) {
            return FALSE;
        }
        // 验证是否已经登录
        $key = $this->config->item('encryption_key');
        $auth = $this->session->userdata('auth');
        if ($auth == md5($key . $actionEmail)) {
            if ($couponID) {
                if ($couponID == 'DISCOUNT') {
                    $offers_amount = $this->getDiscount($details);
                } else {
                    $couponInfo = $this->_coupons($this->country, $couponID, $actionEmail, $orderInfo['order_amount'], $orderInfo['couponPriceList'], $orderInfo['products']);
                    if (!$couponInfo) { // 优惠券不存在
                        return 'CouponERR';
                    }

                    if ($couponInfo ['type'] == 3) {
                        $freightAmount = 0;
                        $offers_amount = 0;
                    } else {
                        $offers_amount = $couponInfo ['offers_amount'];
                    }
                }
            } else {
                $couponID = 0;
                $offers_amount = 0;
            }
        } else {
            $couponID = 0;
            $offers_amount = 0;
        }
        $orderInfo ['freight_amount'] = $freightAmount;
        $orderInfo ['offers_amount'] = $offers_amount;
        $orderInfo ['coupons_id'] = $couponID;
        return $orderInfo;
    }

    
       //根据购物车算折扣
    private function getDiscount($products) {
        $collection_offer = 0;
        $this->load->model('countdown_model');
        $this->load->model('collection_model');
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
        return $value ? ($discountInfo['type'] == 1 ? $value : round($currValue * $value / 100, 2)) * 100 : 0;
    }
    
    function _constructOrder($details, $orderNumber) {
        if ($details) {
            $orderInfo = array(
                'order_weight' => 0,
                'order_quantity' => 0,
                'order_amount' => 0
            );
            // 判断是否为倒计时 (ps: 绑定商品不享受倒计时)
            $this->load->model('countdown_model');
            $this->load->model('product_model');
            $productList = array();
            $index = 0;
//            $haveTotal = [];
            foreach ($details as $key => $product) {
                $countdown_id = $this->countdown_model->getInfoByProductId($this->country, $product ['product_id']);
//                $haveTotal = $this->product_model->checkOutTotal($this->country, $product['product_id'], $haveTotal); //统计当天产品ChectOut次数
                if ($countdown_id) {
                    $countdownInfo = $this->countdown_model->getInfoById($countdown_id);
                    if (is_array($countdownInfo) && $countdownInfo ['status'] == 2) {
                        $product ['product_price'] = $this->countdown_model->getPrice($countdown_id, $product ['product_price']); //倒计时后的单套价格
                    }
                }
                $product['product_price'] = $product['product_price'] - $product['plural_price'];
                switch ($product['bundle_type']) {
                    case 1:
                        $productList[$index] = array(
                            'order_number' => $orderNumber,
                            'product_id' => $product['product_id'],
                            'product_name' => $product['product_title'],
                            'product_sku' => $product['product_dsku'],
                            'product_attr' => $product['product_attr'],
                            'payment_price' => $product['product_price'],
                            'product_quantity' => $product['product_qty'],
                            'payment_amount' => $product['product_price'] * $product['product_qty'],
                            'bundle_skus' => $product['product_dsku'],
                            'total_qty' => $product['product_qty'],
                            'bundle_type' => $product['bundle_type'],
                            'comments_star' => 0
                        );
                        $orderInfo['order_weight']+=$product['product_weight'];
                        $orderInfo['order_quantity']+=$product['product_qty'];
                        $orderInfo['order_amount']+=$productList[$index]['payment_amount'];
                        ++$index;
                        break;
                    case 2:
                        $productList[$index] = array(
                            'order_number' => $orderNumber,
                            'product_id' => $product['product_id'],
                            'product_name' => $product['product_title'],
                            'product_sku' => strtok($product['product_dsku'], ','),
                            'product_attr' => $product['product_attr'],
                            'payment_price' => $product['product_price'],
                            'product_quantity' => $product['product_qty'],
                            'payment_amount' => $product['product_price'] * $product['product_qty'],
                            'bundle_skus' => $product['product_dsku'],
                            'total_qty' => $product['product_qty'] * strtok(','),
                            'bundle_type' => $product['bundle_type'],
                            'comments_star' => 0
                        );
                        $orderInfo['order_weight']+=$product['product_weight'] * $product['product_qty'];
                        $orderInfo['order_quantity']+=$productList[$index]['total_qty'];
                        $orderInfo['order_amount']+=$productList[$index]['payment_amount'];
                        ++$index;
                        break;
                    case 3:
                        $productQuantity = substr_count($product['product_dsku'], ',') + 1;
                        $productList[$index] = array(
                            'order_number' => $orderNumber,
                            'product_id' => $product['product_id'],
                            'product_name' => $product['product_title'],
                            'product_sku' => $product['product_dsku'],
                            'product_attr' => $product['product_attr'],
                            'payment_price' => $product['product_price'],
                            'product_quantity' => $product['product_qty'],
                            'payment_amount' => $product['product_price'] * $product['product_qty'],
                            'bundle_skus' => $product['product_dsku'],
                            'total_qty' => $product['product_qty'] * $productQuantity,
                            'bundle_type' => $product['bundle_type'],
                            'comments_star' => 0
                        );
                        $orderInfo['order_amount']+=$productList[$index]['payment_amount'];
                        $orderInfo['order_weight']+=$product['product_weight'] * $product['product_qty'];
                        $orderInfo['order_quantity']+=$product['product_qty'] * $productQuantity;
                        ++$index;

                        /*
                         * 拆分成两条
                          $productsSkuList = explode(",", $product['product_dsku']);
                          $productsAttrList = explode(",", $product['product_attr']);
                          foreach ($productsSkuList as $indexSku => $productSku) {
                          $productList[$index] = array(
                          'order_number' => $orderNumber,
                          'product_id' => $product['product_id'],
                          'product_name' => $product['product_title'],
                          'product_sku' => $productSku,
                          'product_attr' => $productsAttrList[$indexSku],
                          'payment_price' => $product['product_price'],
                          'product_quantity' => $product['product_qty'],
                          'payment_amount' => $indexSku == 0 ? $product['product_price'] * $product['product_qty'] : 0,
                          'bundle_skus' => $product['product_dsku'],
                          'total_qty' => $product['product_qty'],
                          'bundle_type' => $product['bundle_type'],
                          'comments_star' => 0
                          );
                          if ($indexSku == 0) {
                          $orderInfo['payment_amount']+=$productList[$index]['payment_amount'];
                          }
                          ++$index;
                          }
                          $orderInfo['order_weight']+=$product['product_weight'] * $product['product_qty'];
                          $orderInfo['order_quantity']+=$product['product_qty'] * count($productsSkuList);
                         */
                        break;
                    default:
                        break;
                }
                //加入产品pay次数
                $redisKey = 'T:' . $this->page['datePRC'] . ':' . $this->page ['country'] . ':' . $product['product_id'];
                $this->redis->hashInc($redisKey, 'pay', 1);
                $this->redis->timeOut($redisKey, 259200);

                $orderInfo['products'] = $productList;
                $orderInfo['couponPriceList'][] = $product['product_price'];
            }

            return $orderInfo;
        } else {
            return false;
        }
    }

    //获取收货地址
    //return 字段见 US_member_receive 表
    function _getAddress($logIn, $country_code, $actionID, $addressID) {
        if ($addressID) {
            if ($logIn) {
                $this->load->model('memberReceive_model');
                $fields = 'member_id,receive_firstName,receive_lastName,receive_company,receive_country,receive_province,receive_city,receive_add1,receive_add2,receive_zipcode,receive_phone';
                $addressInfo = $this->memberReceive_model->getInfoById($country_code, $actionID, $addressID, $fields);
                if ($addressInfo) {
                    unset($addressInfo['member_id']);
                }
                return $addressInfo;
            } else {
                return FALSE;
            }
        } else {
            $this->load->library('form_validation');

            $this->form_validation->set_rules('firstname', 'firstname', "required");
            $this->form_validation->set_rules('lastname', 'lastname', 'required');
            $this->form_validation->set_rules('address', 'address', 'required');
            $this->form_validation->set_rules('suburb', 'suburb', 'required');
            $this->form_validation->set_rules('postcode', 'postcode', 'required');
            $this->form_validation->set_rules('emailaddress', 'emailaddress', 'required|valid_email');

            if ($this->form_validation->run() == FALSE) {
                return FALSE;
            }
            $addressInfo ['receive_firstName'] = $this->input->post('firstname', TRUE) ? $this->input->post('firstname', TRUE) : '';
            $addressInfo ['receive_lastName'] = $this->input->post('lastname', TRUE) ? $this->input->post('lastname', TRUE) : '';

            $addressInfo ['receive_company'] = $this->input->post('company', TRUE) ? $this->input->post('company', TRUE) : '';
            $addressInfo ['receive_country'] = $this->page['countryList'][$country_code]['name']; //$this->page['countryNames'][$this->page['countryList'][$this->page['country']]]; //$this->input->post('couniry', TRUE);
            $addressInfo ['receive_province'] = $this->input->post('state', TRUE) ? $this->input->post('state', TRUE) : '';
            $addressInfo ['receive_city'] = $this->input->post('suburb', TRUE) ? $this->input->post('suburb', TRUE) : '';
            $addressInfo ['receive_add1'] = $this->input->post('address', TRUE) ? $this->input->post('address', TRUE) : '';
            $addressInfo ['receive_add2'] = $this->input->post('apt', TRUE) ? $this->input->post('apt', TRUE) : '';
            $addressInfo ['receive_zipcode'] = $this->input->post('postcode', TRUE) ? $this->input->post('postcode', TRUE) : '';
            $addressInfo ['receive_phone'] = $this->input->post('phone', TRUE) ? $this->input->post('phone', TRUE) : '';
            return $addressInfo;
        }
    }

    //获取帐单地址
    function _getBillAddress($logIn, $country_code, $actionID, $addressID) {
        if ($addressID) {
            if ($logIn) {
                $this->load->model('memberReceive_model');
                $fields = 'member_id,receive_firstName,receive_lastName,receive_company,receive_country,receive_province,receive_city,receive_add1,receive_add2,receive_zipcode,receive_phone';
                $addressInfo = $this->memberReceive_model->getBillInfoById($country_code, $actionID, $addressID, $fields);
                if ($addressInfo) {
                    unset($addressInfo['member_id']);
                }
                return $addressInfo;
            } else {
                return FALSE;
            }
        } else {
            $this->load->library('form_validation');

            $this->form_validation->set_rules('bill_firstname', 'firstname', "required");
            $this->form_validation->set_rules('bill_lastname', 'lastname', 'required');
            $this->form_validation->set_rules('bill_address', 'address', 'required');
            $this->form_validation->set_rules('bill_suburb', 'suburb', 'required');
            $this->form_validation->set_rules('bill_postcode', 'postcode', 'required');


            if ($this->form_validation->run() == FALSE) {
                return FALSE;
            }
            $addressInfo ['receive_firstName'] = $this->input->post('bill_firstname', TRUE) ? $this->input->post('bill_firstname', TRUE) : '';
            $addressInfo ['receive_lastName'] = $this->input->post('bill_lastname', TRUE) ? $this->input->post('bill_lastname', TRUE) : '';

            $addressInfo ['receive_company'] = $this->input->post('bill_company', TRUE) ? $this->input->post('bill_company', TRUE) : '';
            $addressInfo ['receive_country'] = $this->page['countryList'][$country_code]['name']; //$this->page['countryNames'][$this->page['countryList'][$this->page['country']]]; //$this->input->post('couniry', TRUE);
            $addressInfo ['receive_province'] = $this->input->post('bill_state', TRUE) ? $this->input->post('bill_state', TRUE) : '';
            $addressInfo ['receive_city'] = $this->input->post('bill_suburb', TRUE) ? $this->input->post('bill_suburb', TRUE) : '';
            $addressInfo ['receive_add1'] = $this->input->post('bill_address', TRUE) ? $this->input->post('bill_address', TRUE) : '';
            $addressInfo ['receive_add2'] = $this->input->post('bill_apt', TRUE) ? $this->input->post('bill_apt', TRUE) : '';
            $addressInfo ['receive_zipcode'] = $this->input->post('bill_postcode', TRUE) ? $this->input->post('bill_postcode', TRUE) : '';
            $addressInfo ['receive_phone'] = $this->input->post('bill_phone', TRUE) ? $this->input->post('bill_phone', TRUE) : '';
            return $addressInfo;
        }
    }

    //返回$actionEmail对应的member_id;
    function _register($country_code, $actionEmail, $member_name) {// 注册用户
        if (!filter_var($actionEmail, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        $this->load->model('member_model');
        $member = $this->member_model->insert($country_code, $actionEmail, 0, true);
        if ($member ['member_id']) {
            if (array_key_exists('member_pwd', $member)) {// 发送用户注册成功邮件
                $this->registeredEmail($actionEmail, $member['member_pwd'], $member_name);
            }
            return $member;
        } else {
            return false; //redirect("home/registered_error");
        }
    }

    // 获取运费
    private function _getFreightAmount($shipping_id) {
        $this->load->model('shipping_model');
        return $this->shipping_model->getShipById($shipping_id);
    }

    // 使用优惠券
    // 参数：
    // 国家代码：$country_code,
    // 优惠券代码：$coupon_id,
    // 帐号：$member_email,
    // 订单金额：$order_total,
    // 产品单价列表：$arr_productPrice
    // 返回：
    // 优惠金额：$offers_amount
    private function _coupons($country_code, $coupon_id, $member_email, $order_total, $arr_productPrice, $product_details) {

        if (!preg_match("/^[0-9a-zA-z]{6,20}+$/i", $coupon_id)) {
            return FALSE;
        }
        $this->load->model('coupons_model');
        $coupons = $this->coupons_model->checkCouponsId($country_code, $coupon_id, $member_email, $order_total, $arr_productPrice);

        if ($coupons ['success']) {

            // 优惠券类型（1-减金额，2-减百分比，3-免运费
            $offers_amount = 0;
            switch ($coupons ['couponInfo'] ['type']) {
                case 1 : // 减金额
                    if ($coupons ['couponInfo'] ['condition'] == 1) {
                        $offers_amount = $coupons ['couponInfo'] ['amount'];
                    } else if ($coupons ['couponInfo'] ['condition'] == 2) {
                        // 判断订单总价是否符合条件
                        if ($order_total >= $coupons ['couponInfo'] ['min'] && $order_total <= $coupons ['couponInfo'] ['max']) {
                            $offers_amount = $coupons ['couponInfo'] ['amount'];
                        }
                    } else {
                        foreach ($product_details as $detail) {
                            $offers = 0;
                            if ($detail ['payment_price'] >= $coupons ['couponInfo'] ['min'] && $detail ['payment_price'] <= $coupons ['couponInfo'] ['max']) {
                                $offers = $coupons ['couponInfo'] ['amount'] * $detail ['product_quantity'];
                                $offers_amount += $offers;
                            }
                        }
                    }
                    break;
                case 2 : // 减百分比
                    if ($coupons ['couponInfo'] ['condition'] == 1) {//所有订单
                        $offers_amount = round($order_total * ($coupons ['couponInfo'] ['amount'] / 100), 0);
                    } else if ($coupons ['couponInfo'] ['condition'] == 2) {
                        // 判断订单总价是否符合条件
                        if ($order_total >= $coupons ['couponInfo'] ['min'] && $order_total <= $coupons ['couponInfo'] ['max']) {
                            $offers_amount = round($order_total * ($coupons ['couponInfo'] ['amount'] / 100), 0);
                        }
                    } else {
                        foreach ($product_details as $detail) {
                            $offers = 0;
                            if ($detail ['payment_price'] >= $coupons ['couponInfo'] ['min'] && $detail ['payment_price'] <= $coupons ['couponInfo'] ['max']) {
                                $offers = round($detail ['payment_price'] * ($coupons ['couponInfo'] ['amount'] / 100), 0) * $detail ['product_quantity'];
                                $offers_amount += $offers;
                            }
                        }
                    }
                    break;
                case 3 : // 免运费
                    if ($coupons ['couponInfo'] ['condition'] == 1) {
                        $offers_amount = 0;
                    } else if ($coupons ['couponInfo'] ['condition'] == 2) {
                        // 判断订单总价是否符合条件
                        if ($order_total >= $coupons ['couponInfo'] ['min'] && $order_total <= $coupons ['couponInfo'] ['max']) {
                            $offers_amount = 0;
                        }
                    } else {
                        foreach ($arr_productPrice as $c_price) {
                            if ($c_price >= $coupons ['couponInfo'] ['min'] && $c_price <= $coupons ['couponInfo'] ['max']) {
                                $offers_amount = 0;
                                break;
                            }
                        }
                    }

                    break;
                default :
                    break;
            }
            return array(
                'offers_amount' => $offers_amount,
                'type' => $coupons ['couponInfo'] ['type']
            );
        } else {
            return FALSE;
        }
    }

    private function _coupons2($country_code, $coupon_id, $member_email, $order_total, $arr_productPrice, $product_details) {
        if (!preg_match("/^[0-9a-zA-z]{6,10}+$/i", $coupon_id)) {
            return FALSE;
        }
        $this->load->model('coupons_model');
        $coupons = $this->coupons_model->checkCouponsId($country_code, $coupon_id, $member_email, $order_total, $arr_productPrice);
        if ($coupons ['success']) {

            // 优惠券类型（1-减金额，2-减百分比，3-免运费
            $offers_amount = 0;
            switch ($coupons ['couponInfo'] ['type']) {
                case 1 : // 减金额
                    if ($coupons ['couponInfo'] ['condition'] == 1) {
                        $offers_amount = $coupons ['couponInfo'] ['amount'];
                    } else if ($coupons ['couponInfo'] ['condition'] == 2) {
                        // 判断订单总价是否符合条件
                        if ($order_total >= $coupons ['couponInfo'] ['min'] && $order_total <= $coupons ['couponInfo'] ['max']) {
                            $offers_amount = $coupons ['couponInfo'] ['amount'];
                        }
                    } else {
                        foreach ($product_details as $detail) {
                            $offers = 0;
                            if ($detail ['payment_price'] >= $coupons ['couponInfo'] ['min'] && $detail ['payment_price'] <= $coupons ['couponInfo'] ['max']) {
                                $offers = $coupons ['couponInfo'] ['amount'] * $detail ['product_quantity'];
                                $offers_amount += $offers;
                            }
                        }
                    }
                    break;
                case 2 : // 减百分比
                    if ($coupons ['couponInfo'] ['condition'] == 1) {
                        $offers_amount = $order_total * ($coupons ['couponInfo'] ['amount'] / 100);
                    } else if ($coupons ['couponInfo'] ['condition'] == 2) {
                        // 判断订单总价是否符合条件
                        if ($order_total >= $coupons ['couponInfo'] ['min'] && $order_total <= $coupons ['couponInfo'] ['max']) {
                            $offers_amount = $order_total * ($coupons ['couponInfo'] ['amount'] / 100);
                        }
                    } else {
                        foreach ($product_details as $detail) {
                            $offers = 0;
                            if ($detail ['payment_price'] >= $coupons ['couponInfo'] ['min'] && $detail ['payment_price'] <= $coupons ['couponInfo'] ['max']) {
                                $offers = round($detail ['payment_price'] / 100 * ($coupons ['couponInfo'] ['amount'] / 100), 2) * $detail ['product_quantity'];
                                $offers_amount += $offers;
                            }
                        }
                    }
                    break;
                case 3 : // 免运费
                    if ($coupons ['couponInfo'] ['condition'] == 1) {
                        $offers_amount = 0;
                    } else if ($coupons ['couponInfo'] ['condition'] == 2) {
                        // 判断订单总价是否符合条件
                        if ($order_total >= $coupons ['couponInfo'] ['min'] && $order_total <= $coupons ['couponInfo'] ['max']) {
                            $offers_amount = 0;
                        }
                    } else {
                        foreach ($arr_productPrice as $c_price) {
                            if ($c_price >= $coupons ['couponInfo'] ['min'] && $c_price <= $coupons ['couponInfo'] ['max']) {
                                $offers_amount = 0;
                                break;
                            }
                        }
                    }

                    break;
                default :
                    break;
            }
            return array(
                'offers_amount' => $offers_amount * 100,
                'type' => $coupons ['couponInfo'] ['type']
            );
        } else {
            return FALSE;
        }
    }

    /**
     * 注册成功发送邮件
     * @param 用户邮件 $member_email
     * @param 用户密码 $member_pwd
     * 
     */
    private function registeredEmail($member_email, $member_pwd, $member_name = "") {
        $this->session->set_userdata('is_newUser', '1');
        if ($member_pwd > 1) {
            $invalid_time = 5184000; //当前时间+60天
            $this->load->helper('encryption');
            $forget_time = time();
            $forget_salt = createSalt();
            $forget_email_md5 = encryption($member_email, $forget_salt);
            $this->load->model('memberforget_model');
            $forget_id = $this->memberforget_model->insert($this->page['country'], $forget_email_md5, $forget_salt, $forget_time, $forget_time + $invalid_time, 2, 0);
            if ($forget_id) {
                $forget_link = '/forget/replace/' . $forget_id . '/' . $forget_email_md5 . '/' . $member_email;
                $emailData = array(
                    'shopurl' => $this->page ['domain'],
                    'shopmail' => $this->page['service_mail'],
                    'to' => $member_email,
                    'account' => $member_email,
                    'password' => $member_pwd,
                    'reseturl' => $this->page ['domain'] . $forget_link,
                    'name' => $member_name ? $member_name : "member"
                );

                $this->load->model('mail_model');
                $this->mail_model->created($emailData);
            }
        }
    }

    /**
     * 订单成功发送邮件
     * @param 用户邮件 $member_email
     * @param 用户密码 $member_pwd
     */
    private function orderEmail($member_email, $time, $currency, $insert_orderData, $insert_detailsData, $insert_shipData, $insert_billData) {
        $this->load->model('product_model');
        //查找销量最好的20个产品中5随机5个
        $_product = $this->product_model->getRcommendByAll($this->country);
        //组装购买的产品图片和产品路径
        foreach ($insert_detailsData as $key => $detailsData) {
            $pics = $this->product_model->orderPics($this->country, $detailsData['product_id']);
            $insert_detailsData[$key]['freebies'] = $pics['freebies'];
            $insert_detailsData[$key]['image'] = $pics['image'];
            $insert_detailsData[$key]['seo_url'] = $pics['seo_url'];
        }
        //生成优惠券
//        $this->load->model('coupons_model');
//        $couponsInfo = $this->coupons_model->autoGet($this->country, $member_email);

        $emailData = array(
            'shopurl' => $this->page ['domain'],
            'shopmail' => $this->page['service_mail'],
            'to' => $member_email,
            'time' => $time,
            'currency' => $currency,
            'name' => $insert_orderData['member_name'],
            'insert_orderData' => $insert_orderData,
            'insert_detailsData' => $insert_detailsData,
            'insert_shipData' => $insert_shipData,
            'insert_billData' => $insert_billData,
            'goodproduct' => RandProduct($_product, 0, 19, 5)
//            'couponsInfo' => $couponsInfo
        );

        $this->load->model('mail_model');
        return $this->mail_model->orderconfirmation($emailData, $this->country);
    }

    function success() {
        if ($this->session->userdata('orderEmail')) {
            $orderCreateSuccess = $this->session->userdata('orderCreateSuccess');
            $this->page['title'] = 'Thank you';
            $headView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'head');
            $this->page['head'] = $this->load->view($headView, $this->page, true);
            $footView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'foot');
            $this->page['foot'] = $this->load->view($footView, $this->page, true);
//            $this->page['shoppingcart'] = $this->load->view('shoppingcart', $this->page, true);
//            if (isset($_SESSION['is_newUser'])) {
//                $this->page['successMessage'] = 'A confirmation email and account activation email have been sent to ' . $this->session->userdata('orderEmail');
//            } else {
//                $this->page['successMessage'] = 'A confirmation email has been sent to ' . $this->session->userdata('orderEmail');
//            }
            $this->page['successMessage'] = $this->session->userdata('orderEmail');
            $this->page['order_id'] = $this->session->userdata('orderNumber');

            $this->load->model('product_model');
            $product_ids = $orderCreateSuccess['OrderDetailsInfo'];
            $ga_addItem = '';
            $itemTmp = '';
            foreach ($product_ids as $productInfo) {
                $ga_addItem = $ga_addItem . "{'id': '{$this->page['order_id']}','name': '{$productInfo['product_name']}','sku': '" . strtok($productInfo['product_sku'], '/') . "', 'price': '" . ($productInfo['payment_price'] / 100) . "','quantity': '{$productInfo['product_quantity']}'},";
                $itemTmp.='{ id: "' . strtok($productInfo['product_sku'], '/') . '",price:' . ($productInfo['payment_price'] / 100) . ',quantity:' . $productInfo['product_quantity'] . '},';
            }
            $this->page['ga_addItem'] = substr($ga_addItem, 0, strlen($ga_addItem) - 1);
            $this->page['ga_addTransaction'] = "'id': '{$this->page['order_id']}','affiliation': 'drgrab', 'revenue': '" . $orderCreateSuccess['OrderInfo']['payment_amount'] / 100 . "', 'shipping': '" . $orderCreateSuccess['OrderInfo']['freight_amount'] / 100 . "', 'currency': '{$this->page['currency_payment']}'";


            if ($this->page['country'] == 'AU') {
//                $itemTmp = '';
//                $this->load->model('orderdetails_model');
//                //$product_ids = $this->orderdetails_model->listByOrderNumber($this->country, $this->page['order_id'], 'product_sku,payment_price,product_quantity');
//                $product_ids = $orderCreateSuccess['OrderDetailsInfo'];
//                foreach ($product_ids as $productInfo) {
//                    $itemTmp.='{ id: "' . strtok($productInfo['product_sku'], '/') . '",price:' . ($productInfo['payment_price'] / 100) . ',quantity:' . $productInfo['product_quantity'] . '},';
//                }
                $itemTmp = substr($itemTmp, 0, strlen($itemTmp) - 1);
                $this->page['countrySEO'] = '<script type="text/javascript" src="//static.criteo.net/js/ld/ld.js" async="true"></script>
                                             <script type="text/javascript">
                                             window.criteo_q = window.criteo_q || [];
                                             window.criteo_q.push(
                                             { event: "setAccount", account: 22926 },
                                              { event: "setEmail", email: "' . $this->session->userdata('orderEmail') . '" },
                                             { event: "setSiteType", type: "m" },
                                             { event: "trackTransaction", id: "' . $this->page['order_id'] . '", item: [' . $itemTmp . '
                                             ]}
                                             );
                                             </script>';
            }
            //$this->page['orders'] = $this->order_model->getInfoByNumber($this->country, $this->page['order_id'], 'create_time,estimated_time,payment_amount');
            $this->page['orders'] = $orderCreateSuccess['OrderInfo'];
            $_product = $this->product_model->getRcommendByAll($this->country);
            $this->page['product'] = RandProduct($_product);
            $this->session->unset_userdata('orderCreateSuccess');
            $this->session->unset_userdata('orderNumber');
            $this->session->unset_userdata('orderEmail');

            $thankyouView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'thankyou');
            $this->load->view($thankyouView, $this->page);
        } else {
            redirect("home"); //订单生成失败
        }
    }

    /*     * ************************************
     * Braintree信用卡支付
     * ************************************ */

    function _braintreeCreateOrder($actionID, $actionEmail, $actionName, $orderNumber, $cartOrderInfo, $orderGuestbook, $addressInfo, $shippingInfo) {
        $create_time = time();
        $insertBillInfo = $addressInfo;
        $insertBillInfo['order_number'] = $orderNumber;

        $insertShipInfo = $insertBillInfo;
        $insertShipInfo['express_type'] = $shippingInfo['name'];
        $insertOrderDetailsInfo = $cartOrderInfo['products'];
        $insertOrderAppendInfo = array(
            'order_number' => $orderNumber,
            'order_guestbook' => $orderGuestbook,
            'landing_page' => $this->session->userdata('landing_page'),
            'refer_site' => $this->session->userdata('refer_site'),
            'order_weight' => $cartOrderInfo['order_weight']
        );

        $this->load->helper('common');
        $insertOrderInfo = array(
            'order_number' => $orderNumber,
            'member_id' => $actionID,
            'member_email' => $actionEmail,
            'member_name' => $actionName,
            'order_quantity' => $cartOrderInfo['order_quantity'],
            'order_insurance' => $cartOrderInfo['order_insurance'],
            'order_giftbox' => $cartOrderInfo['order_giftbox'],
            'order_amount' => $cartOrderInfo['payment_amount'] - $cartOrderInfo['freight_amount'],
            'payment_amount' => $cartOrderInfo['payment_amount'],
            'offers_amount' => $cartOrderInfo['offers_amount'],
            'coupons_id' => $cartOrderInfo['coupons_id'],
            'freight_amount' => $cartOrderInfo['freight_amount'],
            'receive_name' => $addressInfo['receive_firstName'] . ' ' . $addressInfo['receive_lastName'],
            'create_time' => $create_time,
            'send_status' => 0,
            'is_resend' => 1,
            'pay_status' => 0,
            'doc_status' => 1,
            'update_time' => $create_time,
            'pay_type' => 2,
            'estimated_time' => $shippingInfo['estimated_time'],
            'transaction_id' => 0,
            'operator' => '', 'ip_address' => getIP()
        );
        // 掉用方法进行订单添加 同时对5张表进行
        $order_id = $this->order_model->addOrder($this->country, $insertOrderInfo, $insertOrderAppendInfo, $insertBillInfo, $insertShipInfo, $insertOrderDetailsInfo);
        if ($order_id) {
            return array('OrderInfo' => $insertOrderInfo, 'OrderDetailsInfo' => $insertOrderDetailsInfo, 'OrderShipInfo' => $insertShipInfo, 'OrderBillInfo' => $insertBillInfo);
        } else {
            return false;
        }
    }

    function _braintreePayment($logIn, $actionID, $actionEmail, $orderInfo, $address_id, $creditCardInfo) {
        $this->load->library("braintree_lib");
        $result = Braintree_Transaction::sale([
                    'orderId' => $orderInfo['OrderInfo']['order_number'],
                    'amount' => round($orderInfo['OrderInfo']['payment_amount'] / 100, 2),
                    'merchantAccountId' => 'drgrab' . $this->page['currency_payment'],
                    'creditCard' => $creditCardInfo,
                    'options' => [
                        'submitForSettlement' => True
                    ]
        ]);
        if ($result->success) {
            $create_time = time();
            $transaction_id = $result->transaction->id;
            $this->order_model->payment($this->page['country'], $actionID, $actionEmail, $orderInfo['OrderInfo']['coupons_id'], $orderInfo['OrderInfo']['order_number'], $orderInfo['OrderInfo']['payment_amount'], $transaction_id, $create_time, $address_id);
            // 删除购物车表里的信息
            if ($logIn) {
                $this->load->model('cart_model');
                $this->cart_model->delCart($this->country, $actionEmail, 1, 1, 2);
            } else {
                $this->load->helper('cookie');
                delete_cookie('cart');
            }
            //发生订单信息邮件给客户
            $this->orderEmail($actionEmail, $create_time, $this->page ['currency'], $orderInfo['OrderInfo'], $orderInfo['OrderDetailsInfo'], $orderInfo['OrderShipInfo'], $orderInfo['OrderBillInfo']);
            if ($logIn && $this->session->userdata('member_name') == '') {
                $this->session->set_userdata('member_name', $orderInfo['OrderInfo']['member_name']);
            }
            $this->session->set_userdata('orderNumber', $orderInfo['OrderInfo']['order_number']);
            $this->session->set_userdata('orderEmail', $orderInfo['OrderInfo']['member_email']);
            redirect("order/success");
        } else {
            if (!$address_id) {
                $orderInfo['OrderShipInfo']['receive_eamil'] = $actionEmail;
                $this->session->set_userdata('cartInputAddress', $orderInfo['OrderShipInfo']);
            }
            redirect("home/showError/P1100");
        }
    }

    //信用卡风险评估  加入风险队列表中
    /**
     *
     * @param unknown $country     国家
     * @param unknown $orderInfo   订单信息
     * @param unknown $orderAddress  订单地址
     * @param number $status   支付方式 1信用卡  2Paypal
     * @param number $creditCardNumber  信用卡号
     */
    function _risk($country, $orderInfo, $orderAddress, $status = 1, $creditCardNumber = 0) {
        $withDevice = array(
            'ip_address' => $orderInfo['ip_address'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'accept_language' => $_SERVER['HTTP_ACCEPT_LANGUAGE'],
        );


        $withEmail = array(
            'address' => $orderInfo['member_email'],
            'domain' => substr(strstr($orderInfo['member_email'], "@"), 1),
        );


        $withAddress = array(
            'first_name' => $orderAddress['receive_firstName'] ? $orderAddress['receive_firstName'] : '',
            'last_name' => $orderAddress['receive_lastName'] ? $orderAddress['receive_lastName'] : '',
            'company' => $orderAddress['receive_company'] ? $orderAddress['receive_company'] : '',
            'address' => $orderAddress['receive_add1'] ? $orderAddress['receive_add1'] : '',
            'address_2' => $orderAddress['receive_add2'] ? $orderAddress['receive_add2'] : '',
            'city' => $orderAddress['receive_city'] ? $orderAddress['receive_city'] : '',
            'region' => '',
            'country' => $country,
            'postal' => $orderAddress['receive_zipcode'] ? $orderAddress['receive_zipcode'] : '',
            'phone_number' => $orderAddress['receive_phone'] ? $orderAddress['receive_phone'] : '',
            'phone_country_code' => '',
        );


        if ($status == 1) {
            $withCreditCard = array(
                'issuer_id_number' => substr($creditCardNumber, 0, 6),
                'last_4_digits' => substr($creditCardNumber, -4),
                'bank_name' => '',
                'bank_phone_country_code' => '',
                'bank_phone_number' => '',
                'avs_result' => 'Y',
                'cvv_result' => 'Y',
            );
        } else {
            $withCreditCard = 0;
        }


        $data = array(
            'order_number' => $orderInfo['order_number'],
            'payType' => $status,
            'withDevice' => json_encode($withDevice),
            'withEmail' => json_encode($withEmail),
            'withAddress' => json_encode($withAddress),
            'withCreditCard' => $withCreditCard ? json_encode($withCreditCard) : 0,
            'status' => 0
        );


        return $this->order_model->addRiskQueue($this->country, $data);
    }

}
