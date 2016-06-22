<?php

/**
 * @文件： mail
 * @时间： 2015-10-20 11:55:53
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：
 */
class mail extends MY_Controller {

    function __construct() {
        parent::__construct();
    }

    //优惠券过期
    public function couponExpired($msg = 'xiong.xin.yang@qq.com:1441866096') {
        $info = explode(':', $msg);
        $data = array(
            // 我们的信息
            'shopurl' => $this->page['domain'], //'http://www.drgrab.com.au/', // 发送地址
            'shopmail' => $this->page['service_mail'], // 加上各个国家的后缀，例如drgrab.com.au
            // 用户的信息
            'to' => $info[0], // 游客留下的邮箱
            'endDate' => $info[1],
            'name' => 'member'
        );
        $this->load->model('mail_model');
        echo $this->mail_model->couponExpired($data) ? 1 : 0;
    }

    //发货邮件
    public function fulfil() {
        $send = $this->input->post();

        $this->load->model('maildata_model');
        $this->load->model('order_model');

        //获取用户
        $member = $this->maildata_model->getInfoByNumber($send['country'], $send['order_number']);
        //获取收货地址
        $ship = $this->maildata_model->getOrderShip($send['country'], $send['order_number']);
        //获取产品信息
        $details = $this->maildata_model->getOrderDetails($send['country'], $send['order_number'],$send['product_sku']);
        //获取预计到货时间
        $estimatedTime = $this->order_model->getInfoByNumber($send['country'], $send['order_number'], 'create_time,estimated_time');
        //查找销量最好的20个产品中5随机5个
        $_product = $this->product_model->getRcommendByAll($send['country']);

        //生成优惠券
//        $this->load->model('coupons_model');
//        $couponsInfo = $this->coupons_model->autoGet($send['country'],$member['member_email']);

        //组装数据
        $data = array(
            // 我们的信息
            'shopurl' => $this->page['domain'], //'http://www.drgrab.com.au/', // 发送地址
            'shopmail' => $this->page['service_mail'], // 加上各个国家的后缀，例如drgrab.com.au
            'to' => $member['member_email'],
            'member' => $member,
            'order_ship' => $ship,
            'pro_details' => $details,
            'order_send' => $send,
            'estimatedTime' => $estimatedTime,
            'goodproduct' => RandProduct($_product),
//            'couponsInfo'=>$couponsInfo,
            'currency'=>$this->page['currency']
        );

        $this->load->model('mail_model');
        //返回结果发生邮件
        echo $this->mail_model->shippingConfirmation($data) ? 1 : 0;
    }

    //退款邮件
    public function refund() {
        $refund_json = file_get_contents("php://input");
        $refundInfo = json_decode($refund_json, true);

        $this->load->model('maildata_model');
        $refund = $this->maildata_model->getInfoById($refundInfo['country'], $refundInfo['rid']);
        $member = $this->maildata_model->getInfoByNumber($refundInfo['country'], $refund['order_number']);
        $refund_details = $this->maildata_model->getRefund_detailsById($refundInfo['country'], $refundInfo['rid']);

        $data = array(
            // 我们的信息
            'shopurl' => $this->page['domain'], //'http://www.drgrab.com.au/', // 发送地址
            'shopmail' => $this->page['service_mail'], // 加上各个国家的后缀，例如drgrab.com.au
            'to' => $member['member_email'],
            'member' => $member,
            'refund' => $refund,
            'refundInfo' => $refundInfo,
            'refund_details' => $refund_details
        );

        $this->load->model('mail_model');

        echo $this->mail_model->refundNotification($data) ? 1 : 0;
    }

}
