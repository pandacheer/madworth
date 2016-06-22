<?php

/**
 * @文件： template_model.php
 * @时间： 2015-6-8 21:22:45 
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：  模板模型
 */
class Template_model extends CI_Model {

    var $template_Key = 'SYS_Template_';

    function __construct() {
        parent::__construct();
    }

    //模板管理
    function loadData($whereData, $sort = 'key', $order = 'desc', $offset = 0, $per_page = 10, $total = 0) {
        $result = array();
        $fields = 'id,key,pub_about,public,pri_about,private';
        $result['total'] = $total;
        $this->db->select($fields);
        $this->db->from('SYS_Template');
        $this->db->where($whereData);

        $this->db->order_by($sort, $order);
        $this->db->limit($per_page, $offset);

        $result['rows'] = $this->db->get()->result_array();
        return $result;
    }

    function count($whereData) {
        $this->db->from('SYS_Template');
        $this->db->where($whereData);
        return $this->db->count_all_results();
    }

    function insert($insertData) {
        if ($this->db->insert('SYS_Template', $insertData)) {
            $this->redis->delete($this->template_Key . $insertData['country_code'] . '_' . ($insertData['terminal'] == 1 ? 'pc' : 'mobi'));
            return TRUE;
        } else {
            return false;
        }
    }

    function del($terminal_code, $country_code, $whereData) {
        if ($this->db->delete('SYS_Template', $whereData)) {
            $this->redis->delete($this->template_Key . $country_code . '_' . ($terminal_code == 1 ? 'pc' : 'mobi'));
            return TRUE;
        } else {
            return false;
        }
    }

    function update($terminal_code, $country_code, $whereData, $updateData) {
        $this->db->where($whereData);
        if ($this->db->update('SYS_Template', $updateData)) {
            $this->redis->delete($this->template_Key . $country_code . '_' . ($terminal_code == 1 ? 'pc' : 'mobi'));
            return TRUE;
        } else {
            return FALSE;
        }
    }

    //检测Key值是否存在，返回Key的_id 值
    function checkKey($terminal_code, $country_code, $key) {
        $this->db->where(array('terminal' => $terminal_code, 'country_code' => $country_code, 'key' => $key));
        $this->db->select('id');
        $this->db->from('SYS_Template');
        $this->db->limit(1);
        $row = $this->db->get()->row_array();
        if ($row) {
            return $row['id'];
        } else {
            return 0;
        }
    }
}
