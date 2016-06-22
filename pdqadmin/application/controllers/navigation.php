<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class navigation extends Pc_Controller {

    protected $str = '';
    var $pageCountry, $userAccount, $userID;

    public function __construct() {
        parent::__construct();
        parent::_active('navigation');
        $this->pageCountry = $this->session->userdata('my_country');
        $this->userAccount = $this->session->userdata('user_account');
        $this->userID = $this->session->userdata('user_id');
    }

    public function index() {
        $this->page['head'] = $this->load->view('head', $this->_category, true);
        $this->load->model('navigation_model');
        $data = $this->navigation_model->getnav($this->pageCountry);
        if (isset($data)) {
            unset($data['_id']);
            $this->page['navigation'] = $this->_recur($data);
        } else {
            $this->page['navigation'] = '';
        }
        $this->page['foot'] = $this->load->view('foot', $this->_category, true);
        $this->load->model('country_model');
        $this->load->model('language_model');
        $this->page['language'] = $this->language_model->listData();
        foreach ($this->page['language'] as $key => $language_code){
            $c = $this->country_model->getCountryByLangCode($key);
            $c = array_diff($c,array($this->pageCountry));
            $country[$key] = $c;
        }
        $this->page['country'] = $country;
        $this->load->view('editnav', $this->page);
    }

    private function _recur($data) {
        foreach ($data as $vo) {
            $this->str .= '<li class="dd-item" data-msg="' . $vo['msg'] . '"><div class="dd-handle"><a href="Item" class="itemedit">' . $vo['msg'] . '</a></div><b class="dd-handle-bedit" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-pencil fa-fw"></i></b>';
            if (isset($vo['children'])) {
                $this->str .= '<ol class="dd-list">';
                $this->_recur($vo['children']);
                $this->str .= '</ol>';
            }
            $this->str .= '</li>';
        }
        return $this->str;
    }

    public function update() {
        $data = $this->input->post();
        $json = json_decode($data['navigation']);
        $this->load->model('navigation_model');
        $country = array($this->pageCountry);
        foreach ($data as $key => $value) {
            if (substr_count($key, 'lang') > 0) {
                $country = array_merge_recursive($country, $data[$key]);
            }
        }
        $res = true;
        foreach ($country as $kc=>$vc){
            $result = $this->navigation_model->update($vc, $json);
            if($vc==$this->pageCountry){
                $res = $result;
            }
        }
        if ($res) {
            redirect('navigation');
        } else {
            redirect('Showerror/index/Error');
        }
    }

}
