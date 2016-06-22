<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class pages extends MY_Controller {

    private $terminal;

    public function __construct() {
        parent::__construct();
        $this->terminal = $this->session->userdata('isMobile');
        $this->load->model('template_model');


        $footView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'foot');
        $this->page['foot'] = $this->load->view($footView, $this->page, true);
    }

    public function index($url) {
        if (!$url) {
            redirect("/");
        }


        if ($url == "contact-us") {
            $this->page['title'] = 'Contact us';
            $headView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'head');
            $this->page['head'] = $this->load->view($headView, $this->page, true);
            $contactUsView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'contact-us');
            $this->load->view($contactUsView, $this->page);
        } else {
            $this->load->model('page_model');
            $this->page['data'] = $this->page_model->findSeo($this->page['country'], $url);
            if (!$this->page['data']) {
                redirect("/");
            }
            $this->page['title'] = $this->page['data']['seo_title'];
            $this->page['description'] = $this->page['data']['description'];
            $headView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'head');
            $this->page['head'] = $this->load->view($headView, $this->page, true);
            $pagesView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'pages');
            $this->load->view($pagesView, $this->page);
        }
    }

}

?>