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
        $countryKey = 'SYS_CountryInfo_';
        if (!$this->redis->hashExists($domainKey, $domain)) {
            $this->db->select('country_id,name,domain,flag_sort,iso_code_2 as country_code,iso_code_3,language_code,currency_symbol,currency_payment,service_mail,au_rate,timezone,google,facebook,facebook_id');
            $this->db->from('country');
            $this->db->limit(1);
            $this->db->where(array('status' => 2, 'domain' => $domain));
            $row = $this->db->get()->row_array();
            if ($row) {
                $this->redis->hashSet($domainKey, array($domain => $row['country_code']));
                $countryKey.=$row['country_code'];
                if (!$this->redis->exists($countryKey)) {
                    $this->redis->hashSet($countryKey, $row);
                    $result = $this->redis->hashGet($countryKey, $fields, 2);
                }
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
     * 参数为数组，返回二维数组
     * 参数为字符串，返回一维数组
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


    //根据语种代码获取国家代码集合
    //返回值：array('US','EN')
    function getCountryByLangCode($language_code) {
        $languageKey = 'SYS_Language_' . $language_code; //集合
        if (!$this->redis->exists($languageKey)) {
            $this->_initCountry();
        }
        return $this->redis->setMembers($languageKey);
    }



    //初始化国家redis
    //生成：
    //CountryInfo：'FR'=>'France, Metropolitan'   'US'=>'United States'
    //www.pdq.com:
    //Language_en：['US','EN'](集合)
    function _initCountry() {
        $languageKey = 'SYS_Language_'; //集合
        $countryCodeSet = $this->getCountryCodeSet();
        foreach ($countryCodeSet as $countryCode) {
            $language_code = $this->getInfoByCode($countryCode, 'language_code');
            $this->redis->setAdd($languageKey . $language_code, $countryCode, 0);
        }
    }

    
    //后台管理
    function loadData($whereData, $sort = 'status', $order = 'desc', $offset = 0, $per_page = 10, $total = 0) {
        $result = array();
        $rows = array();
        $fields = 'country_id,name,domain,flag_sort,iso_code_2,iso_code_3,language_code,currency_symbol,currency_payment,service_mail,au_rate,status,timezone,google,facebook,facebook_id';
        $result['total'] = $total;
        $this->db->select($fields);
        $this->db->from('country');
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
        $this->db->from('country');
        $this->db->where($whereData);
        return $this->db->count_all_results();
    }

    function update($whereData, $updateData,$countryCode) {
        $this->db->where($whereData);
        if ($this->db->update('country', $updateData)) {
            $countryKey = 'SYS_CountryInfo_' . $countryCode;
            $domainPcKey = 'SYS_DoaminPC';
            $this->redis->delete($countryKey);
            $this->redis->delete($domainPcKey);

            $this->redis->delete($this->redis->getKeys('SYS_Language*'));
            $this->redis->delete($this->redis->getKeys('SYS_CountryCodeSet'));
            $this->redis->hashDel('SYS_Parameter', 'RMBtoAU'); //更新了国家配置，直接删除澳币对人民币汇率
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function getRMBtoAU() {
        $parametersKey = 'SYS_Parameter'; //hash
        if (!$this->redis->hashExists($parametersKey, 'RMBtoAU')) {
            $this->db->select('au_rate');
            $this->db->where('iso_code_2', 'CN');
            $this->db->limit(1);
            $this->db->from('country');
            $query = $this->db->get();
            $row = $query->row_array();
            if ($row) {
                $this->redis->hashSet($parametersKey, array('RMBtoAU' => $row['au_rate']));
                return $row['au_rate'];
            } else {
                return false;
            }
        } else {
            return $this->redis->hashGet($parametersKey, 'RMBtoAU', 2);
        }
    }
    function combobox() {
        $items = array();
        $this->db->select('iso_code_2 as code,name');
        $this->db->from('country');
        $this->db->where('status', 2);
        $query = $this->db->get();
        foreach ($query->result() as $row) {
            array_push($items, $row);
        }
        return $items;
    }
}