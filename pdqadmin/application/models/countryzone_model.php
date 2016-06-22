<?php

/**
 * @文件： country_model.php
 * @时间： 2015-6-8 21:22:45 
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：  国家模型
 */
class CountryZone_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getZoneListByCountryCode($country_code) {
        $zoneListKey = 'SYS_Zone_' . $country_code;
        if (!$this->redis->exists($zoneListKey)) {
            $this->load->model('country_model');
            $country_id = $this->country_model->getInfoByCode($country_code, 'country_id');

            $this->_initZone($country_code,$country_id);
        }
        return $this->redis->hashGet($zoneListKey, NULL, 2);
    }

    function _initZone($country_code, $country_id) {
        $zoneListKey = 'SYS_Zone_' . $country_code;
        if (!$this->redis->exists($zoneListKey)) {
            $this->db->select('code,name');
            $this->db->from('country_zone');
            $this->db->where('country_id', $country_id);
            $this->db->where(array('status' => 1));
            $query = $this->db->get();
            foreach ($query->result_array() as $item) {
                $this->redis->hashSet($zoneListKey, array($item['code'] => $item['name']));
            }
        }
    }

}
