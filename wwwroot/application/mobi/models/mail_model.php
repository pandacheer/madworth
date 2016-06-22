<?php

class Mail_model extends CI_Model {

    protected $table = 'crontabmail';
    protected $shopurl;
    protected $shopmail;
    protected $model = array(
        'status' => 0,
        'from' => '',
        'sender' => '',
        'to' => '',
        'title' => '',
        'content' => ''
    );

    public function __construct() {
        parent::__construct();
    }

    private function add($data) {
        $data['time'] = time();
        return $this->db->insert($this->table, $data);
    }

    // 游客账户生成
    /* 传入数据演示
      $data = array(
      // 我们的信息
      'shopurl' => 'http://www.drgrab.com.au/', // 发送地址
      'shopmail' => 'support@drgrab.com.au', // 加上各个国家的后缀，例如drgrab.com.au
      // 用户的信息
      'name' => 'ZhuJian', // 游客留下的姓名
      'account' => 'ZhuJian@126.com', // 生成的游客账户
      'password' => '123456', // 生成的游客密码
      'to' => 'paddyzhu@me.com', // 游客留下的邮箱
      'reseturl' => 'http://www.drgrab.com.au/forget' // 用户重新修改密码的URL
      );
     */
    public function created($data) {
        $content = $this->load->view('mail/customerAccountCreated', $data, true);
        $array = array(
            'from' => $data['shopmail'],
            'sender' => 'DrGrab Support Team',
            'to' => $data['to'],
            'title' => 'Customer Account Created',
            'content' => $content,
        );
        return $this->add($array);
    }

    // 注册成功
    /* 传入数据演示
      $data = array(
      // 我们的信息
      'shopurl' => 'http://www.drgrab.com.au/', // 发送地址
      'shopmail' => 'support@drgrab.com.au', // 加上各个国家的后缀，例如drgrab.com.au
      // 用户的信息
      'to' => 'paddyzhu@me.com', // 游客留下的邮箱
      );
     */
    public function confirmation($data) {
        $content = $this->load->view('mail/customerAccountConfirmation', $data, true);
        $array = array(
            'from' => $data['shopmail'],
            'sender' => 'DrGrab Support Team',
            'to' => $data['to'],
            'title' => 'Account Confirmation',
            'content' => $content,
        );
        return $this->add($array);
    }

    public function shopifyConfirmation($data) {
        $content = $this->load->view('mail/customerAccountReactivate', $data, true);
        $array = array(
            'from' => $data['shopmail'],
            'sender' => 'DrGrab Support Team',
            'to' => $data['to'],
            'title' => 'Account Activation',
            'content' => $content,
        );
        return $this->add($array);
    }

    // 邮件订阅确定
    /* 传入数据演示
      $data = array(
      // 我们的信息
      'shopurl' => 'http://www.drgrab.com.au/', // 发送地址
      'shopmail' => 'support@drgrab.com.au', // 加上各个国家的后缀，例如drgrab.com.au
      // 用户的信息
      'to' => 'paddyzhu@me.com', // 游客留下的邮箱
      );
     */
    public function confirmationSubscription($data) {
        $content = $this->load->view('mail/customerSubscription', $data, true);
        $array = array(
            'from' => $data['shopmail'],
            'sender' => 'DrGrab Support Team',
            'to' => $data['to'],
            'title' => 'Glad to have you with us, please verify your email',
            'content' => $content,
        );
        return $this->add($array);
    }

    // 密码找回
    /* 传入数据演示
      $data = array(
      // 我们的信息
      'shopurl' => 'http://www.drgrab.com.au/', // 发送地址
      'shopmail' => 'support@drgrab.com.au', // 加上各个国家的后缀，例如drgrab.com.au
      // 用户的信息
      'to' => 'paddyzhu@me.com', // 游客留下的邮箱
      'reseturl' => 'http://www.drgrab.com.au/forget' // 用户重新修改密码的URL
      );
     */
    public function passwordreset($data) {
        $content = $this->load->view('mail/customerAccountPasswordReset', $data, true);
        $array = array(
            'from' => $data['shopmail'],
            'sender' => 'DrGrab Support Team',
            'to' => $data['to'],
            'title' => 'Account Password Reset',
            'content' => $content,
        );
        return $this->add($array);
    }

