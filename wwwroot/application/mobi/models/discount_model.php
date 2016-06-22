<?php

/**
 * @文件： discount_model
 * @时间： 2016-2-10 15:41:01
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：优惠券
 */
class Discount_model extends CI_Model {

    private $Key = 'Discount_';

    function __construct() {
        parent::__construct();
    }

    /*     * **********************************************
     * 通用模块
     * ************************************************ */

    //根据ID查找优惠券信息
    //正确返回array,错误返回 false
    function getInfoById($countryCode, $collection_id) {
        $key = $this->Key . $countryCode . '_' . $collection_id;
        if ($this->redis->exists($key)) {
            $row = $this->redis->hashGet($key, NULL, 2);
        } else {
            $fields = 'collection_id,title,type,condition,detail,start,end,status';
            $this->db->select($fields);
            $this->db->where('collection_id', $collection_id);
            $this->db->limit(1);
            $query = $this->db->get($countryCode . '_discount');
            if ($query->num_rows() > 0) {
                $row = $query->row_array();
                $this->redis->hashSet($key, $row);
                $this->redis->timeOut($key, $row['end'] - time() + 100);
            } else {
                $row = false;
            }
        }
        return $row;
    }

    //获取 Discount Collection ID 集合
    function getDiscountSet($countryCode) {
        $discountSetKey = 'Discount_' . $countryCode;
        if (!$this->redis->exists($discountSetKey)) {
            $this->db->select('collection_id');
            $this->db->from($countryCode . '_discount');
            $this->db->where(array('status' => 2));
            $query = $this->db->get();
            foreach ($query->result_array() as $item) {
                $this->redis->setAdd($discountSetKey, $item['collection_id'], 0);
            }
        }
        return $this->redis->setMembers($discountSetKey);
    }

}
