<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class api extends Pc_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('api_model');
    }

    // 获取订单信息 get
    public function order() {
        // 对比apikey
        $apikey = $this->input->get('apikey', TRUE);
        if ($apikey != 'pandacheer') {
            $this->error('Missing apikey');
        }

        $country = strtoupper($this->input->get('country', TRUE));
        if ($country) {
            if (!$countryInfo = $this->api_model->getCountryInfo($country)) {
                $this->error('error  country');
            }
        } else {
            $this->error('Missing country');
        }

        // 组装条件参数 start
        $ids = $this->input->get('ids', TRUE);

        $limit = $this->input->get('limit', TRUE);
        if (!$limit) {
            $limit = 50;
        }

        $page = $this->input->get('page', TRUE);
        if (!$page) {
            $page = 0;
        }

        $since_id = $this->input->get('since_id', TRUE);

        $created_at_min = $this->input->get('created_at_min', TRUE);
        if ($created_at_min) {
            $created_at_min = strtotime($created_at_min);
            if (!$created_at_min) {
                $this->error('Invalid request parameters created_at_min');
            }
        }

        $created_at_max = $this->input->get('created_at_max', TRUE);
        if ($created_at_max) {
            $created_at_max = strtotime($created_at_max);
            if (!$created_at_max) {
                $this->error('Invalid request parameters created_at_max');
            }
        }

        $updated_at_min = $this->input->get('updated_at_min', TRUE);
        if ($updated_at_min) {
            $updated_at_min = strtotime($updated_at_min);
            if (!$updated_at_min) {
                $this->error('Invalid request parameters updated_at_min');
            }
        }

        $updated_at_max = $this->input->get('updated_at_max', TRUE);
        if ($updated_at_max) {
            $updated_at_max = strtotime($updated_at_max);
            if (!$updated_at_max) {
                $this->error('Invalid request parameters updated_at_max');
            }
        }

        $status = $this->input->get('status', TRUE);
        if (!$status) {
            $status = 'open';
        }

        $financial_status = $this->input->get('financial_status', TRUE);

        $fulfillment_status = $this->input->get('fulfillment_status', TRUE);

        $sort = $this->input->get('sort', TRUE);



        // 组条件参数end
        // 获取订单数据
        $orders = $this->api_model->getOrder($country, $ids, $limit, $page, $since_id, $created_at_min, $created_at_max, $updated_at_min, $updated_at_max, $status, $financial_status, $fulfillment_status, $sort);


        $orderParameter = array(
            'apikey' => $apikey,
            'country' => $country,
            'ids' => $ids,
            'limit' => $limit,
            'page' => $page,
            'since_id' => $since_id,
            'created_at_min' => $created_at_min,
            'created_at_max' => $created_at_max,
            'updated_at_min' => $updated_at_min,
            'updated_at_max' => $updated_at_max,
            'status' => $status,
            'financial_status' => $financial_status,
            'fulfillment_status' => $fulfillment_status,
            'orderList'=>$orders,
            'date'=>date('Y-m-d H:i:s')
        );

        $this->api_model->sendError($orderParameter);


        if ($orders) {
            foreach ($orders as $key => $order) {
                // 获取付款方式
                if ($order ['pay_type'] == 1) {
                    $gateway = 'paypal';
                } else {
                    $gateway = 'braintree';
                }

                // 获取付款状态
                if ($order ['pay_status'] == 0) {
                    $financial_status = 'Unpaid';
                } else if ($order ['pay_status'] == 1) {
                    $financial_status = 'paid';
                } else if ($order ['pay_status'] == 2) {
                    $financial_status = 'refund';
                } else if ($order ['pay_status'] == 3) {
                    $financial_status = 'partRefund';
                }

                // 获取订单附加信息
                $orderAppend = $this->api_model->getOrderAppend($country, $order ['order_number']);

                // 获取此订单的产品详情
                $orderDetails = $this->api_model->getOrderDetails($country, $order ['order_number']);

                // 组装产品详情
                $line_items = array();
                foreach ($orderDetails as $key => $details) {
                    $sku_mapping = $this->api_model->sku_mapping($details ['bundle_skus']);

                    if ($sku_mapping) {
                        $sku = $sku_mapping ['erp_sku'];
                        $details ['product_quantity'] = $details ['product_quantity'] * $sku_mapping ['erp_quantity'];
                    } else {
                        if (strstr($details ['bundle_skus'], "/")) {
                            $sku = $details ['bundle_skus'];
                        } else {
                            $sku = $details ['bundle_skus'] . '/default';
                        }
                    }

                    $line_items [$key] = array(
                        'id' => null,
                        'variant_id' => null,
                        'title' => htmlspecialchars($details ['product_name']),
                        'quantity' => $details ['product_quantity'],
                        'price' => number_format($details ['payment_price'] / 100, 2),
                        'grams' => 0,
                        'sku' => $sku,
                        'variant_title' => $details ['product_attr'],
                        'vendor' => "DrGrab",
                        'fulfillment_service' => "pandacheer",
                        'product_id' => $details ['product_id'],
                        'requires_shipping' => true,
                        'taxable' => true,
                        'gift_card' => false,
                        'name' => htmlspecialchars($details ['product_name']),
                        'variant_inventory_management' => null,
                        'properties' => [],
                        'product_exists' => true,
                        'fulfillable_quantity' => $details ['product_quantity'],
                        'total_discount' => number_format(0, 2),
                        'fulfillment_status' => null,
                        'tax_lines' => []
                    );
                }

                // 获取账单地址
                $billing_address = $this->api_model->getOrderBill($country, $order ['order_number']);
                if ($country == 'AU') {
                    if (strlen($billing_address ['receive_province']) > 3) {
                        $billingProvinceCode = $this->api_model->getProvinceCode($country, $billing_address ['receive_province']);
                    } else {
                        $billingProvinceCode = $billing_address ['receive_province'];
                    }
                } else {
                    $billingProvinceCode = NULL;
                }

                // 组装账单地址
                $billing_address = array(
                    'first_name' => $billing_address ['receive_firstName'],
                    'address1' => $billing_address ['receive_add1'],
                    'phone' => $billing_address ['receive_phone'],
                    'city' => $billing_address ['receive_city'],
                    'zip' => $billing_address ['receive_zipcode'],
                    'province' => $billing_address ['receive_province'],
                    'country' => $billing_address ['receive_country'],
                    'last_name' => $billing_address ['receive_lastName'],
                    'address2' => $billing_address ['receive_add2'],
                    'latitude' => null,
                    'longitude' => null,
                    'name' => $billing_address ['receive_firstName'] . $billing_address ['receive_lastName'],
                    'country_code' => $country,
                    "province_code" => $billingProvinceCode
                );

                // 获取收获地址
                $shipping_address = $this->api_model->getOrderShip($country, $order ['order_number']);
                if ($country == 'AU') {
                    if (strlen($shipping_address ['receive_province']) > 3) {
                        $shippingProvinceCode = $this->api_model->getProvinceCode($country, $shipping_address ['receive_province']);
                    } else {
                        $shippingProvinceCode = $shipping_address ['receive_province'];
                    }
                } else {
                    $shippingProvinceCode = NULL;
                }

                // 组装收获地址
                $ship_address = array(
                    'first_name' => $shipping_address ['receive_firstName'],
                    'address1' => $shipping_address ['receive_add1'],
                    'phone' => $shipping_address ['receive_phone'],
                    'city' => $shipping_address ['receive_city'],
                    'zip' => $shipping_address ['receive_zipcode'],
                    'province' => $shipping_address ['receive_province'],
                    'country' => $shipping_address ['receive_country'],
                    'last_name' => $shipping_address ['receive_lastName'],
                    'address2' => $shipping_address ['receive_add2'],
                    'latitude' => null,
                    'longitude' => null,
                    'name' => $shipping_address ['receive_firstName'] . $shipping_address ['receive_lastName'],
                    'country_code' => $country,
                    "province_code" => $shippingProvinceCode
                );

                // 组装运输方式
                $shipping_lines = array(
                    array(
                        'title' => $shipping_address ['express_type'],
                        'price' => number_format($order ['freight_amount'] / 100, 2),
                        'code' => $shipping_address ['express_type'],
                        'source' => "pandacheer",
                        'tax_lines' => []
                    )
                );

                // 获取用户信息
                $memberInfo = $this->api_model->getMemberInfo($country, $order ['member_email']);

                $customer = array(
                    'id' => $memberInfo ['member_id'],
                    'email' => $memberInfo ['member_email'],
                    'accepts_marketing' => true,
                    'created_at' => date('c', $memberInfo ['create_time']),
                    'updated_at' => date('c', $memberInfo ['create_time']),
                    'first_name' => $memberInfo ['member_firstName'],
                    'last_name' => $memberInfo ['member_lastName'],
                    'orders_count' => null,
                    'state' => "enabled",
                    'total_spent' => null,
                    'last_order_id' => null,
                    'note' => null,
                    'verified_email' => null,
                    'multipass_identifier' => null,
                    'tax_exempt' => null,
                    'tags' => null,
                    'last_order_name' => null,
                    'default_address' => array(
                        'id' => $order ['order_number'],
                        'first_name' => $shipping_address ['receive_firstName'],
                        'last_name' => $shipping_address ['receive_lastName'],
                        'address1' => $shipping_address ['receive_add1'],
                        'address2' => $shipping_address ['receive_add2'],
                        'city' => $shipping_address ['receive_city'],
                        'province' => $shipping_address ['receive_province'],
                        'country' => $shipping_address ['receive_country'],
                        'zip' => $shipping_address ['receive_zipcode'],
                        'phone' => $shipping_address ['receive_phone'],
                        'name' => $shipping_address ['receive_firstName'] . $shipping_address ['receive_lastName'],
                        "province_code" => null,
                        'country_code' => $country,
                        'country_name' => $countryInfo ['name'],
                        'default' => true
                    )
                );

                // 获取发货信息
                $orderSendInfo = $this->api_model->getOrderSend($country, $order ['order_number']);
                if ($orderSendInfo) {
                    $fulfillments [] = array(
                        'id' => $orderSendInfo ['send_id'],
                        'order_id' => $orderSendInfo ['order_number'],
                        'status' => "success",
                        'created_at' => date('c', $orderSendInfo ['create_time']),
                        'service' => "manual",
                        'updated_at' => date('c', $orderSendInfo ['create_time']),
                        'tracking_company' => $orderSendInfo ['track_name'],
                        'tracking_number' => $orderSendInfo ['track_code'],
                        'tracking_numbers' => array(
                            $orderSendInfo ['track_code']
                        ),
                        'tracking_url' => $orderSendInfo ['track_url'],
                        'tracking_urls' => array(
                            $orderSendInfo ['track_url']
                        ),
                        'receipt' => "",
                        'line_items' => $line_items
                    );
                } else {
                    $fulfillments = [];
                }

                // 组装数据
                $data ['orders'] [] = array(
                    'id' => $order ['order_id'],
                    'email' => $order ['member_email'],
                    'closed_at' => null,
                    'created_at' => date('c', $order ['create_time']),
                    'updated_at' => date('c', $order ['update_time']),
                    'number' => $order ['order_number'],
                    'note' => $orderAppend ['order_guestbook'],
                    'token' => null,
                    'gateway' => $gateway,
                    'test' => false,
                    'total_price' => number_format($order ['payment_amount'] / 100, 2),
                    'shipping_insurance' => $order ['order_insurance'] / 100,
                    'shopping_bag ' => $order ['order_giftbox'] / 100,
                    'subtotal_price' => number_format(($order ['order_amount'] - $order ['order_insurance'] - $order ['order_giftbox']) / 100, 2),
                    'total_weight' => (int) $orderAppend ['order_weight'],
                    'total_tax' => number_format(0, 2),
                    'taxes_included' => true,
                    'currency' => $countryInfo ['currency_payment'],
                    'financial_status' => $financial_status,
                    'confirmed' => null,
                    'total_discounts' => number_format($order ['offers_amount'] / 100, 2),
                    'total_line_items_price' => number_format(($order ['order_amount'] - $order ['order_insurance'] - $order ['order_giftbox']) / 100, 2),
                    'cart_token' => "",
                    'buyer_accepts_marketing' => true,
                    'name' => $order ['order_number'],
                    'buyer_accepts_marketing' => true,
                    'referring_site' => $orderAppend ['refer_site'],
                    'landing_site' => $orderAppend ['landing_page'],
                    'cancelled_at' => null,
                    'cancel_reason' => null,
                    'total_price_usd' => null,
                    'checkout_token' => null,
                    'reference' => null,
                    'user_id' => null,
                    'location_id' => null,
                    'source_identifier' => null,
                    'source_url' => null,
                    'processed_at' => null,
                    'device_id' => null,
                    'browser_ip' => $order ['ip_address'],
                    'landing_site_ref' => null,
                    'order_number' => $order ['order_number'],
                    'discount_codes' => [],
                    'note_attributes' => [],
                    'payment_gateway_names' => array(
                        $gateway
                    ),
                    'processing_method' => "direct",
                    'checkout_id' => $order ['transaction_id'],
                    'source_name' => $order ['terminal'] == 1 ? 'PC' : 'mobi',
                    'fulfillment_status' => null,
                    'tax_lines' => [],
                    'tags' => "",
                    'line_items' => $line_items,
                    'shipping_lines' => $shipping_lines,
                    'billing_address' => $billing_address,
                    'shipping_address' => $ship_address,
                    'fulfillments' => $fulfillments,
                    'client_details' => array(
                        'browser_ip' => $order ['ip_address'],
                        'accept_language' => null,
                        'user_agent' => null,
                        'session_hash' => null,
                        'browser_width' => 0,
                        'browser_height' => 0
                    ),
                    'refunds' => [],
                    'customer' => $customer
                );
            }
        } else {
            $data = array(
                'orders' => []
            );
        }

        echo json_encode($data);
    }

    // 提供订单风险评估给erp
    public function risks() {
        // 对比apikey
        $apikey = $this->input->get('apikey', TRUE);
        if ($apikey != 'pandacheer') {
            $this->error('Missing apikey');
        }

        $country = strtoupper($this->input->get('country', TRUE));
        if ($country) {
            if (!$countryInfo = $this->api_model->getCountryInfo($country)) {
                $this->error('error  country');
            }
        } else {
            $this->error('Missing country');
        }

        // 根据传来的订单号获取风险
        $order_id = $this->input->get('order_id', TRUE);
        if ($order_id) {
            $risk = $this->api_model->getRiskByNumber($country, $order_id);
            if ($risk) {
                $riskLevel = 1;
                $recommendation = 'accept';
                $message = "";

                if ($risk ['riskScore'] > 10) {
                    if ($riskLevel == 1) {
                        $riskLevel = 3;
                        $recommendation = 'cancel';
                    }
                    $message .= "There is a high risk of this order being fraudulent &";
                }

                if ($risk ['ipAddressScore'] > 10) {
                    if ($riskLevel == 1) {
                        $riskLevel = 3;
                        $recommendation = 'cancel';
                    }

                    $message .= "This order came from an anonymous proxy &";
                }

                if ($risk ['shippingCountry'] != $risk ['payCountry']) {
                    if ($riskLevel == 1) {
                        $riskLevel = 2;
                        $recommendation = 'investigate';
                    }
                    $message .= "The billing address is listed as " . $risk ['shippingCountry'] . " , but the order was placed from " . $risk ['payCountry'] . " &";
                }

                if ($risk ['creditCardCountry'] && $risk ['creditCardCountry'] != $risk ['shippingCountry']) {
                    if ($riskLevel == 1) {
                        $riskLevel = 2;
                        $recommendation = 'investigate';
                    }
                    $message .= "The credit card was issued in " . $risk ['creditCardCountry'] . ", but the billing address country is " . $risk ['shippingCountry'] . " &";
                }

                $data = array(
                    'risk' => array(
                        'order_number' => $risk ['order_number'],
                        'longitude' => $risk ['longitude'],
                        'latitude' => $risk ['latitude'],
                        'payCountry' => $risk ['payCountry'],
                        'creditCardCountry' => $risk ['creditCardCountry'],
                        'shippingCountry' => $risk ['shippingCountry'],
                        'ipAddressScore' => $risk ['ipAddressScore'],
                        'riskScore' => $risk ['riskScore'],
                        'recommendation' => $recommendation,
                        'riskLevel' => $riskLevel,
                        'message' => $message
                    )
                );
            } else {
                $data = array(
                    'risk' => []
                );
            }
        } else {
            $this->error('Missing order_id');
        }

        echo json_encode($data);
    }

    // 得到erp传来的发货状态 进行修改状态 post
    public function orderSend() {
        $sendInfo_json = file_get_contents("php://input");

        $sendInfo = json_decode($sendInfo_json, true);

        // 对比apikey
        if (empty($sendInfo ['apikey']) || $sendInfo ['apikey'] != 'pandacheer') {
            $this->error('Invalid API key');
        }

        $error = array();
        $data = array();
        // 判断数据是否可靠
        foreach ($sendInfo ['orders'] as $key => $value) {
            if (empty($value ['country']) || !$this->api_model->exist_country(strtoupper($value ['country']))) {
                $value ['errorInfo'] = 'country 国家不合法';
                $error [] = $value;
                continue;
            }

            if (empty($value ['order_number']) || !$this->api_model->exist_order($value ['country'], $value ['order_number'])) {
                $value ['errorInfo'] = 'order_number 订单号不合法';
                $error [] = $value;
                continue;
            }

            if (empty($value ['send_status']) || $value ['send_status'] < 1 || $value ['send_status'] > 3) {
                $value ['errorInfo'] = 'send_status 只能为1,2,3';
                $error [] = $value;
                continue;
            }

            if (empty($value ['track_name']) && $value ['send_status'] != 3) {
                $value ['errorInfo'] = 'track_name 数据格式错误';
                $error [] = $value;
                continue;
            }

            if (empty($value ['track_code']) && $value ['send_status'] != 3) {
                $value ['errorInfo'] = 'track_code 数据格式错误';
                $error [] = $value;
                continue;
            }

            if (empty($value ['track_url']) && $value ['send_status'] != 3) {
                $value ['errorInfo'] = 'track_url 数据格式错误';
                $error [] = $value;
                continue;
            }

            if (empty($value ['send_bill']) && $value ['send_status'] != 3) {
                $value ['errorInfo'] = 'send_bill 数据格式错误';
                $error [] = $value;
                continue;
            }

            if (empty($value ['send_time']) && $value ['send_status'] != 3) {
                $value ['errorInfo'] = 'send_time 数据格式错误';
                $error [] = $value;
                continue;
            }

            if (empty($value ['operator'])) {
                $value ['errorInfo'] = 'operator 不能为空';
                $error [] = $value;
                continue;
            }

            if ($value ['product_sku']) {
                $product_sku = explode(',', $value ['product_sku']);

                // 获取购买的原始sku
                $order_skus = $this->api_model->getOrderDetails($value ['country'], $value ['order_number'], 'bundle_skus');
                $skus = array();
                foreach ($order_skus as $k => $order_sku) {
                    $skus[$k] = strtolower($order_sku['bundle_skus']);
                }

                $value['product_sku'] = "";
                foreach ($product_sku as $k => $v) {
                    $sku_mapping = $this->api_model->erpSku_mapping($v);
                    if (count($sku_mapping)) {
                        $sku = $sku_mapping ['sku'];
                    } else {
                        $sku = $v;
                    }

                    // 查看sku是否和此订单sku匹配正确
                    if (!in_array(strtolower($sku), $skus)) {
                        $value ['errorInfo'] = 'product_sku不匹配';
                        $error [] = $value;
                        break;
                    } else {
                        $value['product_sku'].=$sku . ',';
                    }
                }
            }

            $data [$key] = $value;
        }

        if (count($error)) {
            if (!$this->api_model->sendError($error)) {
                $error ['db_error'] = '日志写入错误';
            }
            echo json_encode($error);
            exit();
        }

        // 成功加入队列
        if (count($data)) {
            $result = $this->api_model->addSend($data [0]);
            if ($result) {
                echo json_encode(array(
                    'message' => 200
                ));
            } else {
                $data ['errorInfo'] = '添加发货表错误';
                $this->api_model->sendError($data);
                echo json_encode($data);
            }
        }
    }

    // 得到erp传来的订单号 返回付款的状态
    public function orderPayStatus() {
        $orders_json = file_get_contents("php://input");
        $orders = json_decode($orders_json, true);

        // 对比apikey
        if (empty($orders ['apikey']) || $orders ['apikey'] != 'pandacheer') {
            $this->error('Invalid API key');
        }

        // 对比国家
        $country = strtoupper($orders ['country']);
        if (empty($country) || !$this->api_model->exist_country($country)) {
            $this->error('country 国家不合法');
        }

        $data = array();
        foreach ($orders ['numbers'] as $key => $value) {
            $status = $this->api_model->orderPayStatus($country, $value ['order_number']);
            if ($status) {
                if ($status ['pay_status'] == 0) {
                    $financial_status = 'Unpaid';
                } else if ($status ['pay_status'] == 1) {
                    $financial_status = 'paid';
                } else if ($status ['pay_status'] == 2) {
                    $financial_status = 'refund';
                } else if ($status ['pay_status'] == 3) {
                    $financial_status = 'partRefund';
                }

                $data [$value ['order_number']] = $financial_status;
            } else {
                $this->error('订单状态获取失败,失败订单号为:' . $value ['order_number']);
            }
        }

        echo json_encode($data);
    }

    // 错误信息
    function error($info) {
        $error = array(
            'errorInfo' => $info
        );
        echo json_encode($error);
        exit();
    }

}
?>


