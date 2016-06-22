<?php

/**
 * @文件： website_model
 * @时间： 2015-12-16 11:10:00
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：
 */
class Website_model extends CI_Model {

    private $datePRC;

    function __construct() {
        parent::__construct();
        $dateTimePRC = new DateTime('@' . (time() + 28800), new DateTimeZone("PRC"));
        $this->datePRC = $dateTimePRC->format("Ymd");
    }

    //网站UV
    function UVSite($countryCode, $md5IP, $IP = '') {
        $redisKey = 'U:' . $this->datePRC . ':' . $countryCode . ':USER';
        $this->redis->setAdd($redisKey, $md5IP);
        $this->redis->timeOut($redisKey, 259200);

        $redisKey = 'T:' . $this->datePRC . ':' . $countryCode . ':user';
        $this->redis->setAdd($redisKey, $md5IP);
        $this->redis->timeOut($redisKey, 259200);

        $redisIPKey = 'U:' . $this->datePRC . ':' . $countryCode . ':IP';
        $this->redis->setAdd($redisIPKey, $IP);
        $this->redis->timeOut($redisIPKey, 259200);
        $redisIPKey = 'T:' . $this->datePRC . ':' . $countryCode . ':ip';
        $this->redis->setAdd($redisIPKey, $IP);
        $this->redis->timeOut($redisIPKey, 259200);
    }

    //进入网站
    function clickSite($countryCode) {
        $redisKey = 'T:' . $this->datePRC . ':' . $countryCode . ':webSite';
        $this->redis->hashInc($redisKey, 'click', 1);
        $this->redis->timeOut($redisKey, 259200);
    }

    //加入购物车
    function addToCart($countryCode) {
        $redisKey = 'T:' . $this->datePRC . ':' . $countryCode . ':webSite';
        $this->redis->hashInc($redisKey, 'addToCart', 1);
        $this->redis->timeOut($redisKey, 259200);
    }

    //进入购物车
    function checkOut($countryCode) {
        $redisKey = 'T:' . $this->datePRC . ':' . $countryCode . ':webSite';
        $this->redis->hashInc($redisKey, 'checkOut', 1);
        $this->redis->timeOut($redisKey, 259200);
    }

    //执行支付
    function payment($countryCode) {
        $redisKey = 'T:' . $this->datePRC . ':' . $countryCode . ':webSite';
        $this->redis->hashInc($redisKey, 'pay', 1);
        $this->redis->timeOut($redisKey, 259200);
    }

    //支付成功
    function purchased($countryCode) {
        $redisKey = 'T:' . $this->datePRC . ':' . $countryCode . ':webSite';
        $this->redis->hashInc($redisKey, 'purchase', 1);
        $this->redis->timeOut($redisKey, 259200);
    }

}
