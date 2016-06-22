<?php

/**
 *  order_model
 *  zhujian
 *  退款订单模型
 */
class orderrefundlist_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    //获取退款订单列表
    public function getRefund_bills($country, $offset = 0, $per_page = 10) {
        $this->db->order_by('refund_id', 'desc');
        $this->db->limit($per_page, $offset);
        $this->db->select('refund_id,order_number,refund_status,proposer,create_time');
        return $this->db->get($country . '_refund_bills')->result_array();
    }

    //获取投诉总数量
    public function refundCount($country) {
        return $this->db->count_all_results($country . '_refund_bills');
    }

    //获取退款单详情
    function getInfo($country_code, $refund_id, $fields = 'refund_id,order_number,order_transaction_id,pay_type,refund_reason,refund_quantity,refund_amount,transaction_id,refund_status,proposer,operator,update_time,create_time') {
        $this->db->select($fields);
        $this->db->where('refund_id', $refund_id);
        $this->db->limit(1);
        return $this->db->get($country_code . '_return_bills')->row_array();
    }

}

?>