<?php

/**
 * @文件： rbacNode_model.php
 * @时间： 2013-4-11 16:06:25
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明： 节点数据库模型
 */
class Rbacnode_model extends CI_Model {

    var $NodeKey = 'Node'; //结点
    var $MenuKey = 'Menu';  //菜单

    function __construct() {
        parent::__construct();
    }

    function select() {
        $node_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $items = array();
        $this->db->select('node_id,node_url,node_title,node_status,node_sort,node_pid,node_ptitle,node_menu');
        $this->db->where('node_pid', $node_id);
        $this->db->from('rbac_node');
        $this->db->order_by('node_sort');
        $query = $this->db->get();
        foreach ($query->result_array() as $row) {
            $row['state'] = $this->has_child($row['node_id']) ? 'closed' : 'open';
            array_push($items, array_change_key_case($row, CASE_LOWER));
        }
        return $items;
    }

    function has_child($node_id) {
        $this->db->from('rbac_node');
        $this->db->where('node_pid', $node_id);
        return $this->db->count_all_results() > 0 ? true : false;
    }

    //返回结点树
    function combotree($role_id = 0, $node_id = 0) {
        $items = array();
        if ($role_id > 0) {
            $query = $this->db->query('SELECT a.node_id as id,a.node_title as text,b.checked as checked FROM rbac_node a
                            left JOIN (select node_id, 1 as checked from rbac_access  WHERE role_id=' . $role_id . ')  b ON a.node_id = b.node_id where a.node_pid=' . $node_id);
        } else {
            $this->db->select('node_id as id,node_title as text');
            $this->db->where('node_pid', $node_id);
            $this->db->from('rbac_node');
            $this->db->order_by('node_sort');
            $query = $this->db->get();
        }
        foreach ($query->result_array() as $row) {
            $row['state'] = 'open';
            if ($this->has_child($row['id'])) {
                $row['children'] = $this->combotree($role_id, $row['id']);
            }
            $items[] = array_change_key_case($row, CASE_LOWER);
        }
        return $items;
    }

    function insert($data) {
        $this->db->trans_start();
        $query = $this->db->query('select node_id.nextval as node_id from dual');
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $node_id = $row->node_id;
        }
        $sql = 'insert into rbac_node(node_id,node_url,node_title,node_status,node_sort,node_pid,node_ptitle,node_menu) values(' . $node_id . ',?,?,?,?,?,?,?)';
        $this->db->query($sql, $data);

        $this->db->trans_complete();
        if ($this->db->trans_status()) {
            if ($this->redis->exists($this->NodeKey)) {
                if ($data['node_url'] != '')
                    $this->redis->setAdd($this->NodeKey, $data['node_url'], 1, $node_id);
                else
                    $this->redis->setAdd($this->NodeKey, $data['node_title'], 1, $node_id);
            }
            if ($this->redis->exists($this->MenuKey)) {
                $this->redis->delete($this->MenuKey);
            }
            return $node_id;
        } else {
            return 0;
        }
    }

    function del($node_id = 0) {
        $this->db->trans_start();
        $this->del_child($node_id);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    function del_child($node_id) {
        $this->db->select('node_id');
        $this->db->from('rbac_node');
        $this->db->where('node_pid', $node_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $this->del_child($row->node_id);
                $this->db->where('node_id', $row->node_id);
                $this->db->delete('rbac_node');
                $this->db->where('node_id', $row->node_id);
                $this->db->delete('rbac_access');
            }
        }
        $this->db->where('node_id', $node_id);
        $this->db->delete('rbac_node');
        $this->db->where('node_id', $node_id);
        $this->db->delete('rbac_access');
    }

    function update($data, $node_id) {
        $sql = "UPDATE rbac_node SET node_url=?, node_title= ?, node_status = ?, node_sort = ?, node_pid = ?, node_ptitle= ?, node_menu = ? WHERE node_id =" . $node_id;
        $result = $this->db->query($sql, $data);
        if ($result) {
            if ($this->redis->exists($this->NodeKey)) {
                $this->redis->setDeleteRange($this->NodeKey, $node_id, $node_id);
                if ($data['node_url'] != '')
                    $this->redis->setAdd($this->NodeKey, $data['node_url'], 1, $node_id);
                else
                    $this->redis->setAdd($this->NodeKey, $data['node_title'], 1, $node_id);
            }
            if ($this->redis->exists($this->MenuKey)) {
                $this->redis->delete($this->MenuKey);
            }
        }
        return $result;
    }

    function status($node_id, $node_status) {
        $node_status = ($node_status == 0) ? 1 : 0;
        $data = array('node_status' => $node_status);
        $this->db->trans_start();
        $this->status_child($node_id, $data);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    function status_child($node_id, $data) {
        $this->db->select('node_id');
        $this->db->from('rbac_node');
        $this->db->where('node_pid', $node_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $this->status_child($row->node_id, $data);
                $this->db->where('node_id', $row->node_id);
                $this->db->update('rbac_node', $data);
            }
        }
        $this->db->where('node_id', $node_id);
        $this->db->update('rbac_node', array_change_key_case($data, CASE_UPPER));
    }

}

