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

    function user() {
        $this->db->select('user_id, user_account');
        $query = $this->db->get('rbac_user');
        return $query->result();
    }

    function tag($whereData = array(), $country_code = 'US', $fields = array('tag.Tag3' => true)) {
        if ($country_code == '')
            $country_code = 'US';
        $collection = $this->mongo->{$country_code . '_product'};
        $tag3Arr = $collection->distinct("tag.Tag3");
        $result = [];
        foreach ($tag3Arr as $tag) {
            if ($tag) {
                $result[] = array(
                    '_id' => $tag,
                    'title' => $tag
                );
            }
        }
        return $result;
    }

    function category($whereData = array()) {
        $collection = $this->mongo->Category;
        return $collection->find($whereData);
    }

    //查找Collection
    function collection($country = 'AU', $fields = array('title' => true)) {
        $collection = $this->mongo->{$country . '_collection'};
        $whereData = array('status' => new MongoInt32(2), 'model' => new MongoInt32(1));
        return $collection->find($whereData, $fields);
    }

    function countDown() {
        $this->db->select('id,name');
        $this->db->from('countdown');
        $this->db->where('status', 2);
        $query = $this->db->get();
        return $query->result_array();
    }

}
