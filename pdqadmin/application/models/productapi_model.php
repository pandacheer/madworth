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
class Productapi_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function stopostdata($datas = array()) {
        $time = time();
        $data = array(
            'data' => json_encode($datas),
            'create_time' => $time
        );

        $this->db->insert('SYS_Product_API', $data);
    }
}
