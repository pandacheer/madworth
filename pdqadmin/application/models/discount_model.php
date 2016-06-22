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

    /*     * **********************************************
     * 后台调用模块
     * ************************************************ */

    //优惠券列表
    //字段：collection_id,title,type,condition,detail,start,end,status

    function listData($countryCode, $whereData, $sort = 'create_time', $order = 'desc', $offset = 0, $per_page = 10, $fields = '*') {

        $this->db->select($fields);
        $this->db->from($countryCode . '_discount');
        $this->db->where($whereData);
        $this->db->limit($per_page, $offset);
        $this->db->order_by($sort, $order);
        $query = $this->db->get();
        return $query->result_array();
    }

    //添加折扣
    //正确返回1，错误返回0
    function insert($countryCode, $data) {
        if ($this->db->insert($countryCode . '_discount', $data)) {
            $this->redis->delete('Discount_' . $countryCode); //删除折扣collection集合
            return 1;
        } else {
            return 0;
        }
    }

    //更新优惠券状态
    //定时任务：删除过期的
    function updateStatus($countryCode, $collection_id, $status) {
        if ($status == 3) {
            $sql = 'delete from  ' . $countryCode . '_discount where collection_id=' . $collection_id;
        } else {
            $sql = 'update ' . $countryCode . '_discount set status=' . $status . ' where collection_id=' . $collection_id;
        }

        if ($this->db->query($sql)) {
            $this->redis->delete('Discount_' . $countryCode); //删除折扣collection集合
            $key = $this->Key . $countryCode . '_' . $collection_id;
            if ($this->redis->exists($key)) {
                $status < 3 ? $this->redis->hashSet($key, array('status' => $status)) : $this->redis->delete($key);
            }
            return 1;
        } else {
            return 0;
        }
    }

    function count($countryCode, $whereData) {
        $this->db->where($whereData);
        $this->db->from($countryCode . '_discount');
        return $this->db->count_all_results();
    }

    //更新
    function update($countryCode, $collection_id, $updateData) {
        $this->db->where('collection_id', $collection_id);
        if ($this->db->update($countryCode . '_discount', $updateData)) {
            $key = $this->Key . $countryCode . '_' . $collection_id;
            if ($this->redis->exists($key)) {
                $this->redis->hashSet($key, $updateData);
            }
            return 1;
        } else {
            return 0;
        }
    }

}
