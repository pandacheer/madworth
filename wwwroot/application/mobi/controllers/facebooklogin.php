<?php

class facebooklogin extends MY_Controller {


    function __construct() {
        parent::__construct();
        $this->page['title'] = 'Bind';
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

    public function index($email='',$code='',$redirecturl='') {
        if(empty($code)||!isset($_SESSION['fbredirecttoken'])||$code!=$_SESSION['fbredirecttoken']){
            redirect('home/showError404');
        }
        unset($_SESSION['fbredirecttoken']);
        $this->page['email'] = $email;
        $this->page['redirecturl'] = $redirecturl;
        $this->load->view('facebooklogin',$this->page);
    }

    
}
