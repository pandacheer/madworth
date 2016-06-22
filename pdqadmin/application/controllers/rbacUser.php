<?php

/**
 * @文件： rbacUser.php
 * @时间： 2015-1-6 14:17:10
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：平台用户控制器
 */
class RbacUser extends CI_Controller {

    private $UserID;

    function __construct() {
        parent::__construct();
        if (!$this->session->userdata('user_in')) {
            redirect(base_url());
        } else {
//            $this->UserID = $this->session->userdata('user_id');
            $this->load->model('rbacuser_model');
//            if ($this->session->userdata('user_id') != 10001) {
//                exit("<script type='text/javascript'>$.messager.alert('错误', '无操作权限！！！', 'error');</script>");
//            }
        }
    }

    function loadGrid() {
        $this->load->view('system/v_rbacUser');
    }

    function select() {
        $data = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $per_page = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'USER_ID';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
        $offset = ($page - 1) * $per_page + 1;
        $total = $this->rbacuser_model->count($data);
        echo json_encode($this->rbacuser_model->select($data, $sort, $order, $offset, $per_page, $total));
    }

    //添加管理员时，调入dialog
    function dialog() {
        $this->load->view('system/v_rbacUserDialog');
    }

    function insert() {
        $this->load->helper(array('form'));
        $this->load->library('form_validation');
        $this->form_validation->set_rules('user_name', '姓名', 'required');
        $this->form_validation->set_rules('user_account', '帐号', 'required|alpha_dash|is_unique[rbac_user.user_account]');
        $this->form_validation->set_rules('user_password', '密码', 'required|min_length[6]|max_length[16]|alpha_dash');
        $this->form_validation->set_rules('user_password2', '密码确认', 'required|alpha_dash|matches[user_password]');
        $this->form_validation->set_rules('user_email', '邮箱', 'required|valid_email');

        if ($this->form_validation->run() == FALSE) {
            exit(json_encode(array('success' => FALSE, 'msg' => '请检查输入项是否符合规范！')));
        } else {
            $user_password = md5($this->input->post('user_password'));
            $data = array(
                'user_account' => $this->input->post('user_account'),
                'user_password' => $user_password,
                'user_name' => $this->input->post('user_name'),
                'user_email' => $this->input->post('user_email'),
                'user_role' => $this->input->post('user_role_text')
            );
            $user_role_id = $this->input->post('user_role_id');
            $user_id = $this->rbacuser_model->insert($data, $user_role_id);
            if ($user_id) {
                exit(json_encode(array('success' => true, 'user_id' => $user_id)));
            } else {
                exit(json_encode(array('success' => FALSE, 'msg' => '管理员添加失败！')));
            }
        }
    }

    function del() {
        $user_id = (int) $this->input->post('user_id');
        if ($this->rbacuser_model->del($user_id)) {
            exit(json_encode(array('success' => true)));
        } else {
            exit(json_encode(array('success' => FALSE, 'msg' => '管理员删除失败！')));
        }
    }

    //调出修改管理员角色界面
    function roleDialog() {
        $this->load->view('system/v_rbacUserRoleDialog');
    }

    function updateRole() {
        $user_id = $this->input->post('user_id');
        $user_role_id = $this->input->post('user_role_id');
        $user_role_text = $this->input->post('user_role_text');
        if ($this->rbacuser_model->updateRole($user_id, $user_role_id, $user_role_text)) {
            exit(json_encode(array('success' => true)));
        } else {
            exit(json_encode(array('success' => FALSE, 'msg' => '用户角色修改失败！')));
        }
    }

    function status() {
        $user_id = (int) $this->input->post('user_id');
        $old_user_status = (int) $this->input->post('user_status');
        if ($old_user_status == 1 | $old_user_status == 3) {
            $new_user_status = 2;
            $msg = '启用';
        } else {
            $new_user_status = 3;
            $msg = '禁用';
        }
        if ($this->rbacuser_model->status($user_id, $old_user_status, $new_user_status)) {
            exit(json_encode(array('success' => true)));
        } else {
            exit(json_encode(array('success' => FALSE, 'msg' => '管理员状态更改失败！')));
        }
    }

    //修改自帐号
    function loadPanelSelf() {
        $this->load->view('system/v_rbacUserSelf');
    }

    function getSelf() {
        $info = $this->rbacuser_model->getOne($this->UserID);
        if (is_array($info)) {
            exit(json_encode(array_change_key_case($info, CASE_LOWER)));
        }
    }

    function rbacUserSelfDialog() {
        $this->load->view('system/v_rbacUserSelfDialog');
    }

    function getSelfBase() {
        $info = $this->rbacuser_model->getOne($this->UserID, 'USER_ID,USER_ACCOUNT,USER_NAME,USER_EMAIL');
        if (is_array($info)) {
            exit(json_encode(array_change_key_case($info, CASE_LOWER)));
        }
    }

    function updateSelf() {
        $user_name = $this->input->post('user_name');
        $user_email = $this->input->post('user_email');
        $this->load->helper(array('form'));
        $this->load->library('form_validation');
        $this->form_validation->set_rules('user_name', '姓名', 'required');
        $this->form_validation->set_rules('user_email', '邮箱', 'required|valid_email');

        if ($this->form_validation->run() == FALSE) {
            exit(json_encode(array('success' => FALSE, 'msg' => '请检查输入项是否符合规范！')));
        } else {
            $data = array(
                'user_name' => $user_name,
                'user_email' => $user_email,
                'user_id' => $this->UserID
            );
            if ($this->rbacuser_model->updateSelf($data)) {
                exit(json_encode(array('success' => true)));
            } else {
                exit(json_encode(array('success' => FALSE, 'msg' => '帐号资料更新失败！')));
            }
        }
    }

}
