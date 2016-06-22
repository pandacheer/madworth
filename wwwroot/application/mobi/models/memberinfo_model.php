<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Memberinfo_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    //member_id,member_gender,member_birthday,member_phone
    //根据member_id，查询数据
    public function getInfo($country_code, $member_id, $fileds = 'member_id,member_gender,member_birthday,member_phone') {
        $this->db->select($fileds);
        return $this->db->get_where($country_code . '_member_info', array('member_id' => $member_id), 1)->row_array();
    }

    function update($country_code, $member_id, $member, $memberInfo) {
        $myInfo = $this->getInfo($country_code, $member_id);
        if ($myInfo['member_birthday'] > 0) {
            unset($memberInfo['member_birthday']);
        }
        $this->db->trans_begin();
        $this->db->update($country_code . '_member', $member, array('member_id' => $member_id));
        $this->db->update($country_code . '_member_info', $memberInfo, array('member_id' => $member_id));
        $this->db->update($country_code . '_member_analysis', array('member_name' => $member['member_name']), array('member_id' => $member_id));

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

}
