<?php

/**
 * @文件： coupons_model
 * @时间： 2015-6-10 15:41:01
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：优惠券
 */
class Coupons_model extends CI_Model {

    private $Key = 'Coupons_';

    function __construct() {
        parent::__construct();
    }

    /*     * **********************************************
     * 通用模块
     * ************************************************ */

    //根据ID查找优惠券信息
    //正确返回array,错误返回 false
    function getInfoById($countryCode, $coupons_id) {
        $key = $this->Key . $countryCode . '_' . $coupons_id;
        if ($this->redis->exists($key)) {
            $row = $this->redis->hashGet($key, NULL, 2);
        } else {
            $fields = 'private,used,type,amount,condition,min,max,frequency,start,end,create_time,update_time,note,creator,display,status';
            $this->db->select($fields);
            $this->db->where('coupons_id', $coupons_id);
            $this->db->limit(1);
            $query = $this->db->get($countryCode . '_coupons');
            if ($query->num_rows() > 0) {
                $row = $query->row_array();
                $this->redis->hashSet($key, $row);
                $row['end'] > time() ? $this->redis->timeOut($key, $row['end'] - time() + 100) : $this->redis->timeOut($key, 600);
            } else {
                $row = false;
            }
        }
        return $row;
    }

    /*     * **********************************************
     * 后台调用模块
     * ************************************************ */

    //优惠券列表
    //字段：coupons_id,private,used,type,amount,order_amount,frequency,start,end,create_time,update_time,creator,status

    function listData($countryCode, $whereData, $sort = 'create_time', $order = 'desc', $offset = 0, $per_page = 10, $fields = '*') {

        $this->db->select($fields);
        $this->db->from($countryCode . '_coupons');
        $this->db->where($whereData);
        $this->db->limit($per_page, $offset);
        $this->db->order_by($sort, $order);
        $query = $this->db->get();
        return $query->result_array();
    }

    //统计Cooupons数量
    function countCoupons($countryCode, $whereData) {
        $this->db->from($countryCode . '_coupons');
        $this->db->where($whereData);
        return $this->db->count_all_results();
    }

    //添加优惠券
    //正确返回1，错误返回0
    function insert($countryCode, $data) {
        if ($this->db->insert($countryCode . '_coupons', $data)) {
            $key = $this->Key . $countryCode . '_' . $data['coupons_id'];
            $this->redis->hashSet($key, $data);
            $this->redis->timeOut($key, $data['end'] - time() + 600);
            return 1;
        } else {
            return 0;
        }
    }

    //更新优惠券状态
    //定时任务：删除过期的
    function updateStatus($countryCode, $coupons_id, $status) {
        $time = time();
        if ($status == 3) {
            $sql = 'update ' . $countryCode . '_coupons set coupons_id="' . $coupons_id . '_' . $time . '", update_time=' . $time . ' , status=' . $status . ' where coupons_id="' . $coupons_id . '"';
        } else {
            $sql = 'update ' . $countryCode . '_coupons set update_time=' . $time . ' , status=' . $status . ' where coupons_id="' . $coupons_id . '"';
        }

        if ($this->db->query($sql)) {
            $key = $this->Key . $countryCode . '_' . $coupons_id;
            if ($this->redis->exists($key)) {
                $this->redis->hashSet($key, array('update_time' => $time, 'status' => $status));
            }
            return 1;
        } else {
            return 0;
        }
    }

    function count($countryCode, $whereData) {
        $this->db->where($whereData);
        $this->db->from($countryCode . '_coupons');
        return $this->db->count_all_results();
    }

    //更新
    function update($countryCode, $coupons_id, $updateCoupons) {
        $this->db->where('coupons_id', $coupons_id);
        if ($this->db->update($countryCode . '_coupons', $updateCoupons)) {
            $key = $this->Key . $countryCode . '_' . $coupons_id;
            if ($this->redis->exists($key)) {
                $this->redis->hashSet($key, $updateCoupons);
            }
            return 1;
        } else {
            return 0;
        }
    }

//    function appendUser($country_code, $coupons_id, $member_emailArr) {
//        $collection = $this->mongo->{$country_code . '_coupons'};
//        $where = array('_id' => $coupons_id);
//        $param = array(
//            '$addToSet' => array(
//                'members' => array(
//                    '$each' => $member_emailArr
//                )
//            )
//        );
//        $collection->update($where, $param, array('upsert' => true));
//        return TRUE;
//    }

