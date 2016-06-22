<?php

/**
 * @文件： coupons_member_model
 * @时间： 2015-6-11 13:27:27
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：会员优惠券记录
 */
class CouponsMember_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    //添加会员优惠券
    //$data = array('member_id1', 'member_id2');
    function appendUser($country_code, $coupons_id, $member_emailArr, $couponInfo) {
        $insertData = array();
        foreach ($member_emailArr as $member_email) {
            $intoCouponsMember = array(
                'member_email' => $member_email,
                'private' => 1,
                'coupons_id' => $coupons_id,
                'surplus_times' => $couponInfo['frequency'] == 0 ? -1 : $couponInfo['frequency'],
                'start' => $couponInfo['start'],
                'end' => $couponInfo['end'],
            );
            $insertData[] = $intoCouponsMember;
        }
        $this->db->trans_begin();
        $this->db->insert_batch($country_code . '_coupons_member', $insertData);
        if ($this->db->trans_status()) {
            $this->db->trans_commit();
            return 1;
        } else {
            $this->db->trans_rollback();
            return 0;
        }
    }

    //返回某个Coupon的所有member_email集合
    function getMembersByCouponsID($country_code, $coupons_id) {
        $this->db->select('member_email');
        $this->db->from($country_code . '_coupons_member');
        $this->db->where('coupons_id', $coupons_id);
        $query = $this->db->get();
        $result = [];
        foreach ($query->result_array() as $row) {
            $result[] = $row['member_email'];
        }
        return $result;
    }

}
