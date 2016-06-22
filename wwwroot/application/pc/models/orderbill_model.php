<?php

/**
 * @文件： orderbill_model
 * @时间： 2015-8-3 15:15:22
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：订单帐单地址表
 */
class Orderbill_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    //根据订单Number获取收货地址信息
    function getInfoById($country_code, $order_number) {
        $fields = 'receive_firstName,receive_lastName,receive_company,receive_country,receive_province,receive_city,receive_add1,receive_add2,receive_zipcode,receive_phone';
        $sql = "select {$fields} from {$country_code}_order_bill where order_number={$order_number} limit 1";
        $query = $this->db->query($sql);
        return $query->row_array();
    }

//    function update($country, $member_id, $receive_id, $data) {
//        $this->db->where('receive_id=' . $receive_id . ' and member_id=' . $member_id);
//        return $this->db->update($country . '_member_receive', $data);
//    }
}
