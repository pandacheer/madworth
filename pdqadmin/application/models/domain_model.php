<?php

/**
 * @文件： domain_model
 * @时间： 2015-6-23 13:38:56
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：域名
 */
class Domain_model extends CI_Model {

    private $Key = 'SYS_Doamin';

    function __construct() {
        parent::__construct();
    }

    function listData() {
        if (!$this->redis->exists($this->Key)) {
            $this->db->select('domain,country,status');
            $this->db->from('domain');
            $query = $this->db->get();
            foreach ($query->result() as $item) {
                if ($item->status == 2) {
                    $this->redis->hashSet($this->Key, array($item->domain => $item->country));
                }
            }
        }
        $row = $this->redis->hashGet($this->Key, NULL, 2);
        return $row;
    }

    //后台管理
    function loadData($whereData, $sort = 'country', $order = 'asc', $offset = 0, $per_page = 10, $total = 0) {
        $result = array();
//        $rows = array();
        $fields = 'id,domain,country,status';
        $result['total'] = $total;
        $this->db->select($fields);
        $this->db->from('SYS_domain');
        $this->db->where($whereData);

        $this->db->order_by($sort, $order);
        $this->db->limit($per_page, $offset);
//        $query = $this->db->get();
//        foreach ($query->result_array() as $row) {
//            $rows[] = $row;
//        }
        $result['rows'] = $this->db->get()->result_array();
        return $result;
    }

    function count($whereData) {
        $this->db->from('SYS_domain');
        $this->db->where($whereData);
        return $this->db->count_all_results();
    }

    function update($whereData, $updateData) {
        $this->db->where($whereData);
        $result = $this->db->update('SYS_domain', $updateData);
        if ($this->redis->exists($this->Key)) {
            $this->redis->delete($this->Key);
        }
        return $result;
    }

    function insert($insertData) {
        if ($this->redis->exists($this->Key)) {
            $this->redis->delete($this->Key);
        }
        return $this->db->insert('SYS_domain', $insertData);
    }

    function combobox() {
        $items = array();
        $this->db->select('code,about');
        $this->db->from('domain');
        $this->db->where('status', 2);
        $query = $this->db->get();
        foreach ($query->result() as $row) {
            array_push($items, $row);
        }
        return $items;
    }

}
