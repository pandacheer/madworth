<?php

/**
 * @文件： language_model
 * @时间： 2015-6-23 13:38:56
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：语言
 */
class Language_model extends CI_Model {

    private $Key = 'SYS_Language';

    function __construct() {
        parent::__construct();
    }

    function listData() {
        if (!$this->redis->exists($this->Key)) {
            $this->db->select('code,about');
            $this->db->from('language');
            $this->db->where(array('status' => 2));
            $query = $this->db->get();
            foreach ($query->result() as $item) {
                $this->redis->hashSet($this->Key, array($item->code => $item->about));
            }
        }
        $row = $this->redis->hashGet($this->Key, NULL, 2);
        return $row;
    }

    //后台管理
    function loadData($whereData, $sort = 'code', $order = 'asc', $offset = 0, $per_page = 10, $total = 0) {
        $result = array();
        $rows = array();
        $fields = 'code,about,status';
        $result['total'] = $total;
        $this->db->select($fields);
        $this->db->from('language');
        $this->db->where($whereData);

        $this->db->order_by($sort, $order);
        $this->db->limit($per_page, $offset);
        $query = $this->db->get();
        foreach ($query->result_array() as $row) {
            $rows[] = $row;
        }
        $result['rows'] = $rows;
        return $result;
    }

    function count($whereData) {
        $this->db->from('language');
        $this->db->where($whereData);
        return $this->db->count_all_results();
    }

    function update($whereData, $updateData) {
        $this->db->where($whereData);
        $result = $this->db->update('language', $updateData);
        if ($this->redis->exists($this->Key)) {
            $this->redis->delete($this->Key);
        }
        return $result;
    }

    function insert($insertData) {
        if ($this->redis->exists($this->Key)) {
            $this->redis->delete($this->Key);
        }
        return $this->db->insert('language', $insertData);
    }

    function combobox() {
        $items = array();
        $this->db->select('code,about');
        $this->db->from('language');
        $this->db->where('status', 2);
        $query = $this->db->get();
        foreach ($query->result() as $row) {
            array_push($items, $row);
        }
        return $items;
    }

}
