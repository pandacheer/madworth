<?php

/**
 * @文件： collection_model
 * @时间： 2015-6-24 9:40:16
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：
 */
class collection_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    // Collection是否存在
    public function has_collection($country_code, $seo_url) {
        $collection = $this->mongo->{$country_code . '_collection'};
        $where = array(
            'seo_url' => new MongoRegex('/^' . $seo_url . '$/i')
        );
        return $collection->findOne($where);
    }

    //根据产品ID获取已加入的Collection
    function getListByProductId($country_code, $product_id) {
        $collection = $this->mongo->{$country_code . '_collection'};
        $where = array(
            'allow' => new MongoId($product_id)
        );
        $collection = $collection->findOne($where, array('allow' => TRUE));
        if ($collection) {
            return $collection['allow'];
        } else {
            return false;
        }
    }

    //根据产品ID获取对应的Collection
    function getCollectionUrl($country_code, $product_id, $seo_title = false) {
        $collection = $this->mongo->{$country_code . '_collection'};
        $where = array(
            'allow' => new MongoId($product_id)
        );


        if (!$seo_title) {
            $collection = $collection->findOne($where, array('seo_url' => TRUE));
            return $collection['seo_url'];
        } else {
            $collection = $collection->findOne($where, array('seo_url' => TRUE, 'seo_title' => 1));
            return array($collection['seo_url'], $collection['seo_title']);
        }
    }

    //修改Collection中产品的排序规则
    function changeSort($country_code, $collection_id, $sort) {
        $collection = $this->mongo->{$country_code . '_collection'};
        $whereData = array('_id' => $collection_id);
        $updateData = array('$set' => array('sort' => $sort, 'allow' => []));
        $result = $collection->update($whereData, $updateData);
        return $result['ok'];
    }

    //考虑多个collection的情况，获取所在collection中所有产品
    function getListFromAllCollectionByProductId($country_code, $product_id) {
        $collection = $this->mongo->{$country_code . '_collection'};
        if (!empty($product_id)) {
            $product_id = array_map(function($v) {
                return new MongoId($v);
            }, $product_id);
        }
        $where = array(
            'allow' => array('$in' => $product_id)
        );
        $collection = $collection->find($where, array('allow' => TRUE, '_id' => 0));
        if ($collection) {
            $tmp = array();
            foreach ($collection as $key => $val) {
                foreach ($val['allow'] as $key1 => $val1) {
                    $tmp[] = $val1;
                }
            }
            return $tmp;
        } else {
            return false;
        }
    }

    //根据产品ID获取对应的Collection信息
    //fields格式：fieldName1,FieldName2
    function getInfoByProID($country_code, $product_id, $fields) {
        $fieldList = explode(',', $fields);
        $getFields = [];
        foreach ($fieldList as $fieldName) {
            $getFields[$fieldName] = TRUE;
        }
        $collection = $this->mongo->{$country_code . '_collection'};
        $where = array(
            'allow' => new MongoId($product_id)
        );

        $collectionInfo = $collection->find($where, $getFields);
        return $collectionInfo;
    }

}
