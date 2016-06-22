<?php

/**
 *  @说明  退款订单控制器
 *  @作者  zhujian
 *  @qq    407284071
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class orderRefund extends Pc_Controller {

    public function __construct() {
        parent::__construct();
        parent::_active('refund');
        $this->user_id = $this->session->userdata('user_id');
        $this->user_name = $this->session->userdata('user_account');
        $this->load->model('refundBills_model');
        $this->country = $this->session->userdata('my_country');
    }

    // 显示退款列表
    public function index() {
        $this->load->helper('form');
        $per_page = 10; // 每页记录数
        if ($this->input->post()) {
            $pagenum = 1;
            $keyword = $this->input->post('search') ? $this->input->post('search') : 'ALL';
            $keyword2 = $this->input->post('s_status') ? $this->input->post('s_status') : 'ALL';
        } else {
            $pagenum = ($this->uri->segment(5) === FALSE) ? 1 : $this->uri->segment(5);
            $keyword = urldecode($this->uri->segment(3) ? $this->uri->segment(3) : 'ALL' );
            $keyword2 = urldecode($this->uri->segment(4) ? $this->uri->segment(4) : 'ALL' );
        }


        if ($keyword != 'ALL') {
            if ($keyword2 == 'ALL') {
                redirect("orderRefund");
            }

            $whereData [$keyword2 . ' like'] = "%$keyword%";
            $total_rows = $this->refundBills_model->refundCount($this->country, $whereData);
            $this->page ['refund_bills'] = $this->refundBills_model->getRefund_bills($this->country, $whereData, ($pagenum - 1) * $per_page, $per_page);
        } else {
            $whereData = [];
            $total_rows = $this->refundBills_model->refundCount($this->country, $whereData);
            $this->page ['refund_bills'] = $this->refundBills_model->getRefund_bills($this->country, $whereData, ($pagenum - 1) * $per_page, $per_page);
        }




        /* $total_rows = $this->refundBills_model->refundCount($this->country);
          // 获取信息
          $this->page ['refund_bills'] = $this->refundBills_model->getRefund_bills($this->country, ($pagenum - 1) * $per_page, $per_page); */

        // 分页开始
        $this->load->library('pagination');
        $config ['base_url'] = base_url() . 'orderRefund/index/' . $keyword . '/' . $keyword2;
        $config ['total_rows'] = $total_rows; // 总记录数
        $config ['per_page'] = $per_page; // 每页记录数
        $config ['num_links'] = 2; // 当前页码边上放几个链接
        $config ['uri_segment'] = 5; // 页码在第几个uri上
        $this->pagination->initialize($config);
        $this->page ['pages'] = $this->pagination->create_links();
        // 分页结束

        $this->page ['head'] = $this->load->view('head', $this->_category, true);
        $this->page ['foot'] = $this->load->view('foot', $this->_category, true);
        //赋值搜索条件到前台
        $this->page['where'] = array($keyword, $keyword2);
        $this->load->view('orderRefundList', $this->page);
    }

    // 显示退款订单详情
    public function getInfo($refund_id = 0) {
        $this->load->helper('form');
        $this->page ['refund_details'] = $this->refundBills_model->getRefund_detailsById($this->country, $refund_id);
        if (!count($this->page ['refund_details'])) {
            redirect("orderRefund");
        }


        if ($this->page ['refund_details'][0]['product_id']) {
            $this->load->model('Product_model');
            foreach ($this->page ['refund_details'] as $key => $detail) {

                $pro = $this->Product_model->orderPics($this->country, $detail['product_id']);

                $img = IMAGE_DOMAIN . '/product/' . $detail['product_sku'] . '/' . $detail['product_sku'] . '.jpg';
                if (!@fopen($img, 'r')) {
                    $img = IMAGE_DOMAIN . $pro['image'];
                }

                $this->page ['refund_details'][$key]['image'] = $img;
            }
        }

        $this->page ['refund_bills'] = $this->refundBills_model->getInfoById($this->country, $refund_id);

        $this->page ['head'] = $this->load->view('head', $this->_category, true);
        $this->page ['foot'] = $this->load->view('foot', $this->_category, true);
        $this->load->view('ordersRefundContent', $this->page);
    }

    // 修改退款单状态 并根据情况进行订单状态修改
    public function paymentRefund() {
        $refund_id = $this->input->post('refund_id', TRUE);
        $order_number = $this->input->post('order_number', TRUE);
        $pay_pwd = $this->input->post('pay_pwd', TRUE);
        //获取订单信息
        $this->load->model('order_model');
        $orderInfo = $this->order_model->getInfoByNumber($this->country, $order_number, 'transaction_id,payment_amount,order_insurance,order_giftbox,pay_status');
        if (!$orderInfo) {
            exit(json_encode(array('success' => FALSE, 'msg' => '订单不存在！')));
        } else {
            if ($orderInfo['pay_status'] == 0 || $orderInfo['pay_status'] == 2) {
                exit(json_encode(array('success' => FALSE, 'msg' => '订单不能执行退款！')));
            }
        }

        $orderInfo['payment_amount'] = $orderInfo['payment_amount'] - $orderInfo['order_insurance'] - $orderInfo['order_giftbox'];

        // 获取退款单信息
        $refundBillInfo = $this->refundBills_model->getInfoById($this->country, $refund_id, 'order_number,refund_amount,pay_type,order_transaction_id,refund_status');
        if (!$refundBillInfo) {
            exit(json_encode(array('success' => FALSE, 'msg' => '退款单不存在！')));
        } else {
            if ($refundBillInfo ['order_number'] != $order_number || $refundBillInfo ['order_transaction_id'] != $orderInfo['transaction_id']) {
                exit(json_encode(array('success' => FALSE, 'msg' => '退款单错误！')));
            }
            if ($refundBillInfo ['refund_status'] != 1) {
                exit(json_encode(array('success' => FALSE, 'msg' => '退款单已处理！')));
            }
        }



        $operator = $this->session->userdata('user_account');
        $all_amount = $this->refundBills_model->refund_sum($this->country, $order_number);

        $paySuccess = FALSE;


        if ($refundBillInfo['pay_type'] < 3) {
            $this->load->helper('callerservice');

            $transaction_id = urlencode($refundBillInfo ['order_transaction_id']);
            $refundType = $orderInfo ['payment_amount'] == $refundBillInfo['refund_amount'] ? 'Full' : 'Partial';
            $amount = urlencode($refundBillInfo['refund_amount'] / 100);
            $currency = urlencode($this->session->userdata('my_currencyPayment'));

            $nvpStr = "&TRANSACTIONID=$transaction_id&REFUNDTYPE=$refundType&CURRENCYCODE=$currency";
            if (strtoupper($refundType) == "PARTIAL") {
                $nvpStr = $nvpStr . "&AMT=$amount";
            }

            $resArray = hash_call("RefundTransaction", $nvpStr);
            $ack = strtoupper($resArray["ACK"]);
            if ($ack == "SUCCESS") {
                $refund_transaction_id = $resArray['REFUNDTRANSACTIONID'];
                $paySuccess = TRUE;
            }
        } else {
            $this->load->library("braintree_lib");
            $result = Braintree_Transaction::refund("{$refundBillInfo['order_transaction_id']}", $refundBillInfo['refund_amount'] / 100);
            $paySuccess = $result->success;
            if ($paySuccess) {
                $refund_transaction_id = $result->transaction->_attributes['id'];
            }
        }

        if ($paySuccess) {
            /* 写入退款成功日志 */
            $logMongoDBtable = $this->mongo->return_log;
            $docLog = array(
                'refund_id' => $refund_id,
                'order_number' => $order_number,
                'transaction_id' => $orderInfo['transaction_id'],
                'refund_amount' => $refundBillInfo['refund_amount'],
                'operator' => $this->user_name,
                'status' => 'Success',
                'msg' => $resArray,
                'create_time' => date('Y-m-d H:i:s')
            );
            $logMongoDBtable->insert($docLog);
            /* 写入结束 */

            // 退款总金额小于订单金额的时候
            if ($all_amount ['refund_amount'] < $orderInfo ['payment_amount']) {
                if ($orderInfo ['pay_status'] == 1) {// 修改订单状态为部分退款 在同时修改退款状态
                    if ($this->refundBills_model->update_refundState($this->country, $order_number, $refund_id, 3, 2, $operator, $refund_transaction_id)) {
                        $result = $this->curlMail($this->country, $refund_id);
                        if ($result) {
                            exit(json_encode(array('success' => TRUE)));
                        } else {
                            exit(json_encode(array('success' => FALSE, 'msg' => '退款成功，发送邮件失败！请联系管理员_E001！')));
                        }
                    } else {
                        exit(json_encode(array('success' => FALSE, 'msg' => '退款成功，数据更新失败！请联系管理员_E002！')));
                    }
                } else if ($orderInfo ['pay_status'] == 3) { // 直接修改退款状态 无需修改订单状态
                    if ($this->refundBills_model->up_refund($this->country, $refund_id, 2, $operator, $refund_transaction_id)) {
                        $result = $this->curlMail($this->country, $refund_id);
                        if ($result) {
                            exit(json_encode(array('success' => TRUE)));
                        } else {
                            exit(json_encode(array('success' => FALSE, 'msg' => '退款成功，发送邮件失败！请联系管理员_E003！')));
                        }
                    } else {
                        exit(json_encode(array('success' => FALSE, 'msg' => '退款成功，数据更新失败！请联系管理员_E004！')));
                    }
                }
            } else if ($all_amount ['refund_amount'] == $orderInfo ['payment_amount']) {// 相等的话 修改订单状态为已退款(全额) 在同时修改退款状态
                if ($this->refundBills_model->update_refundState($this->country, $order_number, $refund_id, 2, 2, $operator, $refund_transaction_id)) {
                    $result = $this->curlMail($this->country, $refund_id);
                    if ($result) {
                        exit(json_encode(array('success' => TRUE)));
                    } else {
                        exit(json_encode(array('success' => FALSE, 'msg' => '退款成功，发送邮件失败！请联系管理员_E005！')));
                    }
                } else {
                    exit(json_encode(array('success' => FALSE, 'msg' => '退款成功，数据更新失败！请联系管理员_E006！')));
                }
            }
        } else {
            /* 写入退款失败日志 */
            $logMongoDBtable = $this->mongo->return_log;
            $docLog = array(
                'refund_id' => $refund_id,
                'order_number' => $order_number,
                'transaction_id' => $orderInfo['transaction_id'],
                'refund_amount' => $refundBillInfo['refund_amount'],
                'operator' => $this->user_name,
                'status' => 'Failure',
                'msg' => $resArray,
                'create_time' => date('Y-m-d H:i:s')
            );
            $logMongoDBtable->insert($docLog);
            /* 写入结束 */
            exit(json_encode(array('success' => FALSE, 'msg' => '支付网关退款失败！')));
        }
    }

    //发退货邮件
    public function curlMail($country, $r_id) {
        $this->load->model('country_model');
        $url = $this->country_model->getInfoByCode($country, $fields = array('domain', 'currency_symbol', 'currency_payment'));

        $data = array(
            'rid' => $r_id,
            'country' => $country,
            'currency' => $url['currency_symbol'],
            'payment' => $url['currency_payment']
        );

        $content = json_encode($data);

        $curl = curl_init($url['domain'] . '/mail/refund');
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }

    //取消退款记录
    public function cancelRefund() {
        $refund_id = $this->input->post('refund_id', TRUE);
        $operator = $this->session->userdata('user_account');

        if ($this->refundBills_model->up_refund($this->country, $refund_id, 3, $operator, '')) {
            exit(json_encode(array('success' => TRUE)));
        } else {
            exit(json_encode(array('success' => False)));
        }
    }

    //删除退款
    public function delete() {
        $refund_id = $this->input->post('refund_id', TRUE);

        $userInfo = $this->refundBills_model->getInfoById($this->country, $refund_id, 'proposer_id');
        if ($this->user_id == $userInfo['proposer_id']) {
            if ($this->refundBills_model->delete_refund($this->country, $refund_id, $this->user_id)) {
                exit(json_encode(array('success' => TRUE)));
            }
        } else {
            exit(json_encode(array('success' => False)));
        }
    }

}

?>
