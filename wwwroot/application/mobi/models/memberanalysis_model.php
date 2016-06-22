<?php

/**
 * @文件： memberAnalysis_model
 * @时间： 2015-6-17 14:51:53
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：会员分析表
 */
class memberAnalysis_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /*     * **********************************************
     * 后台调用模块
     * ************************************************ */

    //会员分析列表
    //字段：member_id,member_name,member_email,member_location,member_orders,last_order,order_spent
    function listMember($whereData, $sort = 'member_id', $order = 'desc', $offset = 0, $per_page = 10) {
        $fields = 'member_id,member_name,member_email,member_location,member_orders,last_order,order_spent';
        $this->db->select($fields);
        $this->db->from('member_analysis');
        $this->db->where($whereData);
        $this->db->limit($per_page, $offset);
        $this->db->order_by($sort, $order);
        $query = $this->db->get();
        return $query->result_array();
    }

}
