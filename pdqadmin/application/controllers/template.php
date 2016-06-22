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
class Template extends Pc_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('template_model');
    }

    function index() {
        $this->load->view('system/v_template');
    }

    function loadData() {
        $page = $this->input->post('page') ? $this->input->post('page') : 1;
        $per_page = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'key';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
        $terminal_code = isset($_POST['terminal_code']) ? $_POST['terminal_code'] : 1;
        $countryCode = $this->input->post('country_code') ? $this->input->post('country_code') : "ALL";
        $offset = ($page - 1) * $per_page;
        $whereData = array('terminal' => $terminal_code, 'country_code' => $countryCode,);
        $total = $this->template_model->count($whereData);
        echo json_encode($this->template_model->loadData($whereData, $sort, $order, $offset, $per_page, $total));
    }

    function loadEditDialog() {
        $this->load->view('system/v_templateDialog');
    }

    function insert() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('country_code', '国家', 'required');
        $this->form_validation->set_rules('terminal_code', '终端', 'required');
        $this->form_validation->set_rules('key', '键值', 'required');
        $this->form_validation->set_rules('pub_about', '原视图说明', 'required');
        $this->form_validation->set_rules('public', '原视图文件名', 'required');
//        $this->form_validation->set_rules('private', '新视图名称', 'required');

        $this->form_validation->set_error_delimiters('', '<br>');
        if ($this->form_validation->run() == FALSE) {
            $error = validation_errors();
            exit(json_encode(array('success' => FALSE, 'error' => $error)));
        }
        //检测Key值是否存在
        if ($this->template_model->checkKey($this->input->post('terminal_code'), $this->input->post('country_code'), $this->input->post('key'))) {
            exit(json_encode(array('success' => FALSE, 'error' => '键值已经存在，不能添加相同主键！')));
        }

        $insertData = array(
            'id' => time(),
            'terminal' => $this->input->post('terminal_code'),
            'country_code' => $this->input->post('country_code'),
            'key' => $this->input->post('key'),
            'pub_about' => $this->input->post('pub_about'),
            'public' => $this->input->post('public'),
            'private' => $this->input->post('private'),
            'pri_about' => $this->input->post('pri_about')
        );
        if ($this->template_model->insert($insertData)) {
            exit(json_encode(array('success' => TRUE, 'id' => $insertData['id'])));
        } else {
            exit(json_encode(array('success' => FALSE, 'error' => '数据库操作失败！！')));
        }
    }

    function del() {
        $terminal_code = $this->input->post('terminal_code');
        $country_code = $this->input->post('country_code');
        $whereData = array('id' => (int) $this->input->post('id'));
        if ($this->template_model->del($terminal_code, $country_code, $whereData)) {
            exit(json_encode(array('success' => TRUE)));
        } else {
            exit(json_encode(array('success' => FALSE, 'msg' => '数据库操作失败！！！')));
        }
    }

    function update() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('country_code', '国家', 'required');
        $this->form_validation->set_rules('terminal_code', '终端', 'required');
        $this->form_validation->set_rules('key', '键值', 'required');
        $this->form_validation->set_rules('pub_about', '原视图说明', 'required');
        $this->form_validation->set_rules('public', '原视图文件名', 'required');
//        $this->form_validation->set_rules('private', '新视图名称', 'required');

        $this->form_validation->set_error_delimiters('', '<br>');
        if ($this->form_validation->run() == FALSE) {
            $error = validation_errors();
            exit(json_encode(array('success' => FALSE, 'error' => $error)));
        }

        //检测Key值是否存在
        $template_id = $this->template_model->checkKey($this->input->post('terminal_code'), $this->input->post('country_code'), $this->input->post('key'));
        if ($template_id > 0 && $template_id != $this->input->post('id')) {
            exit(json_encode(array('success' => FALSE, 'error' => '键值已经存在，不能修改相同主键！')));
        }

        $whereData = array(
            'id' => $this->input->post('id')
        );
        $updateData = array(
            'key' => $this->input->post('key'),
            'pub_about' => $this->input->post('pub_about'),
            'public' => $this->input->post('public'),
            'private' => $this->input->post('private'),
            'pri_about' => $this->input->post('pri_about'),
        );
        if ($this->template_model->update($this->input->post('terminal_code'), $this->input->post('country_code'), $whereData, $updateData)) {
            exit(json_encode(array('success' => TRUE)));
        } else {
            exit(json_encode(array('success' => FALSE, 'error' => '数据库操作失败！！')));
        }
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

        if ($this->template_model->update($whereData, $updateData, $country_code)) {
            exit(json_encode(array('success' => TRUE, 'status' => $updateData['status'])));
        } else {
            exit(json_encode(array('success' => FALSE, 'error' => '数据库操作失败！！')));
        }
    }

}
