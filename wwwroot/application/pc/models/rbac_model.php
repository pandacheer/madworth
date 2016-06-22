<?php

/**
 * @文件： rbac_model
 * @时间： 2013-4-11 16:06:25
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：rbac_model 角色控制模型
 */
class rbac_model extends CI_Model {

    var $UserRoleKey = 'UserRole'; //登录用户的角色
    var $NodeKey = 'Node';  //结点
    var $MenuKey = 'Menu';  //菜单
    var $AccessKey = 'Access'; //不同角色所对应的节点
    var $TimeOut = 3600;

    function __construct() {
        parent::__construct();
        $this->nodeIntoRedis();
        $this->menuIntoRedis(1);
        $this->userRoleIntoRedis($this->session->userdata('user_id'));
        $this->accessIntoRedis($this->session->userdata('user_id'));
    }

    //节点放入redis
    function nodeIntoRedis() {
        if (!$this->redis->exists($this->NodeKey)) {
            $this->db->select('node_id,node_url,node_title');
            $this->db->from('competence_node');
            $this->db->order_by('node_id');
            $query = $this->db->get();
            foreach ($query->result() as $row) {
                if ($row->node_url != '')
                    $this->redis->setAdd($this->NodeKey, $row->node_url, 1, $row->node_id);
                else
                    $this->redis->setAdd($this->NodeKey, $row->node_title, 1, $row->node_id);
            }
        }
        $this->redis->timeOut($this->NodeKey, $this->TimeOut);
    }

    //登录用户的角色放入redis
    function userRoleIntoRedis($user_id) {
        if (!$this->redis->exists($this->UserRoleKey . $user_id)) {
            $this->db->select('role_id');
            $this->db->where('user_id', $user_id);
            $this->db->from('competence_role_user');
            $this->db->order_by('role_id');
            $query = $this->db->get();
            foreach ($query->result() as $row) {
                $this->redis->setAdd($this->UserRoleKey . $user_id, $row->role_id);
            }
        }
    }

    //各个角色对应的结点放入redis
    function accessIntoRedis($user_id) {
        $Arr_userRole = $this->redis->setMembers($this->UserRoleKey . $user_id);
        foreach ($Arr_userRole as $role_id) {
            if (!$this->redis->exists($this->AccessKey . $role_id)) {
                $this->db->select('node_id');
                $this->db->from('competence_access');
                $this->db->where('role_id', $role_id);
                $this->db->order_by('node_id');
                $query = $this->db->get();
                foreach ($query->result() as $row) {
                    $this->redis->setAdd($this->AccessKey . $role_id, $row->node_id);
                }
            }
            $this->redis->timeOut($this->AccessKey . $role_id, $this->TimeOut);
        }
    }

    //生成完整菜单
    function menuIntoRedis($node_id = 1) {
        if (!$this->redis->exists($this->MenuKey)) {
            $this->load->model('menu_model');
            $Arr_menus = $this->menu_model->get_menu($node_id);
            $this->redis->set($this->MenuKey, json_encode($Arr_menus));
        } else {
            $Arr_menus = json_decode($this->redis->get($this->MenuKey), TRUE);
        }
        return $Arr_menus;
    }

    //生成用户菜单ID集合
    function user_menu($user_id) {
        $Arr_userRole = $this->redis->setMembers($this->UserRoleKey . $user_id);
        foreach ($Arr_userRole as $key => $value) {
            $Arr_userRole[$key] = $this->AccessKey . $value;
        }
        return $this->redis->setUnion($Arr_userRole);
    }

    //判断操作是否有权限
    function rbac($url, $user_id) {
        $token = false;
        $node_id = $this->redis->setScore($this->NodeKey, $url);
        if ($node_id) {
            $Arr_userRole = $this->redis->setMembers($this->UserRoleKey . $user_id);
            foreach ($Arr_userRole as $role_id) {
                if ($this->redis->setSearch($this->AccessKey . $role_id, $node_id)) {
                    $token = true;
                    break;
                }
            }
        }
        return $token;
    }

}