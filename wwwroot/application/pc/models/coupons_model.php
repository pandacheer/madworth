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
    function getInfoById($countryCode, $coupons_id, $fields = 'private,used,type,amount,condition,min,max,frequency,start,end,create_time,update_time,note,creator,display,status') {
        $key = $this->Key . $countryCode . '_' . $coupons_id;
        $fieldsArr = explode(",", $fields);
        if ($this->redis->exists($key)) {
            $row = $this->redis->hashGet($key, $fieldsArr, 2);
        } else {
            $this->db->select('private,used,type,amount,condition,min,max,frequency,start,end,create_time,update_time,note,creator,display,status');
            $this->db->where('coupons_id', $coupons_id);
            $this->db->limit(1);
            $query = $this->db->get($countryCode . '_coupons');
            if ($query->num_rows() > 0) {
                $temp = $query->row_array();
                $this->redis->hashSet($key, $temp);
                $temp['end'] > time() ? $this->redis->timeOut($key, $temp['end'] - time() + 100) : $this->redis->timeOut($key, 600);
                $row = $this->redis->hashGet($key, $fieldsArr, 2);
            } else {
                $row = false;
            }
        }
        return $row;
    }

    /*     * **********************************************
     * 前端调用模块
     * ************************************************ */

    //检测会员是否有某张优惠券的使用权限
    //订单未使用优惠券前的支付总额（含运费）
    function checkCouponsId($country_code, $coupons_id, $member_email = '', $order_amount = 0, $salePriceArr = '', $couponFields = 'private,used,type,amount,condition,min,max,frequency,start,end,create_time,update_time,creator,status') {
        //优惠券信息

        $couponsInfo = $this->getInfoById($country_code, $coupons_id, $couponFields);
        if (!is_array($couponsInfo)) {
            return array('success' => FALSE, 'error' => 'coupon_Exist'); // '优惠券不存在';
        }
        if ($couponsInfo['start'] > time()) {
            return array('success' => FALSE, 'error' => 'coupon_Ineffective'); // '优惠券尚未生效';
        } elseif ($couponsInfo['end'] < time()) {
            return array('success' => FALSE, 'error' => 'coupon_Expired'); // '优惠券已过期';
        } elseif ($couponsInfo['status'] != 2) {
            return array('success' => FALSE, 'error' => 'coupon_Stop'); // '优惠券已停止使用';
        }

        if ($member_email) {
            if ($order_amount) {
                switch ($couponsInfo['condition']) {
                    case 2:
                        if ($couponsInfo['min'] > $order_amount) {
                            return array('success' => FALSE, 'error' => 'coupon_OrderConditions'); // '订单金额未能达到' . $couponsInfo['min'];
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
                            return array('success' => FALSE, 'error' => 'coupon_Product'); //  '没有符合条件的商品';
                        }

                        break;

                    default:
                        break;
                }
            }


            $sql = "select surplus_times,end from {$country_code}_coupons_member where member_email='{$member_email}' and coupons_id='{$coupons_id}'  limit 1";
            $query = $this->db->query($sql);
            if ($query->num_rows() > 0) {//不管是私有还是公有，如果有记录则判断次数
                $couponsMemberInfo = $query->row_array();
                //2015.12.9 加入以下3行
                if ($couponsMemberInfo['end'] < time()) {
                    return array('success' => FALSE, 'error' => 'coupon_Expired'); // '优惠券已过期';
                }
                if ($couponsMemberInfo['surplus_times'] == 0) {
                    return array('success' => FALSE, 'error' => 'coupon_NumberOfTime'); // '优惠券使用次数已用完';
                } else {
                    return array('success' => TRUE, 'couponInfo' => $couponsInfo); //'可以使用';
                }
            } else {
                if ($couponsInfo['private'] == 2) { //公有
                    return array('success' => TRUE, 'couponInfo' => $couponsInfo); // '可以使用';
                } else {
                    return array('success' => FALSE, 'error' => 'coupon_Purview'); // '不可使用';
                }
            }
        } else {
            return array('success' => FALSE, 'error' => 'coupon_Purview'); // '不可使用';
        }
    }

    function getMyCoupons($country, $member_email) {
        //从优惠券主表查找有效的公有优惠券
        $myCoupons = array();
        $publicSql = "SELECT coupons_id,type,amount,frequency,end,`condition`,max,min FROM {$country}_coupons WHERE display=1 and private = 2 AND status = 2 AND start < ? AND end > ?";
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
                'end' => $row['end'],
                'condition' => $row['condition'],
                'min' => $row['min'],
                'max' => $row['max']
            );
        }

        //从会员优惠券表中获取私有有效优惠券
        $privateSql = "SELECT coupons_id,end FROM {$country}_coupons_member WHERE member_email = ? AND private=1 AND (surplus_times >0 OR surplus_times=-1) AND start < ? AND end > ?";
        $privateWhere = array(
            'member_email' => $member_email,
            'start' => time(),
            'end' => time()
        );
        $couponFields = 'private,used,type,amount,condition,min,max,frequency,start,end,create_time,update_time,creator,status';
        $privateQuery = $this->db->query($privateSql, $privateWhere);

        foreach ($privateQuery->result_array() as $row) {
            if (!array_key_exists($row['coupons_id'], $myCoupons)) {

                $couponsInfo = $this->getInfoById($country, $row['coupons_id'], $couponFields);

                if ($couponsInfo['status'] == 2) {
                    $myCoupons[$row['coupons_id']] = array(
                        'type' => $couponsInfo['type'],
                        'amount' => $couponsInfo['amount'],
                        'frequency' => $couponsInfo['frequency'],
                        'end' => $row['end'], //2015.12.9  'end' => $couponsInfo['end']
                        'condition' => $couponsInfo['condition'],
                        'min' => $couponsInfo['min'],
                        'max' => $couponsInfo['max']
                    );
                }
            }
        }
        return $myCoupons;
    }

