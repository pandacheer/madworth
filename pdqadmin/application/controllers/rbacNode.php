<?php

/**
 * @文件： rbacNode.php
 * @时间： 2013-4-11 16:04:18
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：node 节点控制器
 */
class RbacNode extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('rbacNode_model');
//        if ($this->session->userdata('user_id') != 10001) {
//            exit("<script type='text/javascript'>$.messager.alert('错误', '无操作权限！！！', 'error');</script>");
//        }
    }

    function loadGrid() {
        $this->load->view('system/v_rbacNode');
    }

    function select() {
        echo json_encode($this->rbacNode_model->select());
    }

    //添加管理员时，调入dialog
    function dialog() {
        $this->load->view('system/v_rbacNodeDialog');
    }

    //生成结点树
    function combotree($role_id = 0) {
        echo json_encode($this->rbacNode_model->combotree($role_id, 0));
    }

    function insert() {
        $this->load->helper(array('form'));
        $this->load->library('form_validation');
        $this->form_validation->set_rules('node_title', '操作说明', 'required');
        if (!$this->input->post('node_menu')) {
            $this->form_validation->set_rules('node_url', '操作url', 'required');
        }
        if ($this->form_validation->run() == FALSE) {
            exit(json_encode(array('success' => FALSE, 'title' => '错误', 'msg' => '节点名称必须填写！')));
        } else {

            $node_url = $this->input->post('node_url') ? $this->input->post('node_url') : '';
            $node_pid = $this->input->post('node_pid') ? $this->input->post('node_pid') : 0;
            $node_ptitle = $node_pid > 0 ? $this->input->post('node_ptitle') : '首页';


            $data = array(
                'node_url' => $node_url,
                'node_title' => $this->input->post('node_title'),
                'node_status' => 0,
                'node_sort' => $this->input->post('node_sort'),
                'node_pid' => $node_pid,
                'node_ptitle' => $node_ptitle,
                'node_menu' => $this->input->post('node_menu')
            );
            $node_id = $this->rbacNode_model->insert($data);
            if ($node_id) {
                exit(json_encode(array('success' => true, 'node_id' => $node_id, 'node_title' => $data['node_title'], 'node_url' => $node_url, 'node_menu' => $data['node_menu'], 'node_sort' => $data['node_sort'], 'node_ptitle' => $node_ptitle)));
            } else {
                exit(json_encode(array('success' => FALSE, 'msg' => '节点添加失败！')));
            }
        }
    }

    function del() {
        $node_id = (int) $this->input->post('node_id');
        if ($node_id > 10000) {
            if ($this->rbacNode_model->del($node_id)) {
                exit(json_encode(array('success' => true)));
            } else {
                exit(json_encode(array('success' => FALSE, 'msg' => '节点删除失败！')));
            }
        } else {
            exit(json_encode(array('success' => FALSE, 'msg' => '根节点不能删除！')));
        }
    }

    function update() {
        $this->load->helper(array('form'));
        $this->load->library('form_validation');
        $this->form_validation->set_rules('node_title', 'User ID', 'required');
        if ($this->form_validation->run() == FALSE) {
            eit(json_encode(array('success' => FALSE, 'title' => '错误', 'msg' => '节点名称必须填写！')));
        } else {
            $node_id = (int) $this->input->post('node_id');
            $node_url = $this->input->post('node_url') ? $this->input->post('node_url') : '';
            $node_pid = $this->input->post('node_pid') ? (int) $this->input->post('node_pid') : 0;
            $node_ptitle = $node_pid > 0 ? $this->input->post('node_ptitle') : '首页';
            $data = array(
                'node_url' => $node_url,
                'node_title' => $this->input->post('node_title'),
                'node_status' => 0,
                'node_sort' => (int) $this->input->post('node_sort'),
                'node_pid' => $node_pid,
                'node_ptitle' => $node_ptitle,
                'node_menu' => (int) $this->input->post('node_menu')
            );
            if ($this->rbacNode_model->update($data, $node_id)) {
                exit(json_encode(array('success' => true)));
            } else {
                exit(json_encode(array('success' => FALSE, 'msg' => '节点修改失败！')));
            }
        }
    }

    function status() {
        $node_id = (int) $this->input->post('node_id');
        $node_status = (int) $this->input->post('node_status');
        if ($this->rbacNode_model->status($node_id, $node_status)) {
            exit(json_encode(array('success' => true)));
        } else {
            exit(json_encode(array('success' => FALSE, 'msg' => '节点状态更改失败！')));
        }
    }

}