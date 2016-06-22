<?php

/**
 * @文件： shipFormula
 * @时间： 2015-7-15 13:55:15
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：
 */
class shipFormula extends Pc_Controller {

    function __construct() {
        parent::__construct();
    }

    function getList($country_code) {
        $sql = 'select id,country_code,weight,formula,special,1 as deletebtn,1 as editbtn from ship_formula where country_code= ? order by weight';
        $query = $this->db->query($sql, array($country_code));
        exit(json_encode($query->result_array()));
    }

    function loadEditDialog() {
        $this->load->view('system/v_shipFormulaDialog');
    }

    function insert() {
        $insertArr = array(
            'country_code' => $this->input->post('country_code'),
            'weight' => $this->input->post('weight'),
            'formula' => $this->input->post('formula'),
            'special' => $this->input->post('special') ? 2 : 1
        );
        $this->load->model('shipformula_model');
        $shipFormulaID = $this->shipformula_model->insert($insertArr);
        if ($shipFormulaID) {
            exit(json_encode(array('success' => TRUE, 'id' => $shipFormulaID,'special'=>$insertArr['special'])));
        } else {
            exit(json_encode(array('success' => false, 'error' => '数据库操作失败，请重试！')));
        }
    }

    function update() {
        $updateArr = array(
            'weight' => $this->input->post('weight'),
            'formula' => $this->input->post('formula'),
            'special' => $this->input->post('special') ? 2 : 1
        );
        $whereArr = array(
            'id' => $this->input->post('id'),
            'country_code' => $this->input->post('country_code')
        );
        $this->load->model('shipformula_model');
        if ($this->shipformula_model->update($whereArr, $updateArr)) {
            exit(json_encode(array('success' => TRUE,'special'=>$updateArr['special'])));
        } else {
            exit(json_encode(array('success' => false, 'error' => '数据库操作失败，请重试！')));
        }
    }

    function delete() {
        $id = $this->input->post('shipFormulaID');
        $this->load->model('shipformula_model');
        if ($this->shipformula_model->delete($id)) {
            exit(json_encode(array('success' => TRUE)));
        } else {
            exit(json_encode(array('success' => false, 'error' => '数据库操作失败，请重试！')));
        }
    }

}