//    public function couponRemind($country, $member_email) { //优惠卷过期提醒
//        $myCoupons = $this->getMyCoupons($country, $member_email);
//        foreach ($myCoupons as $key => $value) {
//            $reminder = $value['end'] - (259200);
//            if (time() >= $reminder) {
//                return $key;
//            }
//        }
//    }

    public function getBeenUsedCoupons($country, $member_email, $fileds = 'coupons_id') {
        $this->db->select($fileds);
        return $this->db->get_where($country . '_coupons_member', array('member_email' => $member_email, 'surplus_times' => 0))->result_array();
    }

    //每天自动生成优惠券，并发送优惠券
    public function autoGet($countryCode, $member_email) {
        $strCodeList = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
        $strCode = explode('.', chunk_split(date('Ymd'), 1, "."));
        $coupons_id = $strCodeList[$strCode[0]] . $strCodeList[$strCode[1]] . $strCodeList[$strCode[2]] . $strCodeList[$strCode[3]] . $strCodeList[$strCode[4]] . $strCodeList[$strCode[5]] . $strCodeList[$strCode[6]] . $strCodeList[$strCode[7]];
        $couponsInfo = $this->getInfoById($countryCode, $coupons_id);
        if ($couponsInfo) {
            $sql = "select surplus_times from {$countryCode}_coupons_member where member_email='{$member_email}' and coupons_id='{$coupons_id}'  limit 1";
            $query = $this->db->query($sql);
            if ($query->num_rows() > 0) {//不管是私有还是公有，如果有记录则判断次数
                $sql = "update {$countryCode}_coupons_member set surplus_times=surplus_times+1 where member_email='{$member_email}' and coupons_id='{$coupons_id}'";
                $this->db->query($sql);
            } else {
                $intoCouponsMember = array(
                    'member_email' => $member_email,
                    'private' => 1,
                    'coupons_id' => $coupons_id,
                    'surplus_times' => 1,
                    'start' => $couponsInfo['start'],
                    'end' => $couponsInfo['end'],
                );
                $this->db->insert($countryCode . '_coupons_member', $intoCouponsMember);
            }
            $couponsInfo['coupons_id'] = $coupons_id;
            return $couponsInfo;
        } else {
            $couponsInfo = array(
                'coupons_id' => $coupons_id,
                'private' => 1, 'used' => 0, 'type' => 1, 'amount' => 200,
                'condition' => 2, 'min' => 200, 'max' => 10000000,
                'frequency' => 1, 'start' => strtotime(date('Y-m-d')), 'end' => strtotime(date('Y-m-d')) + 2592000,
                'create_time' => time(), 'update_time' => time(), 'creator' => 'system', 'status' => 2
            );
            $intoCouponsMember = array(
                'member_email' => $member_email,
                'private' => 1,
                'coupons_id' => $coupons_id,
                'surplus_times' => 1,
                'start' => $couponsInfo['start'],
                'end' => $couponsInfo['end'],
            );
            $this->db->trans_begin();
            $this->db->insert($countryCode . '_coupons', $couponsInfo);
            $this->db->insert($countryCode . '_coupons_member', $intoCouponsMember);
            if ($this->db->trans_status()) {
                $this->db->trans_commit();
                return $couponsInfo;
            } else {
                $this->db->trans_rollback();
                return false;
            }
        }
    }

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

}
