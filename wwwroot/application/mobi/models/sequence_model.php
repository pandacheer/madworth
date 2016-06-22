<?php

/**
 * @文件： Sequence_model
 * @时间： 2015-6-7 22:04:54
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：生成序列 模型
 */
class Sequence_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    //创建订单内部编号
    public function CreateOrderNumber() {
//        $OrderNumberKey = 'SYSOrderNumber_' . date('ymdHi');
//        $incr = $this->redis->deinc($OrderNumberKey);
//        $OrderNumber = date('y') . str_pad(date('W'), 2, '0', STR_PAD_LEFT) . date('N') . str_pad(time() - strtotime(date('Y-m-d')), 5, '0', STR_PAD_LEFT) . str_pad($incr, 3, '0', STR_PAD_LEFT);

        $dateTimePRC = new DateTime('@' . (time() + 28800), new DateTimeZone("PRC"));
        $OrderNumberKey = 'SYSOrderNumber_' . $dateTimePRC->format('ymdHi');
        $incr = $this->redis->deinc($OrderNumberKey);
        $OrderNumber = $dateTimePRC->format('y') . str_pad($dateTimePRC->format('W'), 2, '0', STR_PAD_LEFT) . $dateTimePRC->format('N') . str_pad(strtotime($dateTimePRC->format('Y-m-d H:i:s')) - strtotime($dateTimePRC->format('Y-m-d')), 5, '0', STR_PAD_LEFT) . str_pad($incr, 3, '0', STR_PAD_LEFT);
        $this->redis->timeOut($OrderNumberKey, 100);
        return $OrderNumber;
    }

    //创建退款单号
//    public function CreateRefundId() {
//        $RefundIdKey = 'SYSRefundId_' . date('ymdH');
//
//        $incr = $this->redis->deinc($RefundIdKey);
//        $RefundId = date('y') . str_pad(date('W'), 2, '0', STR_PAD_LEFT) . date('N') . str_pad(time() - strtotime(date('Y-m-d')), 5, '0', STR_PAD_LEFT) . str_pad($incr, 5, '0', STR_PAD_LEFT);
//        $this->redis->timeOut($RefundIdKey, 86400);
//        return $RefundId;
//    }

    //创建倒计时ID
//    public function CreateCountdownId() {
//        $CountdownIdKey = 'SYSCountdownId_' . date('ymdH');
//        $this->redis->timeOut($CountdownIdKey, 86400);
//        $incr = $this->redis->deinc($CountdownIdKey);
//        $CountdownId = date('y') . str_pad(date('W'), 2, '0', STR_PAD_LEFT) . date('N') . str_pad(time() - strtotime(date('Y-m-d')), 5, '0', STR_PAD_LEFT) . str_pad($incr, 5, '0', STR_PAD_LEFT);
//        return $CountdownId;
//    }

    //创建collection ID
//    public function CreateCollectionId() {
//        $CollectionIdKey = 'SYSCollectionId_' . date('ymdH');
//        $this->redis->timeOut($CollectionIdKey, 86400);
//        $incr = $this->redis->deinc($CollectionIdKey);
//        $CollectionId = date('y') . str_pad(date('W'), 2, '0', STR_PAD_LEFT) . date('N') . str_pad(time() - strtotime(date('Y-m-d')), 5, '0', STR_PAD_LEFT) . str_pad($incr, 5, '0', STR_PAD_LEFT);
//        return $CollectionId;
//    }

}
