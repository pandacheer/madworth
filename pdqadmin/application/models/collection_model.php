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

    function insert($country_codes, $doc, $api = false) {
        $CollectionIdKey = 'SYSCollectionId_' . date('ymdH');
        $incr = $this->redis->deinc($CollectionIdKey);
        $this->redis->timeOut($CollectionIdKey, 86400);
        $doc['_id'] = date('y') . str_pad(date('W'), 2, '0', STR_PAD_LEFT) . date('N') . str_pad(time() - strtotime(date('Y-m-d')), 5, '0', STR_PAD_LEFT) . str_pad($incr, 3, '0', STR_PAD_LEFT);

        $collectonCountry = [];
        foreach ($country_codes as $country_code) {
            $collection = $this->mongo->{$country_code . '_collection'};
            $collectonCountry[] = $country_code;
            $collection->insert($doc);
        }
        $collection = $this->mongo->Collection_country;
        if ($api) {
            $doc2 = array(
                '_id' => $doc['_id'],
                'hide' => [],
                'show' => $collectonCountry
            );
        } else {
            $doc2 = array(
                '_id' => $doc['_id'],
                'hide' => $collectonCountry,
                'show' => []
            );
        }
        $collection->insert($doc2);
        return $doc['_id'];
    }

    //查找Collection
    function listData($country_code = 'AU', $whereData = array(), $fields = array(), $offset = 0, $per_page = 10) {
        $collection = $this->mongo->{$country_code . '_collection'};
        if ($per_page == 'ALL') {
            return $collection->find($whereData, $fields)->sort(array('create_time' => -1));
        } else {
            return $collection->find($whereData, $fields)->sort(array('create_time' => -1))->limit($per_page)->skip($offset);
        }
    }

    function count($country_code = 'AU', $whereData = array()) {
        $collection = $this->mongo->{$country_code . '_collection'};
        return $collection->find($whereData)->count();
    }

    //更新并同步
    //$syncCountry_have：已同步的国家
    //$syncCountry：需要同步的国家
    function sync($syncCountry_have, $syncCountry, $collection_id, $doc, $api = false) {
        sort($syncCountry_have);
        $newCountry = array_unique(array_merge($syncCountry, $syncCountry_have)); //最终同步后的国家
        sort($newCountry);
        $collectionCountry = $this->mongo->Collection_country;

        $insertDoc = $doc;
        unset($insertDoc['country']);
        $insertDoc['_id'] = $collection_id;
        foreach ($syncCountry as $country_code) {
            $collection = $this->mongo->{$country_code . '_collection'};
            if (in_array($country_code, $syncCountry_have)) {
                $collection->update(array("_id" => $collection_id), $doc);
            } else {
                $collection->insert($insertDoc);
            }
            if ($api) {
                $collectionCountry->update(array("_id" => $collection_id), array('$addToSet' => array('show' => $country_code), '$pull' => array('hide' => $country_code)));
            } else {
                $collectionCountry->update(array("_id" => $collection_id), array('$addToSet' => array('hide' => $country_code), '$pull' => array('show' => $country_code)));
            }
        }
        return TRUE;
    }

    //2015/07/18改为只更新当前国家
    function update($country_code, $collection_id, $doc) {
        $collectionCountry = $this->mongo->Collection_country;
        if ($doc['status'] == 1) {
            $collectionCountry->update(array("_id" => $collection_id), array('$addToSet' => array('hide' => $country_code), '$pull' => array('show' => $country_code)));
        } else {
            $collectionCountry->update(array("_id" => $collection_id), array('$addToSet' => array('show' => $country_code), '$pull' => array('hide' => $country_code)));
        }

        $collection = $this->mongo->{$country_code . '_collection'};
        $result = $collection->update(array("_id" => $collection_id), array('$set' => $doc));
        return $result['ok'];
    }

    //删除Collection中同步的国家
    function del($delCountry, $collection_id) {
        $collectionCountry = $this->mongo->Collection_country;
        foreach ($delCountry as $country_code) {
            $collection = $this->mongo->{$country_code . '_collection'};
            $collection->remove(array('_id' => $collection_id));

            $where = array("_id" => $collection_id);
            $param = array('$pull' => array('show' => $country_code, 'hide' => $country_code));
            $collectionCountry->update($where, $param);
        }
        return true;
    }

    function updateStatus($updateCountry, $status, $collection_id) {
        $collectionCountry = $this->mongo->Collection_country;
        foreach ($updateCountry as $country_code) {
            $collection = $this->mongo->{$country_code . '_collection'};
            $collection->update(array("_id" => $collection_id), array('$set' => array('status' => new MongoInt32($status))));
            if ($status == 1) {
                $collectionCountry->update(array("_id" => $collection_id), array('$addToSet' => array('hide' => $country_code), '$pull' => array('show' => $country_code)));
            } else {
                $collectionCountry->update(array("_id" => $collection_id), array('$addToSet' => array('show' => $country_code), '$pull' => array('hide' => $country_code)));
            }
        }
        return TRUE;
    }

    //查找某个Collection的详细信息
    function getInfoById($country_code, $collection_id, $fields = array()) {
        $collection = $this->mongo->{$country_code . '_collection'};
        return $collection->findOne(array('_id' => $collection_id), $fields);
    }

    function getInfoByTitle($country_code, $collection_title, $fields = array()) {
        $collection = $this->mongo->{$country_code . '_collection'};
        return $collection->findOne(array('title' => $collection_title), $fields);
    }

    //产品加入Collection
    //$productIdArr：产品数组array('id1','id2');
    //$countryCodeArr：国家代码数组array('AU','US')
    //$collection_ids：collection串 collection_id1，collection_id2，collection_id3
    function addProduct($productIdArr, $countryCodeArr, $collection_ids) {
        $collectionIdArr = explode(',', $collection_ids);
        $where = array('_id' => array('$in' => $collectionIdArr));
        $param = array(
            '$addToSet' => array(
                'allow' => array(
                    '$each' => $productIdArr
                )
            ),
            '$pullAll' => array(
                'disallow' => $productIdArr
            )
        );
        foreach ($countryCodeArr as $country_code) {
            $collection = $this->mongo->{$country_code . '_collection'};
            $msg[] = $collection->update($where, $param);
        }
        return true;
    }

    //添加一个产品到Collection
    //$product_id：产品ID
    //$country_codes：国家代码串：AU,US
    //$collection_ids：collection串 collection_id1，collection_id2，collection_id3
    function addOneProduct($product_id, $country_codes, $collection_ids) {
        if (!is_array($collection_ids)) {
            $collectionIdArr = explode(',', $collection_ids);
        } else {
            $collectionIdArr = $collection_ids;
        }
        $countryCodeArr = explode(',', $country_codes);
        //$removeWhere = array('allow' => new MongoId($product_id));
        $findWhere = array('allow' => new MongoId($product_id));
        $removeParam = array(
            '$pull' => array(
                'allow' => new MongoId($product_id)
            )
        );
        //$updateWhere = array('_id' => array('$in' => $collectionIdArr));
        $nl = [];
        foreach ($countryCodeArr as $country_code) {
            $collection = $this->mongo->{$country_code . '_collection'};
            $collection_id = $collection->find($findWhere, array('_id' => 1));
            $o = array_values(iterator_to_array($collection_id));
            if (!empty($o)) {
                foreach ($o as $key => $value) {
                    $o[$key] = $value['_id'];
                }
            }
            $removeCollection = array_values(array_diff($o, $collectionIdArr));
            $updateCollection = array_values(array_diff($collectionIdArr, $o));
            if (!empty($updateCollection)) {
                $collection_newlasts = $collection->find(array('_id' => array('$in' => $updateCollection)), array('_id' => 1, 'newlast' => 1));
                if (!empty($collection_newlasts)) {
                    foreach ($collection_newlasts as $key => $value) {
                        $nl[$value['_id']] = isset($value['newlast']) ? $value['newlast'] : 0;
                    }
                }
            }
            if (!empty($removeCollection)) {
                $removeCollection = array_values($removeCollection);
                $removeWhere = array('_id' => array('$in' => $removeCollection));
                $collection->update($removeWhere, $removeParam, array('multiple' => true));
            }
            if (!empty($updateCollection)) {
                $updateCollection = array_values($updateCollection);
                foreach ($updateCollection as $vv) {
                    $updateWhere = array('_id' => $vv);
                    if (isset($nl[$vv]) && $nl[$vv] == 1) {
                        $updateParam = array(
                            '$push' => array(
                                'allow' => new MongoId($product_id)
                            )
                        );
                    } else {
                        $updateParam = array(
                            '$push' => array(
                                'allow' => array(
                                    '$each' => array(new MongoId($product_id)),
                                    '$position' => 0
                                )
                            )
                        );
                    }
                    $collection->update($updateWhere, $updateParam);
                }
            }
            //$collection->update($removeWhere, $removeParam, array('multiple' => true));
            //$collection->update($updateWhere, $updateParam, array('multiple' => true));
            //$insert_data = array('table_name'=>$country_code . '_collection','command'=>2,'data'=>json_encode($removeParam),'condition'=>json_encode($removeWhere),'multiple'=>1);
            //$this->db->insert('mongodb_queue',$insert_data);
            //$insert_data = array('table_name'=>$country_code . '_collection','command'=>2,'data'=>json_encode($updateParam),'condition'=>json_encode($updateWhere),'multiple'=>1);
            //$this->db->insert('mongodb_queue',$insert_data);
        }
        return true;
    }

    //根据产品ID获取已加入的Collection
    function getListByProductId($country_code, $product_id) {
        $collection = $this->mongo->{$country_code . '_collection'};
        $where = array(
            'allow' => new MongoId($product_id)
        );
        return $collection->find($where, array('_id' => TRUE));
    }

    //修改Collection中产品的排序规则
    function changeSort($country_code, $collection_id, $sort, $productIDs) {
//        $doc = array(
//            'allow' => $productIDs
//        );
        $collection = $this->mongo->{$country_code . '_collection'};
        $whereData = array('_id' => $collection_id);
        $updateData = array('$set' => array('sort' => $sort, 'allow' => $productIDs));
        $result = $collection->update($whereData, $updateData);
        return $result['ok'];
    }

    function getCollectionByProductId($country_code, $product_id) {
        $collection = $this->mongo->{$country_code . '_collection'};
        $where = array(
            'allow' => new MongoId($product_id)
        );
        return $collection->find($where, array('_id' => false, 'seo_url' => 1))->limit(1);
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

    function hasExists($country_code = 'AU', $seo_url = '', $_id = '') {
        if (!$seo_url || !$_id)
            return false;
        $collection = $this->mongo->{$country_code . '_collection'};
        $data = $collection->findOne(array('seo_url' => $seo_url, '_id' => array('$ne' => $_id)), array('_id' => 1));
        if ($data['_id']) {
            return true;
        }
        return false;
    }

}