    // 订单确认
    /* 传入数据演示
      $data = array(
      // 我们的信息
      'shopurl' => 'http://www.drgrab.com.au/', // 发送地址
      'shopmail' => 'support@drgrab.com.au', // 加上各个国家的后缀，例如drgrab.com.au
      // 用户的信息
      'date' => '08/07/2015', // 订单日期
      'shippingaddress' => 'aabbcc<br>deff street', // 收货地址
      'billingaddress' => 'aabbcc<br>deff street', // 账单地址
      'productlist' => array(), // 订单产品列表
      'subtotal' => 100, // 订单金额
      'shipping' => 10, // 运费总计
      'total' => 110, // 实付金额
      'to' => 'paddyzhu@me.com', // 游客留下的邮箱
      );
     */
    public function orderconfirmation($data, $country) {
        $content = $this->load->view('mail/orderConfirmationForOrder', $data, true);
        $array = array(
            'from' => $data['shopmail'],
            'sender' => 'DrGrab Support Team',
            'to' => $data['to'],
            'bcc' => 0,
            'title' => 'Order Confirmation For Order  ' . $country . $data['insert_orderData']['order_number'],
            'content' => $content,
        );
        return $this->add($array);
    }

    // 订单未付款
    /* 传入数据演示
      $data = array(
      // 我们的信息
      'shopurl' => 'http://www.drgrab.com.au/', // 发送地址
      'shopmail' => 'support@drgrab.com.au', // 加上各个国家的后缀，例如drgrab.com.au
      // 用户的信息
      'productlist' => array(), // 订单产品列表
      'to' => 'paddyzhu@me.com', // 游客留下的邮箱
      'orderurl' => 'https://checkout.shopify.com/orders/4578709' // 该订单URL
      // 等待朱健配合完成
      );
     */
    public function orderunpaid($data) {
        $content = $this->load->view('mail/orderUnpaidReminder', $data, true);
        $array = array(
            'from' => $data['shopmail'],
            'sender' => 'DrGrab Support Team',
            'to' => $data['to'],
            'title' => 'Customer Account Created',
            'content' => $content,
        );
        return $this->add($array);
    }

    // 订单取消
    /* 传入数据演示
      $data = array(
      // 我们的信息
      'shopurl' => 'http://www.drgrab.com.au/', // 发送地址
      'shopmail' => 'support@drgrab.com.au', // 加上各个国家的后缀，例如drgrab.com.au
      // 用户的信息
      'to' => 'paddyzhu@me.com', // 游客留下的邮箱
      // 等待朱建配合完成(目前没有)
      'orderid' => ''
      );
     */
    public function ordercancelled($data) {
        $content = $this->load->view('mail/orderCancelled', $data, true);
        $array = array(
            'from' => $data['shopmail'],
            'sender' => 'DrGrab Support Team',
            'to' => $data['to'],
            'title' => 'Order Cancelled',
            'content' => $content,
        );
        return $this->add($array);
    }

    // 发货确定邮件
    /* 传入数据演示
      $data = array(
      // 我们的信息
      'shopurl' => 'http://www.drgrab.com.au/', // 发送地址
      'shopmail' => 'support@drgrab.com.au', // 加上各个国家的后缀，例如drgrab.com.au
      // 用户的信息
      'to' => 'paddyzhu@me.com', // 游客留下的邮箱
      );
     */
    public function shippingConfirmation($data) {
        $content = $this->load->view('mail/shippingConfirmation', $data, true);
        $array = array(
            'from' => $data['shopmail'],
            'sender' => 'DrGrab Support Team',
            'to' => $data['to'],
            'title' => 'Shipping Confirmation',
            'content' => $content,
        );
        return $this->add($array);
    }

    // 退款通知邮件
    /* 传入数据演示
      $data = array(
      // 我们的信息
      'shopurl' => 'http://www.drgrab.com.au/', // 发送地址
      'shopmail' => 'support@drgrab.com.au', // 加上各个国家的后缀，例如drgrab.com.au
      // 用户的信息
      'to' => 'paddyzhu@me.com', // 游客留下的邮箱
      );
     */
    public function refundNotification($data) {
        $content = $this->load->view('mail/refundNotification', $data, true);
        $array = array(
            'from' => $data['shopmail'],
            'sender' => 'DrGrab Support Team',
            'to' => $data['to'],
            'title' => 'Refund Notification',
            'content' => $content,
        );
        return $this->add($array);
    }

