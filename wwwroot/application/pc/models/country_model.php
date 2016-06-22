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
class Country_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    //根据国家代码获取国家信息
    function getInfoByCode($country_code, $fields = array('name', 'currency_symbol')) {
        $countryKey = 'SYS_CountryInfo_' . $country_code;
        if (!$this->redis->exists($countryKey)) {
            $this->db->select('country_id,name,domain,flag_sort,iso_code_2 as country_code,iso_code_3,language_code,currency_symbol,currency_payment,service_mail,au_rate,timezone,google,facebook,facebook_id');
            $this->db->from('country');
            $this->db->limit(1);
            $this->db->where(array('status' => 2, 'iso_code_2' => $country_code));
            $row = $this->db->get()->row_array();
            if ($row) {
                $this->redis->hashSet($countryKey, $row);
            } else {
                return FALSE;
            }
        }
        return $this->redis->hashGet($countryKey, $fields, 2);
    }

    //根据域名获取国家信息
    function getInfoByDomain($domain, $fields = array('name', 'currency_symbol')) {
        $domainKey = 'SYS_DoaminPC';
        $countryKeyPre = 'SYS_CountryInfo_';
        if (!$this->redis->hashExists($domainKey, $domain)) {
            $this->db->select('country_id,name,domain,flag_sort,iso_code_2 as country_code,iso_code_3,language_code,currency_symbol,currency_payment,service_mail,au_rate,timezone,google,facebook,facebook_id');
            $this->db->from('country');
            $this->db->limit(1);
            $this->db->where(array('status' => 2, 'domain' => $domain));
            $row = $this->db->get()->row_array();
            if ($row) {
                $this->redis->hashSet($domainKey, array($domain => $row['country_code']));
                $countryKey = $countryKeyPre . $row['country_code'];
                $this->redis->hashSet($countryKey, $row);
                $result = $this->redis->hashGet($countryKey, $fields, 2);
            } else {
                return false;
            }
        } else {
            $country_code = $this->redis->hashGet($domainKey, $domain, 1);
            $result = $this->getInfoByCode($country_code, $fields);
        }
        return $result;
    }

    //获取国家代码集合
    function getCountryCodeSet() {
        $countrySetKey = 'SYS_CountryCodeSet';
        if (!$this->redis->exists($countrySetKey)) {
            $this->db->select('name,iso_code_2 as country_code');
            $this->db->from('country');
            $this->db->where(array('status' => 2));
            $query = $this->db->get();
            foreach ($query->result_array() as $item) {
                $this->redis->setAdd($countrySetKey, $item['country_code'], 0);
            }
        }
        return $this->redis->setMembers($countrySetKey);
    }

    /* 获取国家列表
     * 返回二维数组
     */

    function getCountryList($fields = array('name', 'domain')) {
        $countryList = array();
        $countryCodeSet = $this->getCountryCodeSet();
        foreach ($countryCodeSet as $countryCode) {
            $countryInfo = $this->getInfoByCode($countryCode, $fields);
            $countryList[$countryCode] = $countryInfo;
        }
        return $countryList;
    }

}

?>
