<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login extends MY_Controller {

    private $terminal;

    function __construct() {
        parent::__construct();
        $this->page['title'] = 'Login';
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
    }

    public function index() {
        if ($this->session->userdata('member_email')) {
            $this->load->helper('url');
            redirect('/');
            die();
        }
        $this->load->helper('form');
        $this->page['refererUrl'] = $this->input->server('HTTP_REFERER') ? $this->input->server('HTTP_REFERER') : $this->page['domain'];
        $loginView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'login');
        $this->load->view($loginView, $this->page);
    }

}
