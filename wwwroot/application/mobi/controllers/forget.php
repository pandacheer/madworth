<?php

/**
 * @文件： forget
 * @时间： 2015-7-31 11:21:28
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：忘记密码
 */
class forget extends MY_Controller {

    private $terminal;

    function __construct() {
        parent::__construct();
        $this->page['title'] = 'Reset Your Password';
        $this->terminal = $this->session->userdata('isMobile');
        $this->load->model('template_model');
        $headView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'head');
        $this->page['head'] = $this->load->view($headView, $this->page, true);
        //$footLogosView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'foot_logos');
        //$this->page['footLogosView'] = $this->load->view($footLogosView, $this->page, true);
        $footView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'foot');
        $this->page['foot'] = $this->load->view($footView, $this->page, true);
        //$shoppingcartView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'shoppingcart');
        //$this->page['shoppingcart'] = $this->load->view($shoppingcartView, $this->page, true);
    }

    function index() {
        if ($this->session->userdata('member_email')) {
            $this->load->helper('url');
            redirect('/');
            die();
        }
        $this->load->helper('form');
        $forgetPasswordView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'forget-password');
        $this->load->view($forgetPasswordView, $this->page);
    }

    function send() {
        $invalid_time = 172800; //失效时间为当前时间+2天
        if ($this->input->is_ajax_request()) {
            $this->load->helper('language');
            $this->lang->load('sys_forget');
            $this->load->helper('encryption');
            $forget_email = strtolower($this->input->post('forgetEmail'));
            $this->load->model('member_model');
            $check = $this->member_model->checkEmail($this->page['country'], $forget_email);
            if ($check) {
                $forget_time = time();
                $forget_salt = createSalt();
                $forget_email_md5 = encryption($forget_email, $forget_salt);
                $this->load->model('memberforget_model');
                $forget_id = $this->memberforget_model->insert($this->page['country'], $forget_email_md5, $forget_salt, $forget_time, $invalid_time + $forget_time, 1, $check['status'] < 8 ? 0 : 1);
                if ($forget_id) {
                    $forget_link = site_url('forget/replace/' . $forget_id . '/' . $forget_email_md5 . '/' . $forget_email);
                    $data = array(
                        'shopurl' => $this->page['domain'],
                        'shopmail' => $this->page['service_mail'],
                        'to' => $forget_email,
                        'reseturl' => $forget_link,
                        'name' => $check['member_name']
                    );
                    $this->load->model('mail_model');
                    $result = ($this->mail_model->passwordreset($data)) ? array('success' => true, 'message' => lang('forget_sendSuccess')) : array('success' => false, 'message' => lang('forget_sendFail'));
                } else {
                    $result = array('success' => false, 'message' => lang('forget_sendFail'));
                }
            } else {
                $result = array('success' => false, 'message' => lang('forget_emailError'));
            }
            exit(json_encode($result));
        } else {
            $this->load->helper('language');
            $this->lang->load('sys_error');
            $this->page['errorMessage'] = lang('badAjax');
            $showErrorView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'showError');
            $this->load->view($showErrorView, $this->page);
        }
    }

    //调出修改密码页面
    //if $row['forget_type'] >2 不能调出页面
    function replace() {
        $this->load->helper('language');
        $this->lang->load('sys_forget');
        $this->load->model('memberforget_model');
        $search = array(
            'forget_id' => (int) $this->uri->segment(3, 0),
            'forget_email' => $this->uri->segment(4),
            'true_email' => $this->uri->segment(5)
        );
        $row = $this->memberforget_model->getLinkData($search['forget_id']);
        if ($row) {//如果找到
            if (!$row['forget_status']) {//如果需要验证
                $this->memberforget_model->verifyMail($this->page['country'], $search['forget_id'], $search['true_email'], 0, $row['forget_type']);
            }
            if (time() > $row['invalid_time'] || $row['forget_email'] !== $search['forget_email'] || $row['forget_type'] > 2 || $this->page['country'] != $row['country_code']) {
                $this->load->helper('form');
                $this->page['errorMessage'] = lang('forget_linkInvalid');
                $showErrorView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'showError');
                $this->load->view($showErrorView, $this->page);
            } else {
                $this->load->helper('form');
                $this->page['forget_id'] = $search['forget_id'];
                $this->page['forget_email'] = $row['forget_email'];
                $this->page['true_email'] = $this->uri->segment(5);
                $resetPasswordView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'reset-password');
                $this->load->view($resetPasswordView, $this->page);
            }
        } else {
            $this->load->helper('form');
            $this->page['errorMessage'] = lang('forget_linkInvalid');
            $showErrorView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'showError');
            $this->load->view($showErrorView, $this->page);
        }
    }

    function update() {
        $this->load->helper('language');
        $this->lang->load('sys_forget');
        $this->load->model('memberforget_model');

        $check_Token = $this->input->post('check_Token');
        $check_id = (int) $this->input->post('check_id');
        $forget_email = strtolower(trim($this->input->post('forget_email')));

        $row = $this->memberforget_model->getLinkData($check_id);
        if ($row) {//如果找到
            if (time() > $row['invalid_time'] || $check_Token !== $row['forget_email'] || $this->page['country'] != $row['country_code']) {
                $this->page['errorMessage'] = lang('forget_linkInvalid');
                $showErrorView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'showError');
                $this->load->view($showErrorView, $this->page);
            } else {
                $this->load->helper('encryption');
                if (encryption($forget_email, $row['forget_salt']) !== $check_Token) {
                    $this->page['errorMessage'] = lang('forget_linkInvalid');
                    $showErrorView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'showError');
                    $this->load->view($showErrorView, $this->page);
                } else {
                    $this->load->library('form_validation');
                    $this->form_validation->set_rules('password', 'lang:forget_Password', 'required|alpha_dash|min_length[5]|max_length[20]');
                    $this->form_validation->set_rules('verifyPassword', 'lang:forget_verifyPassword', 'required|matches[password]');
                    //判断规则是否是通过
                    if ($this->form_validation->run() == FALSE) {
                        $this->page['errorMessage'] = validation_errors();
                        $showErrorView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'showError');
                        $this->load->view($showErrorView, $this->page);
                    } else {
                        $this->load->model('memberforget_model');
                        $result = $this->memberforget_model->changePwd($this->page['country'], $check_id, $forget_email, $this->input->post('password'));
                        if ($result) {
                            $this->load->model('member_model');
                            $memberInfo = $this->member_model->autoLogin($this->page['country'], $forget_email);
                            if (is_array($memberInfo)) {
                                $this->session->unset_userdata('Verification');
                                //登录成功后判断是否有购物车内容 有的话添加到表中o(^▽^)o start
                                $this->load->helper('cookie');
                                $arr = $this->input->cookie('cart');
                                if ($arr) {
                                    $products = unserialize($arr);
                                    $this->load->model('cart_model');
                                    $result = $this->cart_model->addCart_login($this->page['country'], $forget_email, $products);
                                    if ($result) {
                                        delete_cookie("cart");
                                    }
                                }
                                //end
                                $key = $this->config->item('encryption_key');
                                $memberInfo['auth'] = md5($key . $memberInfo['member_email']);
                                $this->session->set_userdata($memberInfo);
                                redirect("home/showSuccess/S2004");
//                                $this->page['successMessage'] = lang('forget_updateSuccess');
//                                $showSuccessView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'showSuccess');
//                                $this->load->view($showSuccessView, $this->page);
                            }
                        } else {
                            //跳转错误页面
                            $this->page['errorMessage'] = lang('forget_updateFail');
                            $showErrorView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'showError');
                            $this->load->view($showErrorView, $this->page);
                        }
                    }
                }
            }
        } else {
            $this->page['errorMessage'] = lang('forget_linkInvalid');
            $showErrorView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'showError');
            $this->load->view($showErrorView, $this->page);
        }
    }

    //用户注册后的验证
    function verifyMail() {
        $this->load->helper('language');
        $this->lang->load('sys_forget');
        $this->load->model('memberforget_model');
        $search = array(
            'forget_id' => (int) $this->uri->segment(3, 0),
            'forget_email' => $this->uri->segment(4),
            'true_email' => $this->uri->segment(5)
        );
        $row = $this->memberforget_model->getLinkData($search['forget_id']);
        if ($row) {//如果找到 已验证 过期 国家代码不一致
            if ($row['forget_status'] == 1 || time() > $row['invalid_time'] || $row['forget_email'] !== $search['forget_email'] || $row['forget_type'] < 8 || $this->page['country'] != $row['country_code']) {
                $this->load->helper('form');
                $this->page['errorMessage'] = lang('forget_linkInvalid');
                $showErrorView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'showError');
                $this->load->view($showErrorView, $this->page);
            } else {
                $this->load->helper('encryption');
                if (encryption($search['true_email'], $row['forget_salt']) !== $search['forget_email']) {
                    $this->page['errorMessage'] = lang('forget_linkInvalid');
                    $showErrorView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'showError');
                    $this->load->view($showErrorView, $this->page);
                } else {
                    if ($this->memberforget_model->verifyMail($this->page['country'], $search['forget_id'], $search['true_email'], 1, $row['forget_type'])) {
                        $this->load->model('member_model');
                        $memberInfo = $this->member_model->autoLogin($this->page['country'], $search['true_email']);
                        if (is_array($memberInfo)) {
                            $this->session->unset_userdata('Verification');
                            //登录成功后判断是否有购物车内容 有的话添加到表中o(^▽^)o start
                            $this->load->helper('cookie');
                            $arr = $this->input->cookie('cart');
                            if ($arr) {
                                $products = unserialize($arr);
                                $this->load->model('cart_model');
                                $result = $this->cart_model->addCart_login($this->page['country'], $search['true_email'], $products);
                                if ($result) {
                                    delete_cookie("cart");
                                }
                            }
                            //end
                            $key = $this->config->item('encryption_key');
                            $memberInfo['auth'] = md5($key . $memberInfo['member_email']);
                            $this->session->set_userdata($memberInfo);
                            redirect("home/showSuccess/S2001");
                        }
                    }
                }
            }
        } else {
            $this->load->helper('form');
            $this->page['errorMessage'] = lang('forget_linkInvalid');
            $showErrorView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'showError');
            $this->load->view($showErrorView, $this->page);
        }
    }

    function authorization() {
        $this->load->helper('language');
        $this->lang->load('sys_forget');
        $this->load->model('memberforget_model');
        $search = array(
            'forget_id' => (int) $this->uri->segment(3, 0),
            'forget_email' => $this->uri->segment(4),
            'true_email' => $this->uri->segment(5)
        );
        $from = $this->uri->segment(6);
        if (empty($from))
            $from = 'fb';
        $row = $this->memberforget_model->getLinkData($search['forget_id']);
        if ($row) {//如果找到 已验证 过期 国家代码不一致
            if ($row['forget_status'] == 1 || time() > $row['invalid_time'] || $row['forget_email'] !== $search['forget_email'] || $row['forget_type'] != 3 || $this->page['country'] != $row['country_code']) {
                $this->load->helper('form');
                $this->page['errorMessage'] = lang('forget_linkInvalid');
                $showErrorView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'showError');
                $this->load->view($showErrorView, $this->page);
            } else {
                $this->load->helper('encryption');
                if (encryption($search['true_email'], $row['forget_salt']) !== $search['forget_email']) {
                    $this->page['errorMessage'] = lang('forget_linkInvalid');
                    $showErrorView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'showError');
                    $this->load->view($showErrorView, $this->page);
                } else {
                    if ($members = $this->memberforget_model->authorizationThird($this->page['country'], $search['forget_id'], $search['true_email'], 0, $from)) {
                        $member_id = $members[0];
                        $this->load->model('member_model');
                        $array = $this->member_model->getInfo($this->page['country'], $member_id, 'member_email');
                        $memberInfo = $this->member_model->autoLogin($this->page['country'], $array['member_email']);
                        if (is_array($memberInfo)) {
                            $this->session->unset_userdata('Verification');
                            //登录成功后判断是否有购物车内容 有的话添加到表中o(^▽^)o start
                            $this->load->helper('cookie');
                            $arr = $this->input->cookie('cart');
                            if ($arr) {
                                $products = unserialize($arr);
                                $this->load->model('cart_model');
                                $result = $this->cart_model->addCart_login($this->page['country'], $array['member_email'], $products);
                                if ($result) {
                                    delete_cookie("cart");
                                }
                            }
                            //end
                            if (empty($memberInfo['member_name']) || empty($memberInfo['member_firstName']) || empty($memberInfo['member_lastName']) || empty($memberInfo['member_gender']) || empty($memberInfo['member_birthday'])) {
                                $memberInfoData = $memberData = array();
                                if (empty($memberInfo['member_name']) && (!empty($members[1]) || !empty($members[2]))) {
                                    $memberData['member_name'] = $members[1] . ' ' . $members[2];
                                }
                                if (empty($memberInfo['member_firstName']) && !empty($members[1])) {
                                    $memberData['member_firstName'] = $members[1];
                                }
                                if (empty($memberInfo['member_lastName']) && !empty($members[2])) {
                                    $memberData['member_lastName'] = $members[2];
                                }
                                if ($memberInfo['member_gender']==3 && !empty($members[3])) {
                                    $memberInfoData['member_gender'] = $members[3];
                                }
                                if (empty($memberInfo['member_birthday']) && !empty($members[4])) {
                                    $memberInfoData['member_birthday'] = $members[4];
                                }
                                $this->member_model->updatePersonalviathird($this->page['country'], $memberInfo['member_id'], $memberInfoData, $memberData);
                            }
                            $key = $this->config->item('encryption_key');
                            $memberInfo['auth'] = md5($key . $memberInfo['member_email']);
                            $this->session->set_userdata($memberInfo);
                            redirect("home/showSuccess/S2001");
                        }
                    }
                }
            }
        } else {
            $this->load->helper('form');
            $this->page['errorMessage'] = lang('forget_linkInvalid');
            $showErrorView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'showError');
            $this->load->view($showErrorView, $this->page);
        }
    }

}
