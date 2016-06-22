<?php

/**
 * @文件： paymentlog_model
 * @时间： 2015-9-1 14:15:36
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：支付日志
 */
class Paymentlog_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function insert($country_code, $logInfo) {
        $collection = $this->mongo->{$country_code . '_payment_log'};
        $collection->insert($logInfo);
    }

    //put your code here
}
