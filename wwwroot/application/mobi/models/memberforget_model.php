<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Memberforget_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    //$forget_status=1 无需认证，=0 需要认证
    public function insert($country_code, $forget_email, $forget_salt, $forget_time, $invalid_time, $forget_type, $forget_status = 1) {
        $insertData = array(
            'country_code' => $country_code,
            'forget_email' => $forget_email,
            'forget_salt' => $forget_salt,
            'forget_time' => $forget_time,
            'invalid_time' => $invalid_time,
            'forget_type' => $forget_type,
            'forget_status' => $forget_status
        );

        if ($this->db->insert('member_forget', $insertData)) {
            return $this->db->insert_id();
        } else {
            return 0;
        }
    }

    function getLinkData($forget_id) {
        $sql = 'select country_code,forget_email,forget_salt,forget_time,invalid_time,forget_type,forget_status from member_forget where forget_id = ' . $forget_id . ' limit 1';
        return $this->db->query($sql)->row_array();
    }

    public function changePwd($country_code, $check_id, $member_email, $member_pwd) {
        $updatePwd['member_salt'] = createSalt();
        $updatePwd['member_pwd'] = encryption($member_pwd, $updatePwd['member_salt']);

        $this->db->trans_begin();
        $updateMemberSQL = "update {$country_code}_member set member_salt = ? , member_pwd = ?  where member_email = '{$member_email}'";
        $this->db->query($updateMemberSQL, $updatePwd);
        $deleteForgetSQL = 'delete from member_forget where forget_id=' . $check_id;
        $this->db->query($deleteForgetSQL);

        if ($this->db->trans_status()) {
            $this->db->trans_commit();
            return TRUE;
        } else {
            $this->db->trans_rollback();
            return FALSE;
        }
    }

    /*
     * 验证邮箱
     * $forget_type <9 注册验证
     *              =9 订阅邮件验证
     */

    function verifyMail($country_code, $forget_id, $member_email, $delete = 0, $forget_type = 8) {

        if ($forget_type < 9) {//注册验证
            $this->db->trans_begin();
            $updateMemberSQL = "update {$country_code}_member set status = 8 where member_email = '{$member_email}'";
            $this->db->query($updateMemberSQL);
            if ($delete) {//
//                $updateForgetSQL = 'delete from member_forget where forget_id=' . $forget_id;
                $updateForgetSQL = 'update member_forget set forget_status=10 where forget_id=' . $forget_id;
            } else {
                $updateForgetSQL = 'update member_forget set forget_status=1 where forget_id=' . $forget_id;
            }
            $this->db->query($updateForgetSQL);

            if ($this->db->trans_status()) {
                $this->db->trans_commit();
                $dateTimePRC = new DateTime('@' . (time() + 28800), new DateTimeZone("PRC"));
                $redisKey = 'T:' . $dateTimePRC->format("Ymd") . ':' . $country_code . ':member';
                $this->redis->hashInc($redisKey, 'verify', 1);
                $this->redis->timeOut($redisKey, 259200);
                return TRUE;
            } else {
                $this->db->trans_rollback();
                return FALSE;
            }
        } else {
            $subscription = $this->mongo->selectCollection($country_code . '_subscription');
            $subscription->update(array('_id' => $member_email), array('$set' => array('status' => 1)));
            $updateForgetSQL = 'delete from member_forget where forget_id=' . $forget_id;
            return $this->db->query($updateForgetSQL);
        }
    }

    function authorizationThird($country_code, $forget_id, $member_email, $delete = 0, $from = 'fb') {
        $this->db->trans_begin();
        if (empty($member_email)) {
            return false;
        }
        $array = explode('-', $member_email);
        if (count($array) != 2) {
            return false;
        }
        $member_id = $array[0];
        $third_id = $array[1];
        if (!$member_id || !$third_id) {
            return false;
        }
        $a = [$member_id, $array[2], $array[3], $array[4], $array[5]];
        if ($from == 'fb') {
            $field = 'fb_id';
        } else {
            $field = 'fb_id';
        }
        $this->db->where(array('member_id' => $member_id));
        $this->db->from($country_code . '_member_thirdbind');
        $c = $this->db->count_all_results();
        if ($c > 0) {
            $updateSQL = 'update ' . $country_code . '_member_thirdbind set ' . $field . '=\'' . $third_id . '\' where member_id=' . $member_id;
        } else {
            $insertSQL = 'insert into ' . $country_code . '_member_thirdbind(member_id,' . $field . ') values(' . $member_id . ',\'' . $third_id . '\')';
        }
        $this->db->query($insertSQL);
        if ($delete) {
            $updateForgetSQL = 'update member_forget set forget_status=10 where forget_id=' . $forget_id;
        } else {
            $updateForgetSQL = 'update member_forget set forget_status=1 where forget_id=' . $forget_id;
        }
        $this->db->query($updateForgetSQL);
        if ($this->db->trans_status()) {
            $this->db->trans_commit();
            return $a;
        } else {
            $this->db->trans_rollback();
            return FALSE;
        }
    }

}