    /*     * **********************************************
     * 前端调用模块
     * ************************************************ */

    //检测会员是否有某张优惠券的使用权限
    //订单未使用优惠券前的支付总额（含运费）
    function checkCouponsId($country, $coupons_id, $member_email, $order_amount, $salePriceArr) {
        //优惠券信息
        $couponsInfo = $this->getInfoById($country, $coupons_id);
        if (!is_array($couponsInfo)) {
            return '优惠券不存在';
        }
        if ($couponsInfo['start'] > time()) {
            return '优惠券尚未生效';
        } elseif ($couponsInfo['end'] > time()) {
            return '优惠券已过期';
        } elseif ($couponsInfo['status'] !== 2) {
            return '优惠券已停止使用';
        }
        switch ($couponsInfo['condition']) {
            case 2:
                if ($couponsInfo['min'] > $order_amount) {
                    return '订单金额未能达到' . $couponsInfo['min'];
                }
                break;
            case 3:  //判断购物车中的产品单价
                $ok = FALSE;
                foreach ($salePriceArr as $salePrice) {
                    if ($salePrice >= $couponsInfo['min'] && $salePrice <= $couponsInfo['max']) {
                        $ok = TRUE;
                        break;
                    }
                }
                if (!$ok) {
                    return '没有符合条件的商品';
                }

                break;

            default:
                break;
        }


        $sql = "select surplus_times from {$country}_coupons_member where member_email={$member_email} and coupons_id={$coupons_id}  limit 1";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {//不管是私有还是公有，如果有记录则判断次数
            $couponsMemberInfo = $query->row_array();
            if ($couponsMemberInfo['surplus_times'] == 0) {
                return '优惠券使用次数已用完';
            } else {
                return '可以使用';
            }
        } else {
            if ($couponsInfo['private'] == 2) { //公有
                return '可以使用';
            } else {
                return '不可使用';
            }
        }
    }

    //调用用户的所有有效优惠券
    function getMyCoupons($country, $member_email) {
        //从优惠券主表查找有效的公有优惠券
        $myCoupons = array();
        $publicSql = "SELECT coupons_id,type,amount,frequency,end FROM {$country}_coupons WHERE display=1 and private = 2 AND status = 2 AND start < ? AND end > ?";
        $pubicWhere = array(
            'start' => time(),
            'end' => time()
        );
        $publicQuery = $this->db->query($publicSql, $pubicWhere);
        foreach ($publicQuery->result_array() as $row) {
            $myCoupons[$row['coupons_id']] = array(
                'type' => $row['type'],
                'amount' => $row['amount'],
                'frequency' => $row['frequency'],
                'end' => $row['end']
            );
        }

        //从会员优惠券表中获取私有有效优惠券
        $privateSql = "SELECT coupons_id FROM {$country}_coupons_member WHERE member_email = ? AND surplus_times >0 AND start < ? AND end > ?";
        $privateWhere = array(
            'member_email' => $member_email,
            'start' => time(),
            'end' => time()
        );
        $privateQuery = $this->db->query($privateSql, $privateWhere);
        foreach ($privateQuery->result_array() as $row) {
            if (!array_key_exists($row['coupons_id'], $myCoupons)) {
                $couponsInfo = $this->getInfoById($country, $row['coupons_id']);
                if ($couponsInfo['status'] == 2) {
                    $myCoupons[$row['coupons_id']] = array(
                        'type' => $couponsInfo['type'],
                        'amount' => $couponsInfo['amount'],
                        'frequency' => $couponsInfo['frequency'],
                        'end' => $couponsInfo['end']
                    );
                }
            }
        }
        return $myCoupons;
    }

}
