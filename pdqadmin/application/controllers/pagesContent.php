<?php

/**
 *  @说明  cms 内容控制器
 *  @作者  zhujian
 *  @qq    407284071
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class pagesContent extends Pc_Controller {

    public function __construct() {
        parent::__construct();
        parent::_active('pages');
        $this->country = $this->session->userdata('my_country');
        $this->load->model('pagecontent_model');
    }

    public function index() {
        $this->load->helper('form');

        $this->page['head'] = $this->load->view('head', $this->_category, true);
        $this->page['foot'] = $this->load->view('foot', $this->_category, true);
        $this->load->view('pageContentAdd', $this->page);
    }

    public function addPages() {
        if(empty($this->input->post('pages_title'))){
            redirect('Showerror/index/please enter Title');
        }
        if($this->input->post('isShow')==''||!in_array($this->input->post('isShow'),array(0,1))){
            redirect('Showerror/index/Visibility Error');
        }
        if(!empty($this->input->post('pages_title', TRUE))&&  mb_strlen($this->input->post('pages_title', TRUE))>60){
            redirect('Showerror/index/pages_title maxlength is 60');
        }
        if(!empty($this->input->post('description', TRUE))&&  mb_strlen($this->input->post('description', TRUE))>160){
            redirect('Showerror/index/description maxlength is 160');
        }
        $time = time();
        $data = array(
            '_id' => $time,
            'country' => $this->country,
            'pages_title' => $this->input->post('pages_title', TRUE),
            'pages_content' => $this->input->post('pages_content'),
            'seo_title' => $this->input->post('seo_title', TRUE),
            'description' => $this->input->post('description'),
            'url' => $this->input->post('url')?preg_replace('/\s+/','-',$this->input->post('url', TRUE)):'',
            'isShow' => $this->input->post('isShow', TRUE),
            'create_time' => $time,
            'update_time' => $time
        );

        if ($this->pagecontent_model->addPages($data)) {
            redirect("pages");
        } else {
             redirect('Showerror/index/Error');
        }
    }

    public function updatePages($id) {
        $this->load->helper('form');
        $this->page['PagesContent'] = $this->pagecontent_model->getPagesContent($id);
        $this->page['head'] = $this->load->view('head', $this->_category, true);
        $this->page['foot'] = $this->load->view('foot', $this->_category, true);
        $this->load->model('country_model');
        $countryInfo = $this->country_model->getInfoByCode($this->country, array('domain'));
        $this->page['domain'] = $countryInfo['domain'];
        $this->load->view('pageContentUpdate', $this->page);
    }

    public function amendPages() {
        if(empty($this->input->post('pages_title'))){
            redirect('Showerror/index/please enter Title');
        }
        if($this->input->post('isShow')==''||!in_array($this->input->post('isShow'),array(0,1))){
            redirect('Showerror/index/Visibility Error');
        }
        if(!empty($this->input->post('pages_title', TRUE))&&  mb_strlen($this->input->post('pages_title', TRUE))>60){
            redirect('Showerror/index/pages_title maxlength is 60');
        }
        if(!empty($this->input->post('description', TRUE))&&  mb_strlen($this->input->post('description', TRUE))>160){
            redirect('Showerror/index/description maxlength is 160');
        }
        $id = $this->input->post('id', TRUE);
        $data = array(
            'pages_title' => $this->input->post('pages_title', TRUE),
            'pages_content' => $this->input->post('pages_content'),
            'seo_title' => $this->input->post('seo_title', TRUE),
            'description' => $this->input->post('description'),
            'url' => $this->input->post('url')?preg_replace('/\s+/','-',$this->input->post('url', TRUE)):'',
            'isShow' => $this->input->post('isShow', TRUE),
            'update_time' => time()
        );

        if ($this->pagecontent_model->updatePages($id, $data)) {
            redirect("pagesContent/updatePages/$id");
        } else {
           redirect('Showerror/index/Error');
        }
    }

    public function delPages() {
        $id = $this->input->post('id', TRUE);

        if ($this->pagecontent_model->delPages($id)) {
            redirect("pages");
        } else {
             redirect('Showerror/index/Error');
        }
    }

}

?>