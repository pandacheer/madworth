<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Member_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function insert($country_code, $member_email, $member_pwd, $quickReg = FALSE) {
        if ($quickReg) {
            $quickMemberId = $this->checkEmail($country_code, $member_email);
            if ($quickMemberId) {
                unset($quickMemberId['status']);
                return $quickMemberId;
            } else {
                $member_pwd = uniqid();
            }
        }
        $this->load->helper('encryption');
        $salt = createSalt();
        $time = time();
        $insertData = array(
            'member_id' => $this->CreateMemberId(),
            'member_name' => $member_email,
            'member_email' => $member_email,
            'member_pwd' => encryption($member_pwd, $salt),
            'member_salt' => $salt,
            'create_time' => $time,
            'login_inc' => 0,
            'login_time' => $time,
            'status' => 1
        );
        $this->load->model('coupons_model');
        $couponInfo = $this->coupons_model->getInfoById($country_code, 'NEWGRABBER');
        $this->db->trans_begin();
        if ($couponInfo && $couponInfo['status'] == 2) {
            $intoCouponsMember = array(
                'member_email' => $member_email,
                'private' => 1,
                'coupons_id' => 'NEWGRABBER',
                'surplus_times' => $couponInfo['frequency'] == 0 ? -1 : $couponInfo['frequency'],
                'start' => $couponInfo['start'],
                'end' => $couponInfo['end'],
            );
            $this->db->insert($country_code . '_coupons_member', $intoCouponsMember);
        }
        $this->db->insert($country_code . '_member', $insertData);
        $this->db->insert($country_code . '_member_info', ['member_id' => $insertData['member_id'], 'member_gender' => 3, 'member_birthday' => 0, 'member_phone' => '']);
        $this->db->insert($country_code . '_member_analysis', ['member_id' => $insertData['member_id'], 'member_name' => $insertData['member_email'], 'member_email' => $insertData['member_email'], 'member_orders' => 0, 'last_order' => 0, 'order_spent' => 0]);
        if ($this->db->trans_status()) {
            $this->db->trans_commit();
            $dateTimePRC = new DateTime('@' . (time() + 28800), new DateTimeZone("PRC"));
            $redisKey = 'T:' . $dateTimePRC->format("Ymd") . ':' . $country_code . ':member';
            $quickReg ? $this->redis->hashInc($redisKey, 'autoReg', 1) : $this->redis->hashInc($redisKey, 'reg', 1); //统计当天注册会员数
            $this->redis->timeOut($redisKey, 259200);
            return array(
                'member_id' => $insertData['member_id'],
                'member_name' => $insertData['member_name'],
                'member_pwd' => $member_pwd
            );
        } else {
            $this->db->trans_rollback();
            return 0;
        }
    }

    private function CreateMemberId() {
        $MemberIdKey = 'SYSMemberId' . date('ymdHi');
        $incr = $this->redis->deinc($MemberIdKey);
        $this->redis->timeOut($MemberIdKey, 100);
        $MemberId = date('y') . str_pad(date('W'), 2, '0', STR_PAD_LEFT) . date('N') . str_pad(time() - strtotime(date('Y-m-d')), 5, '0', STR_PAD_LEFT) . str_pad($incr, 5, '0', STR_PAD_LEFT);
        return $MemberId;
    }

    public function getInfo($country_code, $member_id, $fileds = 'member_firstName') {
        $this->db->select($fileds);
        return $this->db->get_where($country_code . '_member', array('member_id' => $member_id), 1)->row_array();
    }

    public function getInfoByEmail($country_code, $member_email) {
        $this->db->select('member_id,member_name');
        return $this->db->get_where($country_code . '_member', array('member_email' => $member_email), 1)->row_array();
    }

    //
    function login($country_code, $member_email, $member_pwd) {
        $sql = 'select member_id,member_name,member_pwd,member_salt,status from ' . $country_code . '_member where member_email=\'' . $member_email . '\' limit 1';
        $info = $this->db->query($sql)->row_array();
        if ($info) {
            if ($info['status'] == 2) {//login_shopify_error
                return ['member_id' => $info['member_id'], 'member_name' => $info['member_name']];
            }
            $this->load->helper('encryption');
            if ($info['member_pwd'] !== encryption($member_pwd, $info['member_salt'])) {
                $result = 'login_password_error';
            } else {
                $sql = 'update ' . $country_code . '_member set login_inc=login_inc+1,login_time=' . time() . ' where member_id=' . $info['member_id'];
                $result = $this->db->query($sql) ? ['member_id' => $info['member_id'], 'member_name' => $info['member_name'], 'member_email' => $member_email] : 'login_fail_error';
            }
        } else {
            $result = 'login_email_error';
        }
        return $result;
    }

    function autoLogin($country_code, $member_email) {
        $sql = 'select member_id,member_name,member_firstName,member_lastName from ' . $country_code . '_member where member_email=\'' . $member_email . '\' limit 1';
        $info = $this->db->query($sql)->row_array();
        if ($info) {
            $sql = 'update ' . $country_code . '_member set login_inc=login_inc+1,login_time=' . time() . ' where member_id=' . $info['member_id'];
            $sqls = 'select member_gender,member_birthday from ' . $country_code . '_member_info where member_id=' . $info['member_id'] . ' limit 1';
            $infos = $this->db->query($sqls)->row_array();
            if ($infos) {
                $info['member_gender'] = $infos['member_gender'];
                $info['member_birthday'] = $infos['member_birthday'];
            } else {
                $info['member_gender'] = '';
                $info['member_birthday'] = '';
            }
            $result = $this->db->query($sql) ? ['member_id' => $info['member_id'], 'member_name' => $info['member_name'], 'member_email' => $member_email, 'member_gender' => $info['member_gender'], 'member_birthday' => $info['member_birthday'], 'member_firstName' => $info['member_firstName'], 'member_lastName' => $info['member_lastName']] : 'login_fail_error';
        } else {
            $result = 'login_email_error';
        }
        return $result;
    }

    //密码修改权限
    function checkAuth($country_code, $member_id, $member_pwd) {
        $sql = 'select member_id,member_name,member_pwd,member_salt from ' . $country_code . '_member where member_id=' . $member_id . ' limit 1';
        $info = $this->db->query($sql)->row_array();
        if ($info) {
            $this->load->helper('encryption');
            if ($info['member_pwd'] !== encryption($member_pwd, $info['member_salt'])) {
                $result = false;
            } else {
                $result = true;
            }
        } else {
            $result = FALSE;
        }
        return $result;
    }

    //检测帐号是否存在
    function checkEmail($country_code, $member_email) {
        $query = $this->db->query('SELECT member_id,member_name,status FROM ' . $country_code . '_member where member_email=\'' . $member_email . '\' limit 1');
        if ($query->num_rows() > 0) {
            $row = $query->row_array();

            //账号存在删除购物车里面的所有信息
            $CI = & get_instance();
            $cart = $CI->mongo->selectCollection($country_code . '_cart');
            $where = array('_id' => $member_email);
            if ($cart->remove($where)) {
                return $row;
            }
        } else {
            return 0;
        }
    }

    //主要用来更新登录信息
    function update($country_code, $whereData, $updateData) {
        $this->db->where($whereData);
        return $this->db->update($country_code . '_member', $updateData);
    }

    //更新个人信息
    // $country_code ：国家代码,
    // $member_id ： 会员ID
    // $change ： 2－只更新密码 3－更新密码及信息
    // $postMemberPwd ： 新的密码
    // $postMemberInfo ：新的信息
    function updatePersonal($country_code, $member_id, $change, $postMemberPwd, $postMemberInfo = NULL, $member = NULL) {
        $this->load->helper('encryption');
        $salt = createSalt();
        $updatePwd = array(
            'member_pwd' => encryption($postMemberPwd, $salt),
            'member_salt' => $salt
        );
        if ($change == 2) {//只更新密码
            $this->db->where('member_id', $member_id);
            return $this->db->update($country_code . '_member', $updatePwd);
        } else {//更新密码及信息
            $this->db->trans_begin();
            $memberData = array(
                'member_firstName' => $member['member_firstName'],
                'member_lastName' => $member['member_lastName'],
                'member_name' => $member['member_name'],
                'member_pwd' => encryption($postMemberPwd, $salt),
                'member_salt' => $salt
            );

            $this->db->update($country_code . '_member', $memberData, array('member_id' => $member_id));
            $this->db->update($country_code . '_member_info', $postMemberInfo, array('member_id' => $member_id));
            $this->db->update($country_code . '_member_analysis', array('member_name' => $member['member_name']), array('member_id' => $member_id));

            if ($this->db->trans_status()) {
                $this->db->trans_commit();
                return TRUE;
            } else {
                $this->db->trans_rollback();
                return FALSE;
            }
        }
    }
    
    function updatePersonalviathird($country_code, $member_id, $postMemberInfo, $memberData) {
        if (empty($postMemberInfo) && empty($memberData)) {
            return true;
        }
        $this->db->trans_begin();
        if (!empty($memberData)) {
            $this->db->update($country_code . '_member', $memberData, array('member_id' => $member_id));
            $this->db->update($country_code . '_member_analysis', array('member_name' => $memberData['member_name']), array('member_id' => $member_id));
        }
        if (!empty($postMemberInfo)) {
            $this->db->update($country_code . '_member_info', $postMemberInfo, array('member_id' => $member_id));
        }
        if ($this->db->trans_status()) {
            $this->db->trans_commit();
            return TRUE;
        } else {
            $this->db->trans_rollback();
            return FALSE;
        }
    }

    function thirdlogin($country_code, $id, $third = 'fb') {
        $where = '';
        if ($third == 'fb') {
            $where = " where fb_id='{$id}'";
        }
        $this->db->trans_begin();
        $sql = 'select member_id from ' . $country_code . '_member_thirdbind ' . $where . ' limit 1';
        $info = $this->db->query($sql)->row_array();
        if ($info) {
            $sql = 'select member_id,member_name,member_email,member_firstName,member_lastName from ' . $country_code . '_member where member_id=' . $info['member_id'] . ' limit 1';
            $info = $this->db->query($sql)->row_array();
            if ($info) {
                $sql = 'update ' . $country_code . '_member set login_inc=login_inc+1,login_time=' . time() . ' where member_id=' . $info['member_id'];
                $sqls = 'select member_gender,member_birthday from ' . $country_code . '_member_info where member_id=' . $info['member_id'] . ' limit 1';
                $infos = $this->db->query($sqls)->row_array();
                if ($infos) {
                    $info['member_gender'] = $infos['member_gender'];
                    $info['member_birthday'] = $infos['member_birthday'];
                } else {
                    $info['member_gender'] = '';
                    $info['member_birthday'] = '';
                }
                $result = $this->db->query($sql) ? ['member_id' => $info['member_id'], 'member_name' => $info['member_name'], 'member_email' => $info['member_email'], 'member_gender' => $info['member_gender'], 'member_birthday' => $info['member_birthday'], 'member_firstName' => $info['member_firstName'], 'member_lastName' => $info['member_lastName']] : 'login_fail_error';
            } else {
                $result = 'login_email_error';
            }
            $this->db->trans_commit();
            return $result;
        } else {
            $this->db->trans_rollback();
            return false;
        }
    }

}
