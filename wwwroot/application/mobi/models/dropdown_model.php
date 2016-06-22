<?php

/**
 * @文件： dropdown
 * @时间： 2015-6-30 16:53:35
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：下拉列表
 */
class Dropdown_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function tag($country_code, $whereData, $field = 'Tag1') {
        $command = array(
            "distinct" => $country_code.'_product',
            "key" => 'tag.' . $field,
            "query" => $whereData
        );
        return $this->mongo->command($command);
    }

//    function category($whereData = array()) {
//        $collection = $this->mongo->Category;
//        return $collection->find($whereData);
//    }
//
//    //查找Collection
//    function collection($country = 'AU', $fields = array('title' => true)) {
//        $collection = $this->mongo->{$country . '_collection'};
//        $whereData = array('status' => new MongoInt32(2), 'model' => new MongoInt32(1));
//        return $collection->find($whereData, $fields);
//    }
//
//    function countDown() {
//        $this->db->select('id,name');
//        $this->db->from('countdown');
//        $this->db->where('status', 2);
//        $query = $this->db->get();
//        return $query->result_array();
//    }
}
