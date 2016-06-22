<?php

/**
 * @文件： ordership_model
 * @时间： 2015-8-3 15:15:22
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：订单发货地址表
 */
class Ordersend_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    //根据订单Number获取发货进度
    function getProgressById($country_code, $order_number, /* $is_resend = 1, */ $fields = 'send_id,order_number,send_status,track_code,track_url,send_bill,send_time,logistics,is_resend,create_time,operator') {
        //and is_resend={$is_resend}
    	$sql = "select {$fields} from {$country_code}_order_send where order_number={$order_number} order by send_id desc";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    //根据订单Number获取发货信息
    function getInfoById($country_code, $order_number, $fields = 'send_id,order_number,send_status,track_code,track_url,send_bill,send_time,logistics,is_resend,create_time,operator') {
        $sql = "select {$fields} from {$country_code}_order_send where order_number={$order_number} order by send_id desc";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    
    
    //根据订单Number获取最后一条发货的url
    function getSendUrl($country_code, $order_number, $fields = 'track_url,track_code') {
    	$this->db->order_by('send_id', 'desc');
    	$this->db->select($fields);
    	$this->db->limit(1);
    	return $this->db->get_where($country_code . '_order_send', array('order_number' => $order_number),1)->row_array();
    }

//    function update($country, $member_id, $receive_id, $data) {
//        $this->db->where('receive_id=' . $receive_id . ' and member_id=' . $member_id);
//        return $this->db->update($country . '_member_receive', $data);
//    }
}
