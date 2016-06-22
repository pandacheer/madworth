<?php

/* * ���䶩��* */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Subscription extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->country = $this->page ['country'];
    }

    public function insert() {
        $time = time();
        $email = $this->input->post('email');
        $this->load->model('subscription_model');

        $this->load->library('form_validation');
        $this->form_validation->set_rules('email', "|required|valid_email|");
        if (!$this->form_validation->run()) {
//            sleep(2);
            exit(json_encode(array('status' => false)));
        }

        if ($this->subscription_model->checkMail($this->country, $email)) {
//            sleep(2);
            exit(json_encode(array('status' => false)));
        } else {
            $this->subscription_model->insert($this->country, $email, $time);
            $this->_sendVerifyMail($email);
//            sleep(2);
            exit(json_encode(array('status' => true)));
        }
    }

    //发送激活邮件
    function _sendVerifyMail($member_email) {
        $this->load->helper('encryption');
        $invalid_time = 1296000; //失效时间为当前时间+15天
        $forget_time = time();
        $forget_salt = createSalt();
        $forget_email_md5 = encryption($member_email, $forget_salt);
        $this->load->model('memberforget_model');
        $forget_id = $this->memberforget_model->insert($this->page['country'], $forget_email_md5, $forget_salt, $forget_time, $forget_time + $invalid_time, 9, 0);
        $data = array(
            'shopurl' => $this->page['domain'],
            'shopmail' => $this->page['service_mail'],
            'to' => $member_email, // 游客留下的邮箱
            'reseturl' => site_url('forget/verifyMail/' . $forget_id . '/' . $forget_email_md5 . '/' . $member_email) // 找回密码连接
        );
        $this->load->model('mail_model');
        $this->mail_model->confirmationSubscription($data);
    }

}
