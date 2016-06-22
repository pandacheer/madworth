<?php

/**
 * @文件： collectioncountry_model
 * @时间： 2015-6-24 9:40:16
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：
 */
class Collectioncountry_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    //查找Collection包含的国家
    function getCountries($collection_id, $fields = array()) {
        $collection = $this->mongo->Collection_country;
        $whereData = array('_id' => $collection_id);
        return $collection->findOne($whereData, $fields);
    }

    function insert($country_codes, $doc) {

        $collectonCountry = [];
        foreach ($country_codes as $country_code) {
            $collection = $this->mongo->{$country_code . '_collection'};
            $collectonCountry[] = array($country_code => 1);
            var_dump($collection->insert($doc));
        }
        $collection = $this->mongo->Collection_country;
        $doc2 = array(
            '_id' => $doc['_id'],
            'country' => $collectonCountry
        );
        $collection->insert($doc2);
    }

    //更新并同步
    //$country：已同步的国家
    //$syncCountry：需要同步的国家
    function update($country, $syncCountry, $collection_id, $doc) {
        sort($country);
        $newCountry = array_unique(array_merge($syncCountry, $country)); //最终同步后的国家
        sort($newCountry);
        if ($country != $newCountry) {
            $collection = $this->mongo->Collection;
            $collection->update(array("_id" => $collection_id), array('$set' => array('country' => $newCountry)));
        }
        $insertDoc = $doc;
        unset($insertDoc['country']);
        $insertDoc['_id'] = $collection_id;
        foreach ($syncCountry as $country_code) {
            $collection = $this->mongo->{$country_code . '_collection'};
            if (in_array($country_code, $country)) {
                $collection->update(array("_id" => $collection_id), $doc);
//                $collection->update(array("_id" => $collection_id), array('$set' => $doc));
            } else {
                $collection->insert($insertDoc);
            }
        }
        return TRUE;
    }

    //删除Collection中同步的国家
    function del($delCountry, $collection_id) {
        foreach ($delCountry as $country_code) {
            $collection = $this->mongo->{$country_code . '_collection'};
            $collection->remove(array('_id' => $collection_id));
        }
        $collection = $this->mongo->Collection;
        $where = array("_id" => $collection_id);
        $param = array('$pullAll' => array('country' => $delCountry));
        $collection->update($where, $param);
        return true;
    }

    function updateStatus($updateCountry, $status, $collection_id) {
        foreach ($updateCountry as $country_code) {
            $collection = $this->mongo->{$country_code . '_collection'};
            $collection->update(array("_id" => $collection_id), array('$set' => array('status' => new MongoInt32($status))));
        }
        return TRUE;
    }

    //查找某个Collection的详细信息
    function getInfoById($country_code, $collection_id, $fields = array()) {
        $collection = $this->mongo->{$country_code . '_collection'};
        return $collection->findOne(array('_id' => $collection_id), $fields);
    }

    function addProduct($product_id, $country_code, $collection_ids) {
        $collection = $this->mongo->{$country_code . '_collection'};
        $collectionIdsArr = explode(',', $collection_ids);
        foreach ($collectionIdsArr as $collection_id) {
            var_dump($collection->update(array('_id' => $collection_id), array('$push' => array('allow' => $product_id))));
        }
    }

}
