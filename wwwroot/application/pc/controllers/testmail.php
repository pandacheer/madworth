<?php
/*
    这是一个测试邮件的controller
    测试完毕以后可以删除我
    删除我并不会对任何其他功能产生影响！
*/
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Testmail extends MY_Controller{
    public function testcreated(){
        $data = array(
            // 我们的信息
            'shopurl' => 'http://www.drgrab.com.au/', // 发送地址
            'shopmail' => 'support@drgrab.com.au', // 加上各个国家的后缀，例如drgrab.com.au
            // 用户的信息
            'name' => 'ZhuJian', // 游客留下的姓名
            'account' => 'ZhuJian@126.com', // 生成的游客账户
            'password' => '123456', // 生成的游客密码
            'to' => '282227460@qq.com', // 游客留下的邮箱
            'reseturl' => 'http://www.drgrab.com.au/forget' // 用户重新修改密码的URL
        );
        $this->load->model('mail_model');
        $this->mail_model->created($data);
    }
    
    public function testreg(){
        $data = array(
            'shopurl' => 'http://abc.com/',
            'shopmail' => 'support@abc.com',
            'to' => '282227460@qq.com', // 游客留下的邮箱
            'reseturl' => 'forget/verifyMail/'
        );
        $this->load->model('mail_model');
        $this->mail_model->confirmation($data);
    }
    
    public function testpasswordreset(){
        $data = array(
            // 我们的信息
            'shopurl' => 'http://www.drgrab.com.au/', // 发送地址
            'shopmail' => 'support@drgrab.com.au', // 加上各个国家的后缀，例如drgrab.com.au
            // 用户的信息
            'to' => '282227460@qq.com', // 游客留下的邮箱
            'reseturl' => 'http://www.drgrab.com.au/forget' // 用户重新修改密码的URL
        );
        $this->load->model('mail_model');
        $this->mail_model->passwordreset($data);
    }
    
    public function orderconfirmation(){
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
            'to' => '407284071@qq.com', // 游客留下的邮箱
        );
        $this->load->model('mail_model');
        $this->mail_model->orderconfirmation($data);
    }
    
    public function orderunpaid(){
        $data = array(
            // 我们的信息
            'shopurl' => 'http://www.drgrab.com.au/', // 发送地址
            'shopmail' => 'support@drgrab.com.au', // 加上各个国家的后缀，例如drgrab.com.au
            // 用户的信息
            'productlist' => array(), // 订单产品列表
            'to' => '282227460@qq.com', // 游客留下的邮箱
            'orderurl' => 'https://checkout.shopify.com/orders/4578709' // 该订单URL
        );
        $this->load->model('mail_model');
        $this->mail_model->orderunpaid($data);
    }
    
    public function ordercancelled(){
        $data = array(
            // 我们的信息
            'shopurl' => 'http://www.drgrab.com.au/', // 发送地址
            'shopmail' => 'support@drgrab.com.au', // 加上各个国家的后缀，例如drgrab.com.au
            // 用户的信息
            'to' => '282227460@qq.com', // 游客留下的邮箱
            // 等待朱建配合完成
            'orderid' => ''
        );
        $this->load->model('mail_model');
        $this->mail_model->ordercancelled($data);
    }
    
    public function shippingConfirmation(){
        $data = array(
            // 我们的信息
            'shopurl' => 'http://www.drgrab.com.au/', // 发送地址
            'shopmail' => 'support@drgrab.com.au', // 加上各个国家的后缀，例如drgrab.com.au
            // 用户的信息
            'to' => '282227460@qq.com', // 游客留下的邮箱
            // 等待朱健配合完成
        );
        $this->load->model('mail_model');
        $this->mail_model->shippingConfirmation($data);
    }
    
    public function refundNotification(){
        $data = array(
            // 我们的信息
            'shopurl' => 'http://www.drgrab.com.au/', // 发送地址
            'shopmail' => 'support@drgrab.com.au', // 加上各个国家的后缀，例如drgrab.com.au
            // 用户的信息
            'to' => '282227460@qq.com', // 游客留下的邮箱
            // 等待朱健配合完成
        );
        $this->load->model('mail_model');
        $this->mail_model->refundNotification($data);
    }
    
    public function shareReview(){
        $data = array(
            // 我们的信息
            'shopurl' => 'http://www.drgrab.com.au/', // 发送地址
            'shopmail' => 'support@drgrab.com.au', // 加上各个国家的后缀，例如drgrab.com.au
            // 用户的信息
            'to' => '282227460@qq.com', // 游客留下的邮箱
            // 等待朱健配合完成
        );
        $this->load->model('mail_model');
        $this->mail_model->shareReview($data);
    }
    
    public function couponExpired(){
        $data = array(
            // 我们的信息
            'shopurl' => 'http://www.drgrab.com.au/', // 发送地址
            'shopmail' => 'support@drgrab.com.au', // 加上各个国家的后缀，例如drgrab.com.au
            // 用户的信息
            'to' => '282227460@qq.com', // 游客留下的邮箱
            // 等待朱健配合完成
        );
        $this->load->model('mail_model');
        $this->mail_model->couponExpired($data);
    }
    
    public function birthday(){
        $data = array(
            // 我们的信息
            'shopurl' => 'http://www.drgrab.com.au/', // 发送地址
            'shopmail' => 'support@drgrab.com.au', // 加上各个国家的后缀，例如drgrab.com.au
            // 用户的信息
            'to' => '282227460@qq.com', // 游客留下的邮箱
            // 等待朱健配合完成
        );
        $this->load->model('mail_model');
        $this->mail_model->birthday($data);
    }
    
    public function coupon(){
        $data = array(
            // 我们的信息
            'shopurl' => 'http://www.drgrab.com.au/', // 发送地址
            'shopmail' => 'support@drgrab.com.au', // 加上各个国家的后缀，例如drgrab.com.au
            // 用户的信息
            'to' => '282227460@qq.com', // 游客留下的邮箱
            // 等待朱健配合完成
        );
        $this->load->model('mail_model');
        $this->mail_model->coupon($data);
    }
    
    public function shippingUpdate(){
        $data = array(
            // 我们的信息
            'shopurl' => 'http://www.drgrab.com.au/', // 发送地址
            'shopmail' => 'support@drgrab.com.au', // 加上各个国家的后缀，例如drgrab.com.au
            // 用户的信息
            'to' => '282227460@qq.com', // 游客留下的邮箱
            // 等待朱健配合完成
        );
        $this->load->model('mail_model');
        $this->mail_model->shippingUpdate($data);
    }
}