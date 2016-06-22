<?php

/**
 * @文件： rbacRole.php
 * @时间： 2015-1-28 21:04:18
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明： 角色控制器
 */
class RbacRole extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('rbacRole_model');
//        if ($this->session->userdata('user_id') != 10001) {
//            exit("<script type='text/javascript'>$.messager.alert('错误', '无操作权限！！！', 'error');</script>");
//        }
    }

    function loadGrid() {
        $this->load->view('system/v_rbacRole');
    }

    function select() {
        echo json_encode($this->rbacRole_model->select());
    }

    //添加管理员时，调入dialog
    function dialog() {
        $this->load->view('system/v_rbacRoleDialog');
    }

    function insert() {
        $this->load->helper(array('form'));
        $this->load->library('form_validation');
        $this->form_validation->set_rules('role_name', '角色名称', 'required');
        if ($this->form_validation->run() == FALSE) {
            exit(json_encode(array('success' => FALSE, 'msg' => '角色名称必须填写！')));
        } else {
            $data = array(
                'role_name' => $this->input->post('role_name'),
                'role_access' => $this->input->post('role_access_text'),
                'role_status' => 0,
                'role_remark' => $this->input->post('role_remark')
            );
            $role_access_id = $this->input->post('role_access_id');
            $role_id = $this->rbacRole_model->insert($data, $role_access_id);
            if ($role_id) {
                exit(json_encode(array('success' => true, 'role_name' => $data['role_name'])));
            } else {
                exit(json_encode(array('success' => FALSE, 'msg' => '角色添加失败！')));
            }
        }
    }

    function del() {
        $role_id = $this->input->post('role_id');
        if ($this->rbacRole_model->del($role_id)) {
            exit(json_encode(array('success' => true)));
        } else {
            exit(json_encode(array('success' => FALSE, 'msg' => '角色删除失败！')));
        }
    }

    function update() {
        $this->load->helper(array('form'));
        $this->load->library('form_validation');
        $this->form_validation->set_rules('role_name', 'User ID', 'required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('success' => FALSE, 'title' => '错误', 'msg' => '角色名称必须填写！'));
        } else {
            $role_id = $this->input->post('role_id');
            $data = array(
                'ROLE_NAME' => $this->input->post('role_name'),
                'ROLE_ACCESS' => $this->input->post('role_access_text'),
                'ROLE_STATUS' => 1,
                'ROLE_REMARK' => $this->input->post('role_remark')
            );
            $role_access_id = $this->input->post('role_access_id');
            if ($this->rbacRole_model->update($data, $role_id, $role_access_id)) {
                exit(json_encode(array('success' => true, 'msg' => '角色【' . $data['ROLE_NAME'] . '】修改成功！')));
            } else {
                exit(json_encode(array('success' => FALSE, 'msg' => '角色【' . $data['ROLE_NAME'] . '】修改失败！')));
            }
        }
    }

    function status() {
        $role_id = (int) $this->input->post('role_id');
        $old_role_status = (int) $this->input->post('role_status');
        if ($old_role_status == 1 | $old_role_status == 3) {
            $new_role_status = 2;
            $msg = '启用';
        } else {
            $new_role_status = 3;
            $msg = '禁用';
        }
        if ($this->rbacRole_model->status($role_id, $old_role_status, $new_role_status)) {
            exit(json_encode(array('success' => true, 'role_status' => $new_role_status)));
        } else {
            exit(json_encode(array('success' => FALSE, 'msg' => '角色状态更改失败！')));
        }
    }

    //生成角色树
    function combotree($role_id = 0) {
        echo json_encode($this->rbacRole_model->combotree($role_id));
    }

}