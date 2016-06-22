<?php

/**
 * @文件： shipping_model
 * @时间： 2015-8-8 10:38:02
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：
 */
class shipping_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getShipping($country_code) {
        $collection = $this->mongo->shipping;
        $doc = $collection->findOne(array('_id' => $country_code));
        return $doc['model'];
    }

    function getShipById($id) {
        $collection = $this->mongo->shipping;
        $doc = $collection->aggregate(array('$project' => array('model' => 1, '_id' => 1)), array('$unwind' => '$model'), array('$match' => array('model.id' => new MongoInt32($id))));
        if ($doc['ok']) {
            $doc['result'][0]['model']['country_code']=$doc['result'][0]['_id'];
            return $doc['result'][0]['model'];
        }else{
            return false;
        }
    }

    function getDeliveryMethods($country_code) {
        $collection = $this->mongo->shipping;
        $this->page['shippingArr'] = $collection->find(array('_id' => $country_code));
    }

}
