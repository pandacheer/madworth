<?php

/**
 * @文件： country
 * @时间： 2015-6-23 14:48:00
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：
 */
class Country extends Pc_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('country_model');
    }

    function index() {

        $this->load->view('system/v_country');
    }

    function loadData() {
        $page = $this->input->post('page') ? $this->input->post('page') : 1;
        $per_page = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'status';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
        $offset = ($page - 1) * $per_page;
        $whereData = array();
        $total = $this->country_model->count($whereData);
        echo json_encode($this->country_model->loadData($whereData, $sort, $order, $offset, $per_page, $total));
    }

    function statusChange() {
        $country_id = $this->input->post('country_id');
        $country_code = $this->input->post('country_code');
        $country_status = (int) $this->input->post('country_status');
        $whereData = array(
            'country_id' => $country_id,
            'status' => $country_status
        );
        $updateData = array(
            'status' => $country_status == 1 ? 2 : 1
        );

        if ($this->country_model->update($whereData, $updateData, $country_code)) {
            exit(json_encode(array('success' => TRUE, 'status' => $updateData['status'])));
        } else {
            exit(json_encode(array('success' => FALSE, 'error' => '数据库操作失败！！')));
        }
    }

    function loadEditDialog() {
        $this->load->view('system/v_countryDialog');
    }

    function update() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('domain', '域名', 'required');
        $this->form_validation->set_rules('flag_sort', '国旗排序', 'required');
        $this->form_validation->set_rules('language_code', '语种', 'required');
        $this->form_validation->set_rules('currency_symbol', '货币符号', 'required');
        $this->form_validation->set_rules('currency_payment', '货币类型代码', 'required');
        $this->form_validation->set_rules('au_rate', '澳币对换外币', 'required|decimal');
        $this->form_validation->set_rules('timezone', '时区', 'required');
        $this->form_validation->set_rules('google', 'Google统计', 'required');
        $this->form_validation->set_rules('facebook', 'FaceBook链接', 'required');
        $this->form_validation->set_rules('facebook_id', 'FaceBook ID', 'required');
        $this->form_validation->set_rules('service_mail', '服务邮件', 'required');
        
        

        $this->form_validation->set_error_delimiters('', '<br>');
        if ($this->form_validation->run() == FALSE) {
            $error = validation_errors();
            exit(json_encode(array('success' => FALSE, 'error' => $error)));
        }
        $whereData = array(
            'country_id' => $this->input->post('country_id')
        );
        $updateData = array(
            'domain' => $this->input->post('domain'),
            'flag_sort' => $this->input->post('flag_sort'),
            'language_code' => $this->input->post('language_code'),
            'currency_symbol' => $this->input->post('currency_symbol'),
            'currency_payment' => $this->input->post('currency_payment'),
            'au_rate' => $this->input->post('au_rate'),
            'timezone' => $this->input->post('timezone'),
            'google'=>$this->input->post('google'),
            'facebook'=>$this->input->post('facebook'),
            'facebook_id'=>$this->input->post('facebook_id'),
            'service_mail'=>$this->input->post('service_mail')
                
        );
        if ($this->country_model->update($whereData, $updateData, $this->input->post('iso_code_2'))) {
            exit(json_encode(array('success' => TRUE)));
        } else {
            exit(json_encode(array('success' => FALSE, 'error' => '数据库操作失败！！')));
        }
    }

    function combobox() {
        echo json_encode($this->country_model->combobox());
    }

}