    // 邀请客户发布评论
    /* 传入数据演示
      $data = array(
      // 我们的信息
      'shopurl' => 'http://www.drgrab.com.au/', // 发送地址
      'shopmail' => 'support@drgrab.com.au', // 加上各个国家的后缀，例如drgrab.com.au
      // 用户的信息
      'to' => 'paddyzhu@me.com', // 游客留下的邮箱
      // 等待朱健配合完成(目前没有)
      );
     */
    public function shareReview($data) {
        $content = $this->load->view('mail/shareReview', $data, true);
        $array = array(
            'from' => $data['shopmail'],
            'sender' => 'DrGrab Support Team',
            'to' => $data['to'],
            'title' => 'Email to invite the customer to file and share review 30 days after we send the shipping confirmation',
            'content' => $content,
        );
        return $this->add($array);
    }

    // 告知客户打折卡到期时间 7天/1天
    /* 传入数据演示
      $data = array(
      // 我们的信息
      'shopurl' => 'http://www.drgrab.com.au/', // 发送地址
      'shopmail' => 'support@drgrab.com.au', // 加上各个国家的后缀，例如drgrab.com.au
      // 用户的信息
      'to' => 'paddyzhu@me.com', // 游客留下的邮箱
      );
     */
    public function couponExpired($data) {
        $content = $this->load->view('mail/couponExpired', $data, true);
        $array = array(
            'from' => $data['shopmail'],
            'sender' => 'DrGrab Support Team',
            'to' => $data['to'],
            'title' => 'Email to remind the customer that the coupon will be expired in seven days/one day',
            'content' => $content,
        );
        return $this->add($array);
    }

    // 生日祝贺
    /* 传入数据演示
      $data = array(
      // 我们的信息
      'shopurl' => 'http://www.drgrab.com.au/', // 发送地址
      'shopmail' => 'support@drgrab.com.au', // 加上各个国家的后缀，例如drgrab.com.au
      // 用户的信息
      'to' => 'paddyzhu@me.com', // 游客留下的邮箱
      // 等待朱健配合完成
      );
     */
    public function birthday($data) {
        $content = $this->load->view('mail/couponExpired', $data, true);
        $array = array(
            'from' => $data['shopmail'],
            'sender' => 'DrGrab Support Team',
            'to' => $data['to'],
            'title' => 'Email to remind the customer that the coupon will be expired in seven days/one day',
            'content' => $content,
        );
        return $this->add($array);
    }

    // 给3、6、9、12个月未下单的会员提供5% off / 8% off / 10% off /15%的打折卡
    /* 传入数据演示
      $data = array(
      // 我们的信息
      'shopurl' => 'http://www.drgrab.com.au/', // 发送地址
      'shopmail' => 'support@drgrab.com.au', // 加上各个国家的后缀，例如drgrab.com.au
      // 用户的信息
      'to' => 'paddyzhu@me.com', // 游客留下的邮箱
      // 等待朱健配合完成
      );
     */
    public function coupon($data) {
        $content = $this->load->view('mail/coupon', $data, true);
        $array = array(
            'from' => $data['shopmail'],
            'sender' => 'DrGrab Support Team',
            'to' => $data['to'],
            'title' => 'Email to remind the customer who haven\'t purchased in 3/6/9/12 months from Drgrab, with coupon 5% off / 8% off / 10% off /15% off respectively.',
            'content' => $content,
        );
        return $this->add($array);
    }

    // 订单物流更变
    /* 传入数据演示
      $data = array(
      // 我们的信息
      'shopurl' => 'http://www.drgrab.com.au/', // 发送地址
      'shopmail' => 'support@drgrab.com.au', // 加上各个国家的后缀，例如drgrab.com.au
      // 用户的信息
      'to' => 'paddyzhu@me.com', // 游客留下的邮箱
      // 等待朱健配合完成(目前没有)
      );
     */
    public function shippingUpdate($data) {
        $content = $this->load->view('mail/shippingUpdate', $data, true);
        $array = array(
            'from' => $data['shopmail'],
            'sender' => 'DrGrab Support Team',
            'to' => $data['to'],
            'title' => 'Shipping update for order #9999',
            'content' => $content,
        );
        return $this->add($array);
    }
    
    //授权第三方账号登陆邮件
    function authorizationThird($data){
        $content = $this->load->view('mail/facebookconfirm', $data, true);
        $array = array(
            'from' => $data['shopmail'],
            'sender' => 'DrGrab Support Team',
            'to' => $data['to'],
            'title' => 'facebook authorization',
            'content' => $content
        );
        return $this->add($array);
    }

}
