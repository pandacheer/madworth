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

    function listByOrderNumber($country_code, $order_number, $fields = 'product_id,product_name,product_sku,product_attr,payment_price,product_quantity,payment_amount,comments_star') {
        $this->db->where('order_number', $order_number);
        $this->db->select($fields);
        return $this->db->get($country_code . '_order_details')->result_array();
    }

}
