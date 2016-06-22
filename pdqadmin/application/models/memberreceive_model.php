<?php

/**
 * @文件： memberReceive_model
 * @时间： 2015-6-17 15:15:22
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：会员地址表
 */
class Memberreceive_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    
        //根据会员ID获取会员收货地址列表
    //字段：receive_id,member_id,receive_name,receive_company,receive_country,receive_province,receive_city,receive_add1,receive_add2,receive_zipcode,receive_phone,is_default
    function getListByMemberId($country, $member_id) {
        $fields = 'receive_id,receive_firstName,receive_lastName,receive_company,receive_country,receive_province,receive_city,receive_add1,receive_add2,receive_zipcode,receive_phone,is_default';
        $sql = "select $fields from {$country}_member_receive where member_id=$member_id order by is_default desc";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    
    /*     * **********************************************
     * 前端调用模块
     * ************************************************ */

    //根据会员ID获取会员收货地址列表
    //字段：receive_id,member_id,receive_name,receive_company,receive_country,receive_province,receive_city,receive_add1,receive_add2,receive_zipcode,receive_phone,is_default
    function listAddByMbId($country, $member_id) {
        $fields = 'receive_id,receive_firstName,receive_lastName,receive_company,receive_country,receive_province,receive_city,receive_add1,receive_add2,receive_zipcode,receive_phone,is_default';
        $sql = "select $fields from {$country}_member_receive where member_id＝$member_id order by is_default desc";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    //根据ID获取收货地址信息
    function getInfoById($country, $member_id, $receive_id) {
        $fields = 'receive_id,member_id,receive_firstName,receive_lastName,receive_company,receive_country,receive_province,receive_city,receive_add1,receive_add2,receive_zipcode,receive_phone,is_default';
        $sql = "select {$fields} from {$country}_member_receive where receive_id={$receive_id} limit 1";
        $query = $this->db->query($sql);
        $row = $query->row_array();
        if ($row['member_id'] == $member_id) {
            return $row;
        } else {
            return false;
        }
    }

    function insert($country, $data) {
        return $this->db->insert($country . '_member_receive', $data);
    }

    function del($country, $member_id, $receive_id) {
        return $this->db->delete($country . '_member_receive', array('receive_id' => $receive_id, 'member_id' => $member_id));
    }

    function update($country, $member_id, $receive_id, $data) {
        $this->db->where('receive_id=' . $receive_id . ' and member_id=' . $member_id);
        return $this->db->update($country . '_member_receive', $data);
    }
    
    
   
}
