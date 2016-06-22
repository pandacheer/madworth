<?php

/**
 * @文件： orderdetails_model
 * @时间： 2015-8-3 15:52:43
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：订单详情表
 */
class Orderdetails_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function listByOrderNumber($country_code, $order_number, $fields = 'details_id,product_id,product_name,product_sku,product_attr,payment_price,product_quantity,payment_amount,comments_star') {
        $this->db->where('order_number', $order_number);
        $this->db->select($fields);
        return $this->db->get($country_code . '_order_details')->result_array();
    }
    //根据 details_id 获取订单信息
    function getInfoByID($country_code, $details_id, $fields = 'details_id,member_id,order_number,product_id,product_name,product_sku,product_attr,payment_price,product_quantity,payment_amount,bundle_skus,total_qty,bundle_type,comments_star') {
        $sql = "select {$fields} from {$country_code}_order_details where details_id={$details_id} limit 1";
        return $this->db->query($sql)->row_array();
    }
    
    
    //获取当前用户的的产品详情
    function getInfoProDetails($country_code,$member_id,$order_number,$fields = 'details_id,member_id,order_number,product_id,product_name,product_sku,product_attr,payment_price,product_quantity,payment_amount,bundle_skus,total_qty,bundle_type,comments_star') {
    	$this->db->where ( array ('member_id' => $member_id,'order_number' => $order_number));
    	$this->db->select($fields);
    	return $this->db->get($country_code . '_order_details')->result_array();
    }
    	

}

