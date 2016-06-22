<?php

/**
 * @文件： countdown_model
 * @时间： 2015-6-10 10:48:32
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：倒计时
 */
class countdown_model extends CI_Model {

    private $CountDownKeyPre = 'CountDown_';
    private $CountDownKey;

    function __construct() {
        parent::__construct();
    }

    //根据产品ID获得倒时计ID
    //正常返回倒计时ID，错误返回0
    function getInfoByProductId($country_code, $product_id) {
        $collection = $this->mongo->{$country_code . '_countdown'};
        $where = array(
            'product' => new MongoId($product_id)
        );
        $result = $collection->findOne($where, array('_id' => TRUE));
        if (is_array($result)) {
            return $result['_id'];
        } else {
            return 0;
        }
    }

    //根据倒计时ID查找倒计时信息
    //正确返回array,错误返回 false
    function getInfoById($countdown_id) {

        $this->CountDownKey = $this->CountDownKeyPre . $countdown_id;
        if ($this->redis->exists($this->CountDownKey)) {
            $row = $this->redis->hashGet($this->CountDownKey, NULL, 2);
        } else {
            $fields = 'id,name,start,end,cycle,auto_recount,price,rate,decimal,creator,create_time,update_time,status';
            $this->db->select($fields);
            $this->db->where('id', $countdown_id);
            $this->db->limit(1);
            $query = $this->db->get('countdown');
            if ($query->num_rows() > 0) {
                $row = $query->row_array();
                $this->redis->hashSet($this->CountDownKey, $row);
                if ($row['auto_recount'] == 2) {
                    $row['end'] > time() ? $this->redis->timeOut($this->CountDownKey, $row['end'] - time() + 100) : $this->redis->timeOut($this->CountDownKey, 600);
                }
            } else {
                $row = false;
            }
        }
        return $row;
    }

    //计算倒计时价
    //$price商品价格
    function getPrice($countdown_id, $price) {
        $countDownFields = $this->getInfoById($countdown_id);
        $resultPrice = $price;
        if (is_array($countDownFields)) {
            if ($countDownFields['status'] == 2) {//倒计时是否开启
                $time = time();
                if ($countDownFields['start'] < $time) {//倒计时是否开始
                    if ($countDownFields['auto_recount'] == 2) {//倒计时自动续期
                        $resultPrice = $this->calculatePrice($price, $countDownFields['price'], $countDownFields['rate'], $countDownFields['decimal']);
                    } else {
                        if ($countDownFields['end'] > $time) {//倒计时在有效周期内
                            $resultPrice = $this->calculatePrice($price, $countDownFields['price'], $countDownFields['rate'], $countDownFields['decimal']);
                        }
                    }
                }
            }
        }
        return $resultPrice;
    }

    private function calculatePrice($price, $lessPrice, $lessScale, $lastNumber) {
        $result = round(($price - $lessPrice) * (1 - $lessScale / 100));
        if ($lastNumber > -1) {
            $result = floor($result / 100) * 100 + $lastNumber;
        }
        return $result;
    }

    //前端调用
    //计算倒计时距离结束的时间
    //$start：倒计时起始时间
    //$cycle：轮转周期
    function getEndTime($start, $cycle) {
        $time = time();
        $hasBeenExecuted = $time - $start; //倒计时总共执行时长
        $hasBeenExecutedTime = $hasBeenExecuted % $cycle; //最后一轮已执行时长
        $laveTime = $cycle - $hasBeenExecutedTime; //最后一轮剩余时长
        return ($time + $laveTime) * 1000;
//        return date('Y/m/d H:i:s', time() + $cycle - $hasBeenExecutedTime);
//        $laveTime = $cycle - $hasBeenExecutedTime; //最后一轮剩余时长
//        $result['day'] = floor($laveTime / 86400);
//        $result['hour'] = floor($laveTime % 86400 / 3600);
//        $result['minute'] = floor($laveTime % 86400 % 3600 / 60);
//        $result['second'] = $laveTime % 86400 % 3600 % 60;
//        return $result;
    }

}
