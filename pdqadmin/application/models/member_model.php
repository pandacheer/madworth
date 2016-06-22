<?php

/**
 * @文件： member_model
 * @时间： 2015-6-10 10:02:35
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：会员
 */
class Member_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getInfoById($country_code, $member_id, $fields = 'member_name,member_email,member_pwd,member_salt,create_time,login_inc,login_time,status') {
        $this->db->select($fields);
        $this->db->where('member_id', $member_id);
        $this->db->limit(1);
        $query = $this->db->get($country_code . '_member');
        return $query->row_array();
    }
    
    
    
    //用户信息列表
    function listData($country_code, $whereData, $sort = 'member_id', $order = 'desc', $offset = 0, $per_page = 10,$fields = 'member_id,member_email,create_time') {
    	$this->db->select($fields);
    	$this->db->from($country_code . '_member');
    	$this->db->where($whereData);
    	if($per_page){
    		$this->db->limit($per_page, $offset);
    	}
    	$this->db->order_by($sort, $order);
    	$query = $this->db->get();
    	return $query->result_array();
    }
    
    
    //用户信息列表数量
    function count($country_code, $whereData) {
    	$this->db->where($whereData);
    	$this->db->from($country_code . '_member');
    	return $this->db->count_all_results();
    }
    
    

}
