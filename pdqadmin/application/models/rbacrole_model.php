<?php

/**
 * @文件： rbacRole_model.php
 * @时间： 2013-4-11 16:06:25
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：角色数据库模型
 */
class Rbacrole_model extends CI_Model {

    var $AccessKey = 'Access'; //不同角色所对应的节点

    function __construct() {
        parent::__construct();
    }

    function select() {
        $items = array();
        $this->db->select('role_id,role_name,role_access,role_remark,role_status');
        $this->db->from('rbac_role');
        $this->db->order_by('role_id');
        $query = $this->db->get();
        foreach ($query->result_array() as $row) {
            $items[] = array_change_key_case($row, CASE_LOWER);
        }
        return $items;
    }

    function insert($data, $role_access_id) {
        $this->db->trans_start();
        $query = $this->db->query('select role_id.nextval as role_id from dual');
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $role_id = $row->role_id;
        }
        $sql = 'insert into rbac_role(role_id,role_name,role_access,role_status,role_remark,role_pid) values(' . $role_id . ',?,?,?,?,1)';
        $this->db->query($sql, $data);
        $node_ids = explode(',', $role_access_id);
        foreach ($node_ids as $node_id) {
            $this->db->insert('rbac_access', array('role_id' => $role_id, 'node_id' => $node_id));
        }
        $this->db->trans_complete();
        if ($this->db->trans_status()) {
            if ($this->redis->exists($this->AccessKey . $role_id)) {
                foreach ($node_ids as $node_id) {
                    $this->redis->setAdd($this->AccessKey . $role_id, $node_id);
                }
            }
            return $role_id;
        } else {
            return 0;
        }
    }

    function del($role_id = 0) {
        $this->db->trans_start();
        $this->db->where('role_id', $role_id);
        $this->db->delete('rbac_role');
        $this->db->where('role_id', $role_id);
        $this->db->delete('rbac_access');
        $this->db->trans_complete();
        if ($this->db->trans_status()) {
            if ($this->redis->exists($this->AccessKey . $role_id)) {
                $this->redis->delete($this->AccessKey . $role_id);
            }
            return true;
        } else {
            return false;
        }
    }

    //$data  更新的字段
    //$role_id 角色ID
    //$role_access_id 角色对应的权限ID，格式：1,3,4,5...
    function update($data, $role_id, $role_access_id) {
        $this->db->trans_start();
        $this->db->where('role_id', $role_id);
        $this->db->update('rbac_role', $data);
        $this->db->where('role_id', $role_id);
        $this->db->delete('rbac_access');
        $node_ids = explode(',', $role_access_id);
        foreach ($node_ids as $node_id) {
            $this->db->insert('rbac_access', array('role_id' => $role_id, 'node_id' => $node_id));
        }
        $this->db->trans_complete();
        if ($this->db->trans_status()) {
            if ($this->redis->exists($this->AccessKey . $role_id)) {
                $this->redis->delete($this->AccessKey . $role_id);
                foreach ($node_ids as $node_id) {
                    $this->redis->setAdd($this->AccessKey . $role_id, $node_id);
                }
            }
            return true;
        } else {
            return false;
        }
    }

    function status($role_id, $old_role_status, $new_role_status) {
        $data = array('role_status' => $new_role_status);
        $this->db->trans_start();
        $this->status_child($role_id, $old_role_status, $data);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    private function status_child($role_id, $old_role_status, $data) {
        $this->db->select('role_id');
        $this->db->from('rbac_role');
        $this->db->where('role_pid', $role_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $this->status_child($row->role_id, $data);
                $this->db->where('role_id', $row->role_id);
                $this->db->where('role_status', $old_role_status);
                $this->db->update('rbac_role', $data);
            }
        }
        $this->db->where('role_id', $role_id);
        $this->db->update('rbac_role', $data);
    }

    //生成角色树
    function combotree($user_id) {
        $items = array();
        if ($user_id > 0) {
            $query = $this->db->query('SELECT a.role_id as id,a.role_name as text,b.checked AS checked FROM rbac_role a
                            left JOIN (select role_id, 1 as checked from rbac_role_user  WHERE user_id=' . $user_id . ') b ON a.role_id = b.role_id');
        } else {
            $this->db->select('role_id as id  ,role_name as text');
            $this->db->from('rbac_role');
            $this->db->order_by('role_id');
            $query = $this->db->get();
        }

        foreach ($query->result_array() as $row) {
            $items[] = array_change_key_case($row, CASE_LOWER);
        }
        return $items;
    }

//    function combotree_user($user_id) {
//        $items = array();
//        $query = $this->db->query('SELECT a.role_id as id,a.role_name as text,b.checked AS checked FROM rbac_role AS a
//                            left JOIN (select role_id, true as checked from rbac_role_user  WHERE user_id=' . $user_id . ') AS b ON a.role_id = b.role_id');
//        foreach ($query->result_array() as $row) {
//            $row['state'] = 'open';
//            if ($row['checked'] == 1)
//                $row['checked'] = 'true';
//            $items[] = $row;
//        }
//        return $items;
//    }
}

