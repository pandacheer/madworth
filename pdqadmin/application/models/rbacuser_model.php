<?php

/**
 * @文件： rbacUser_model.php
 * @时间： 2015-1-5 22:04:54
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：平台用户模型
 */
class Rbacuser_model extends CI_Model {

    var $UserRoleKey = 'UserRole'; //登录用户的角色

    function __construct() {
        parent::__construct();
    }

    //根据用户名、密码确定是否可以登录
    function check_user($user_account, $user_password) {
        $this->db->select('user_id,user_password,user_status');
        $this->db->where('user_account', $user_account);
        $query = $this->db->get('rbac_user');
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            if ($row['user_password'] != $user_password) {
                $return = '密码错误！';
            } else {
                $return = array_change_key_case($row, CASE_LOWER);
            }
        } else {
            return '帐号错误！';
        }
        return $return;
    }

    //更新最后登录时间
    function update_lastdate($user_id) {
        $sql = "update rbac_user set user_lastdate=" . time() . " WHERE user_id = ?";
        $this->db->query($sql, array($user_id));
    }

    //更改密码
    function pwdChange($data) {
        $sql = "UPDATE rbac_user SET user_password=? WHERE user_id= ?";
        return $this->db->query($sql, $data);
    }

    function pwdChangeByAccount($user_password, $user_account) {
        $sql = "UPDATE rbac_user SET user_password=? WHERE user_account= ?";
        return $this->db->query($sql, array($user_password, $user_account));
    }

    function check_forget($forget_account, $forget_email) {
        $this->db->select('user_email');
        $this->db->where('user_account', $forget_account);
        $query = $this->db->get('rbac_user');
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            if ($row['user_email'] != $forget_email) {
                $return = 0; //邮箱不正确
            } else {
                $return = 1; //OK
            }
        } else {
            $return = -1; //帐号不存在
        }
        return $return;
    }

    function select($data = array(), $sort = 'user_id', $order = 'desc', $offset = 0, $per_page = 10, $total = 0) {
        $result = array();
        $rows = array();
        $result['total'] = $total;
        $this->db->select('user_id,user_account,user_name,user_createdate,user_lastdate,user_status,user_email,user_role', FALSE);
        $this->db->from('rbac_user');
        $this->db->where($data);
        $this->db->order_by($sort, $order);
        $this->db->limit($per_page, $offset);
        $query = $this->db->get();
        foreach ($query->result_array() as $row) {
            $row['user_createdate']=  date('Y-m-d H:i:s', $row['user_createdate']);
            $row['user_lastdate']=  date('Y-m-d H:i:s', $row['user_lastdate']);
            $rows[]=$row;
        }
        $result['rows'] = $rows;
        return $result;
    }

    function count($data = array()) {
        $this->db->from('rbac_user');
        $this->db->where($data);
        return $this->db->count_all_results();
    }

    function insert($data, $user_role_id) {
        $time=time();
        $this->db->trans_start();

        $sql = "insert into rbac_user(user_account,user_password,user_name,user_email,user_role,user_createdate,user_lastdate,user_status) values(?,?,?,?,?,$time,$time,1)";
        $this->db->query($sql, $data);
        $user_id=$this->db->insert_id();
        $role_ids = explode(',', $user_role_id);
        foreach ($role_ids as $role_id) {
            $this->db->insert('rbac_role_user', array('user_id' => $user_id, 'role_id' => $role_id));
        }
        $this->db->trans_complete();
        if ($this->db->trans_status()) {
            return $user_id;
        } else {
            return 0;
        }
    }

    function del($user_id = 0) {
        $this->db->trans_start();
        $this->db->where('user_id', $user_id);
        $this->db->delete('rbac_user');
        $this->db->where('user_id', $user_id);
        $this->db->delete('rbac_role_user');
        $this->db->trans_complete();
        if ($this->db->trans_status()) {
            if ($this->redis->exists($this->UserRoleKey . $user_id)) {
                $this->redis->delete($this->UserRoleKey . $user_id);
            }
            return true;
        } else {
            return false;
        }
    }

    //更新用户的角色
    //$user_id 用户ID
    //$user_role_id 用户对应的角色ID，格式：1,3,4,5...  
    //$user_role 角色说明
    function updateRole($user_id, $user_role_id, $user_role_text) {
        $this->db->trans_start();
        $this->db->where('user_id', $user_id);
        $this->db->update('rbac_user', array('user_role' => $user_role_text));
        $this->db->where('user_id', $user_id);
        $this->db->delete('rbac_role_user');
        $role_ids = explode(',', $user_role_id);
        foreach ($role_ids as $role_id) {
            $this->db->insert('rbac_role_user', array('user_id' => $user_id, 'role_id' => $role_id));
        }
        $this->db->trans_complete();
        if ($this->db->trans_status()) {
            if ($this->redis->exists($this->UserRoleKey . $user_id)) {
                $this->redis->delete($this->UserRoleKey . $user_id);
            }
            return true;
        } else {
            return FALSE;
        }
    }

    function status($user_id, $old_user_status, $new_user_status) {
        $data = array('user_status' => $new_user_status);
        $this->db->trans_start();
        $this->db->where('user_id', $user_id);
        $this->db->where('user_status', $old_user_status);
        $this->db->update('rbac_user', $data);
        $this->db->trans_complete();
        if ($this->db->trans_status()) {
            return true;
        } else {
            return false;
        }
    }

    function getOne($user_id, $fields = 'user_id,user_account,user_name,user_email,user_role,user_createdate,user_lastdate') {
        $sql = 'select ' . $fields . ' from rbac_user where user_id=' . $user_id;
        $query = $this->db->query($sql);
        $row = $query->row_array();
        return $row;
    }

    function updateSelf($data) {
        $sql = 'update rbac_user set user_name=? , user_email=? where user_id= ?';
        return $this->db->query($sql, $data);
    }
    
    
    //查询所有的后台用户名   退货申请那边要做条件筛选
    function getInfo() {
    	$sql = 'select user_account from rbac_user';
        $query = $this->db->query($sql);
        $row = $query->result_array();
        return $row;
    }
    

}
