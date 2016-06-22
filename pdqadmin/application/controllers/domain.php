<?php

/**
 * @文件： domain
 * @时间： 2015-6-23 14:06:36
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：
 */
class Domain extends Pc_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('domain_model');
    }

    public function index() {
        $this->load->view('system/v_domain');
    }

    function loadData() {
        $page = $this->input->post('page') ? $this->input->post('page') : 1;
        $per_page = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'country';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
        $offset = ($page - 1) * $per_page;
        $whereData = array();
        $total = $this->domain_model->count($whereData);
        echo json_encode($this->domain_model->loadData($whereData, $sort, $order, $offset, $per_page, $total));
    }

    function statusChange() {
        $code = $this->input->post('code');
        $status = $this->input->post('status');
        $whereData = array(
            'code' => $code,
            'status' => $status
        );
        $updateData = array(
            'status' => $status == 1 ? 2 : 1
        );

        if ($this->domain_model->update($whereData, $updateData)) {
            exit(json_encode(array('success' => TRUE, 'status' => $updateData['status'])));
        } else {
            exit(json_encode(array('success' => FALSE, 'error' => '数据库操作失败！！')));
        }
    }

    function loadEditDialog() {
        $this->load->view('system/v_domainDialog');
    }

    function update() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('about', '语种说明', 'required');
        $this->form_validation->set_error_delimiters('', '<br>');
        if ($this->form_validation->run() == FALSE) {
            $error = validation_errors();
            exit(json_encode(array('success' => FALSE, 'error' => $error)));
        }
        $whereData = array(
            'code' => $this->input->post('code')
        );
        $updateData = array(
            'about' => $this->input->post('about')
        );
        if ($this->domain_model->update($whereData, $updateData)) {
            exit(json_encode(array('success' => TRUE, 'about' => $updateData['about'])));
        } else {
            exit(json_encode(array('success' => FALSE, 'error' => '数据库操作失败！！')));
        }
    }

    function insert() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('domain', '域名', 'required|alpha|min_length[2]|max_length[2]|is_unique[domain.code]');
        $this->form_validation->set_rules('country', '国家', 'required');
        $this->form_validation->set_error_delimiters('', '<br>');
        if ($this->form_validation->run() == FALSE) {
            $error = validation_errors();
            exit(json_encode(array('success' => FALSE, 'error' => $error)));
        }

        $insertData = array(
            'domain' => $this->input->post('domain'),
            'country' => $this->input->post('country')
        );
        if ($this->domain_model->insert($insertData)) {
            exit(json_encode(array('success' => TRUE)));
        } else {
            exit(json_encode(array('success' => FALSE, 'error' => '数据库操作失败！！')));
        }
    }

    function combobox() {
        echo json_encode($this->domain_model->combobox());
    }

}
