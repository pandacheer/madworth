<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Reg extends MY_Controller {

    public $template_country;
    private $terminal;

    function __construct() {
        parent::__construct();
        $this->page['title'] = 'Register';
        $this->terminal = $this->session->userdata('isMobile');
        $this->load->model('template_model');
        $headView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'head');
        $this->page['head'] = $this->load->view($headView, $this->page, true);
        $footLogosView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'foot_logos');
        $this->page['footLogosView'] = $this->load->view($footLogosView, $this->page, true);
        $footView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'foot');
        $this->page['foot'] = $this->load->view($footView, $this->page, true);
        $shoppingcartView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'shoppingcart');
        $this->page['shoppingcart'] = $this->load->view($shoppingcartView, $this->page, true);
        $this->template_country = $this->page['country'];
    }

    public function index() {
        if ($this->session->userdata('member_email')) {
            $this->load->helper('url');
            redirect('/');
            die();
        }

        $this->load->helper('form');
        $this->page['refererUrl'] = urlencode($this->input->server('HTTP_REFERER') ? $this->input->server('HTTP_REFERER') : $this->page['domain']);
        $regView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'reg');
        $this->load->view($regView, $this->page);
    }

    public function add() { //注册
        if ($this->input->is_ajax_request()) {
            $template_country = $this->page['country'];

            $this->load->library('form_validation');
            $this->load->helper('language');
            $this->lang->load('sys_reg');

            if ($this->session->userdata('Verification')) {
                $verifySession = $this->session->userdata('Verification');
                $verifySession['clickTimes'] += 1;
                $this->session->set_userdata('Verification', $verifySession);
            } else {
                $this->session->set_userdata('Verification', array('clickTimes' => 1, 'verifyCode' => ''));
            }
            if ($this->session->userdata('Verification')['clickTimes'] > 3) {//对验证码
                if (strtolower($this->input->post('myCode')) != $this->session->userdata('Verification')['verifyCode']) {
                    exit(json_encode(array('success' => FALSE, 'clickTimes' => $this->session->userdata('Verification')['clickTimes'], 'errorMessage' => lang('reg_codeError'))));
                }
            }


            $this->form_validation->set_rules('email', 'lang:reg_Email', "strtolower|required|valid_email|is_unique[{$template_country}_member.member_email]");
            $this->form_validation->set_rules('password', 'lang:reg_Password', 'required|alpha_dash|min_length[5]|max_length[20]');
            $this->form_validation->set_rules('verifyPassword', 'lang:reg_verifyPassword', 'required|matches[password]');

            if ($this->form_validation->run() == FALSE) {
                exit(json_encode(array('success' => FALSE, 'clickTimes' => $this->session->userdata('Verification')['clickTimes'], 'errorMessage' => validation_errors())));
            } else {
                $this->session->unset_userdata('Verification');
                $post['email'] = strtolower(trim($this->input->post('email')));
                $post['password'] = $this->input->post('password');

                //添加数据到MYSQL，并判断是否成功，成功则放入session
                $this->load->model('member_model');
                $result = $this->member_model->insert($this->page['country'], $post['email'], $post['password'], false);
                if ($result) {
                    //自动登录
                    $key = $this->config->item('encryption_key');
                    $this->session->set_userdata(['member_name' => '', 'member_email' => $post['email'], 'member_id' => $result['member_id'], 'auth' => md5($key . $post['email'])]);
                    //登录成功后判断是否有购物车内容 有的话添加到表中o(^▽^)o start
                    $this->load->helper('cookie');
                    $arr = $this->input->cookie('cart');
                    if ($arr) {
                        $products = unserialize($arr);
                        $this->load->model('cart_model');
                        if ($this->cart_model->addCart_login($this->page['country'], $post['email'], $products)) {
                            delete_cookie("cart");
                        }
                    }
                    //发电子邮件
                    $this->_sendVerifyMail($post['email']);
                    exit(json_encode(array('success' => TRUE)));
                } else {
                    //跳转错误页面 
                    exit(json_encode(array('success' => FALSE, 'clickTimes' => $this->session->userdata('Verification')['clickTimes'], 'errorMessage' => lang('reg_dbError'))));
                }
            }
        } else {
            $this->load->helper('language');
            $this->lang->load('sys_error');
            $this->page['errorMessage'] = lang('badAjax');
            $showErrorView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'showError');
            $this->load->view($showErrorView, $this->page);
        }
    }

    public function login() {//登录
        if ($this->input->is_ajax_request()) {
            $this->load->helper('language');
            $this->lang->load('sys_login');
            if ($this->session->userdata('Verification')) {
                $verifySession = $this->session->userdata('Verification');
                $verifySession['clickTimes'] += 1;
                $this->session->set_userdata('Verification', $verifySession);
            } else {
                $this->session->set_userdata('Verification', array('clickTimes' => 2, 'verifyCode' => ''));
            }
            if ($this->session->userdata('Verification')['clickTimes'] > 3) {//对验证码
                if (strtolower($this->input->post('verifyCode')) != $this->session->userdata('Verification')['verifyCode']) {
                    exit(json_encode(array('success' => FALSE, 'clickTimes' => $this->session->userdata('Verification')['clickTimes'], 'errorMessage' => lang('login_code_error'))));
                }
            }
            $data['member_email'] = strtolower(trim($this->input->post('myEmail')));
            $data['member_pwd'] = $this->input->post('myPassword');

            $this->load->library('form_validation');
            //验证规则
            $this->form_validation->set_rules('myEmail', 'lang:login_Email', "required|valid_email");
            $this->form_validation->set_rules('myPassword', 'lang:login_Password', 'required|alpha_dash|min_length[5]|max_length[20]');

            //判断验证规则
            if (!$this->form_validation->run()) {
                exit(json_encode(array('success' => FALSE, 'clickTimes' => $this->session->userdata('Verification')['clickTimes'], 'errorMessage' => validation_errors())));
            } else {

                $this->load->model('member_model');
                $memberInfo = $this->member_model->login($this->page['country'], $data['member_email'], $data['member_pwd']);
                if (is_array($memberInfo)) {
                    if (count($memberInfo) == 2) {//shopify老用户未验证时 $memberInfo为member_id
                        $this->member_model->updatePersonal($this->page['country'], $memberInfo['member_id'], 2, $data['member_pwd'], '', '');
                        $this->_sendVerifyMail($data['member_email'], $memberInfo['member_name']);
                        exit(json_encode(array('success' => FALSE, 'email' => $data['member_email'], 'errorMessage' => "shopify")));
                    }
                    $this->session->unset_userdata('Verification');
                    //登录成功后判断是否有购物车内容 有的话添加到表中o(^▽^)o start
                    $this->load->helper('cookie');
                    $arr = $this->input->cookie('cart');
                    if ($arr) {
                        $products = unserialize($arr);
                        $this->load->model('cart_model');
                        $result = $this->cart_model->addCart_login($this->page['country'], $data['member_email'], $products);
                        if ($result) {
                            delete_cookie("cart");
                        }
                    }
                    //end
                    $key = $this->config->item('encryption_key');
                    $memberInfo['auth'] = md5($key . $memberInfo['member_email']);
                    $this->session->set_userdata($memberInfo);
                    exit(json_encode(array('success' => TRUE)));
                } else {
                    exit(json_encode(array('success' => FALSE, 'clickTimes' => $this->session->userdata('Verification')['clickTimes'], 'errorMessage' => lang($memberInfo))));
                }
            }
        } else {
            $this->load->helper('language');
            $this->lang->load('sys_error');
            $this->page['errorMessage'] = lang('badAjax');
            $showErrorView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'showError');
            $this->load->view($showErrorView, $this->page);
        }
    }

    //发送激活邮件
    function _sendVerifyMail($member_email, $member_name = '') {
        $this->load->helper('encryption');
        $invalid_time = 1296000; //失效时间为当前时间+15天
        $forget_time = time();
        $forget_salt = createSalt();
        $forget_email_md5 = encryption($member_email, $forget_salt);
        $this->load->model('memberforget_model');
        $forget_id = $this->memberforget_model->insert($this->page['country'], $forget_email_md5, $forget_salt, $forget_time, $forget_time + $invalid_time, 8, 0);
        $data = array(
            'shopurl' => 'http://' . $this->input->server('HTTP_HOST') . '/',
            'shopmail' => $this->page['service_mail'],
            'to' => $member_email, // 游客留下的邮箱
            'name' => $member_name ? $member_name : 'member',
            'reseturl' => site_url('forget/verifyMail/' . $forget_id . '/' . $forget_email_md5 . '/' . $member_email) // 找回密码连接
        );
        $this->load->model('mail_model');
        if ($member_name) {
            $this->mail_model->shopifyConfirmation($data);
        } else {
            $this->mail_model->confirmation($data);
        }
    }

    public function logOut() { //退出
        $this->session->unset_userdata('member_id');
        $this->session->unset_userdata('member_name');
        $this->session->unset_userdata('member_email');
        redirect('/');
    }

    public function comparison() { //异步对比验证码
        if ($this->input->is_ajax_request()) {
            $verifyCode = strtolower($this->input->post('verifyCode'));
            exit($verifyCode == $this->session->userdata('Verification')['verifyCode']);
        }
    }

    public function validationEmail() { //异步对比邮箱是否重复
        if ($this->input->is_ajax_request()) {
            $this->load->library('form_validation');
            $template_country = $this->page['country'];
            $this->input->post('email');
            $this->form_validation->set_rules('email', '', "required|valid_email|is_unique[{$template_country}_member.member_email]");
            exit($this->form_validation->run());
        } else {
            $this->load->helper('language');
            $this->lang->load('sys_error');
            $this->page['errorMessage'] = lang('badAjax');
            $showErrorView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'showError');
            $this->load->view($showErrorView, $this->page);
        }
    }

    public function vcode() {  //验证码
        $this->load->library('Vcode');
        $code = strtolower($this->vcode->getcode());
        $verifySession = $this->session->userdata('Verification');
        if (!$this->session->userdata('Verification')) {
            $verifySession['clickTimes'] = 0;
        }
        $verifySession['verifyCode'] = $code;
        $this->session->set_userdata('Verification', $verifySession);
        echo $this->vcode->outimg();
    }

    function fbcallback() {
        $redirecturl = $this->input->get('redirecturl');
        if (!$redirecturl)
            $redirecturl = '/';
        $error = $this->input->get('error');
        if ($error) {
            redirect($redirecturl);
        }
        $helper = $this->fb->getRedirectLoginHelper();
        try {
            $accessToken = $helper->getAccessToken();
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            redirect($redirecturl);
            //echo 'Graph returned an error: ' . $e->getMessage();
            //exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            redirect($redirecturl);
            //echo 'Facebook SDK returned an error: ' . $e->getMessage();
            //exit;
        }
        if (!isset($accessToken)) {
            redirect($redirecturl);
            /*
              if ($helper->getError()) {
              header('HTTP/1.0 401 Unauthorized');
              echo "Error: " . $helper->getError() . "\n";
              echo "Error Code: " . $helper->getErrorCode() . "\n";
              echo "Error Reason: " . $helper->getErrorReason() . "\n";
              echo "Error Description: " . $helper->getErrorDescription() . "\n";
              } else {
              header('HTTP/1.0 400 Bad Request');
              echo 'Bad request';
              }
              exit;
             * 
             */
        }
        $oAuth2Client = $this->fb->getOAuth2Client();
        if (!$accessToken->isLongLived()) {
            try {
                $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
            } catch (Facebook\Exceptions\FacebookSDKException $e) {
                redirect($redirecturl);
                //echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
                //exit;
            }
        }
        $res = $this->fb->get('/me?fields=id,email,name,gender,birthday,first_name,last_name', $accessToken->getValue());
        $data = $res->getDecodedBody();
        if (isset($data['birthday']) && !empty($data['birthday'])) {
            $data['birthday'] = strtotime($data['birthday']) ? strtotime($data['birthday']) : 0;
        }
        if (isset($data['gender']) && !empty($data['gender'])) {
            $data['gender'] = $data['gender'] == 'male' ? 1 : ($data['gender'] == 'female' ? 2 : 3);
        }
        $this->load->model('member_model');
        $memberInfo = $this->member_model->thirdlogin($this->page['country'], $data['id']);
        if (is_array($memberInfo)) {
            //登录成功后判断是否有购物车内容 有的话添加到表中o(^▽^)o start
            $this->load->helper('cookie');
            $arr = $this->input->cookie('cart');
            if ($arr) {
                $products = unserialize($arr);
                $this->load->model('cart_model');
                $result = $this->cart_model->addCart_login($this->page['country'], $memberInfo['member_email'], $products);
                if ($result) {
                    delete_cookie("cart");
                }
            }
            //end
            if (empty($memberInfo['member_name']) || empty($memberInfo['member_firstName']) || empty($memberInfo['member_lastName']) || empty($memberInfo['member_gender']) || empty($memberInfo['member_birthday'])) {
                $memberInfoData = $memberData = array();
                if (empty($memberInfo['member_name']) && (!empty($data['first_name']) || !empty($data['last_name']))) {
                    $memberData['member_name'] = $data['first_name'] . ' ' . $data['last_name'];
                }
                if (empty($memberInfo['member_firstName']) && !empty($data['first_name'])) {
                    $memberData['member_firstName'] = $data['first_name'];
                }
                if (empty($memberInfo['member_lastName']) && !empty($data['last_name'])) {
                    $memberData['member_lastName'] = $data['last_name'];
                }
                if ($memberInfo['member_gender']==3 && !empty($data['gender'])) {
                    $memberInfoData['member_gender'] = $data['gender'];
                }
                if (empty($memberInfo['member_birthday']) && !empty($data['birthday'])) {
                    $memberInfoData['member_birthday'] = $data['birthday'];
                }
                $this->member_model->updatePersonalviathird($this->page['country'], $memberInfo['member_id'], $memberInfoData, $memberData);
            }
            $key = $this->config->item('encryption_key');
            $memberInfo['auth'] = md5($key . $memberInfo['member_email']);
            $this->session->set_userdata($memberInfo);
            redirect($redirecturl);
        } else {
            $_SESSION['fb_id'] = $data['id'];
            $_SESSION['fbredirecttoken'] = md5(time() . mt_srand(1000, 999));
            $_SESSION['fbemail'] = $data['email'];
            $_SESSION['first_name'] = $data['first_name'];
            $_SESSION['last_name'] = $data['last_name'];
            $_SESSION['birthday'] = $data['birthday'];
            $_SESSION['gender'] = $data['gender'];
            redirect('facebooklogin/index/' . $data['email'] . "/" . $_SESSION['fbredirecttoken'] . "/" . $redirecturl);
        }
    }

    function checkfblogin() {
        $redirecturl = $this->input->post('redirecturl');
        if (!$redirecturl)
            $redirecturl = '/';
        if (!isset($_SESSION['fb_id'])) {
            redirect('home/showError404');
        }
        $data = $this->input->post();
        if (empty($data)) {
            redirect('home/showError404');
        }
        $data['id'] = $_SESSION['fb_id'];
        unset($_SESSION['fb_id']);
        $this->load->model('member_model');
        $memberInfo = $this->member_model->getInfoByEmail($this->page['country'], $data['email']);
        if (!isset($memberInfo['member_id']) || empty($memberInfo['member_id'])) {
            //自动注册
            $result = $this->_register($this->page['country'], $data['email']);
            if ($result && isset($result['member_id'])) {
                $memberInfo['member_id'] = $result['member_id'];
            } else {
                redirect('home/showError404');
            }
        }
        if ($data['email'] == $_SESSION['fbemail']) {
            $memberInfo = $this->member_model->autoLogin($this->page['country'], $data['email']);
            if (is_array($memberInfo)) {
                $this->session->unset_userdata('Verification');
                //登录成功后判断是否有购物车内容 有的话添加到表中o(^▽^)o start
                $this->load->helper('cookie');
                $arr = $this->input->cookie('cart');
                if ($arr) {
                    $products = unserialize($arr);
                    $this->load->model('cart_model');
                    $result = $this->cart_model->addCart_login($this->page['country'], $memberInfo['member_email'], $products);
                    if ($result) {
                        delete_cookie("cart");
                    }
                }
                //end
                if (empty($memberInfo['member_name']) || empty($memberInfo['member_firstName']) || empty($memberInfo['member_lastName']) || empty($memberInfo['member_gender']) || empty($memberInfo['member_birthday'])) {
                    $memberInfoData = $memberData = array();
                    if (empty($memberInfo['member_name']) && (!empty($_SESSION['first_name']) || !empty($_SESSION['last_name']))) {
                        $memberData['member_name'] = $_SESSION['first_name'] . ' ' . $_SESSION['last_name'];
                    }
                    if (empty($memberInfo['member_firstName']) && !empty($_SESSION['first_name'])) {
                        $memberData['member_firstName'] = $_SESSION['first_name'];
                    }
                    if (empty($memberInfo['member_lastName']) && !empty($_SESSION['last_name'])) {
                        $memberData['member_lastName'] = $_SESSION['last_name'];
                    }
                    if ($memberInfo['member_gender']==3 && !empty($_SESSION['gender'])) {
                        $memberInfoData['member_gender'] = $_SESSION['gender'];
                    }
                    if (empty($memberInfo['member_birthday']) && !empty($_SESSION['birthday'])) {
                        $memberInfoData['member_birthday'] = $_SESSION['birthday'];
                    }
                    $this->member_model->updatePersonalviathird($this->page['country'], $memberInfo['member_id'], $memberInfoData, $memberData);
                }
                $key = $this->config->item('encryption_key');
                $memberInfo['auth'] = md5($key . $memberInfo['member_email']);
                $this->session->set_userdata($memberInfo);
                unset($_SESSION['fbemail'], $_SESSION['first_name'], $_SESSION['last_name'], $_SESSION['gender'], $_SESSION['birthday']);
                redirect($redirecturl);
            }
        } else {
            $this->load->helper('encryption');
            $invalid_time = 86400;
            $forget_time = time();
            $forget_salt = createSalt();
            $a = '';
            if (!empty($_SESSION['first_name'])) {
                $a .= '-' . $_SESSION['first_name'];
            } else {
                $a .= '-';
            }
            if (!empty($_SESSION['last_name'])) {
                $a .= '-' . $_SESSION['last_name'];
            } else {
                $a .= '-';
            }
            if (!empty($_SESSION['gender'])) {
                $a .= '-' . $_SESSION['gender'];
            } else {
                $a .= '-';
            }
            if (!empty($_SESSION['birthday'])) {
                $a .= '-' . $_SESSION['birthday'];
            } else {
                $a .= '-';
            }
            unset($_SESSION['fbemail'], $_SESSION['first_name'], $_SESSION['last_name'], $_SESSION['gender'], $_SESSION['birthday']);
            $forget_email_md5 = encryption($memberInfo['member_id'] . '-' . $data['id'] . $a, $forget_salt);
            $this->load->model('memberforget_model');
            $forget_id = $this->memberforget_model->insert($this->page['country'], $forget_email_md5, $forget_salt, $forget_time, $invalid_time + $forget_time, 3, 0);
            if ($forget_id) {
                $member_name = isset($memberInfo['member_name']) ? $memberInfo['member_name'] : '';
                $forget_link = site_url('forget/authorization/' . $forget_id . '/' . $forget_email_md5 . '/' . $memberInfo['member_id'] . '-' . $data['id'] . $a);
                $datas = array(
                    'shopurl' => $this->page['domain'],
                    'shopmail' => $this->page['service_mail'],
                    'to' => $data['email'],
                    'confirmlink' => $forget_link,
                    'name' => $member_name
                );
                $this->load->model('mail_model');
                $this->mail_model->authorizationThird($datas);
                $this->page['email'] = $datas['to'];
                $this->page['jumpUrl'] = $this->page['domain'];
                $this->load->view('pleaseconfirmemail', $this->page);
            }
        }
    }

    //返回$actionEmail对应的member_id;
    function _register($country_code, $actionEmail) {// 注册用户
        if (!filter_var($actionEmail, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        $this->load->model('member_model');
        $member = $this->member_model->insert($country_code, $actionEmail, 0, true);
        if ($member ['member_id']) {
            if (array_key_exists('member_pwd', $member)) {// 发送用户注册成功邮件
                $this->registeredEmail($actionEmail, $member['member_pwd']);
            }
            return $member;
        } else {
            return false; //redirect("home/registered_error");
        }
    }

    /**
     * 注册成功发送邮件
     * @param 用户邮件 $member_email
     * @param 用户密码 $member_pwd
     * 
     */
    private function registeredEmail($member_email, $member_pwd, $member_name = '') {
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
                    'shopurl' => $this->page['domain'],
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

}
