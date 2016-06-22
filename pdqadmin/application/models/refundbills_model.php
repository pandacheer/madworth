<?php

/**
 *  order_model
 *  zhujian
 *  退款订单模型
 */
class refundBills_model extends CI_Model {

    public function __construct() {
       parent::__construct();
    }

    // 获取退款详情信息
    public function getRefund_detailsById($country, $refund_id, $fields = 'refund_id,order_number,refund_price,refund_quantity,refund_amount,product_id,product_name,product_sku,product_attr') {
        $this->db->select($fields);
        $this->db->order_by('refund_id', 'desc');
        $this->db->where('refund_id', $refund_id);
        return $this->db->get($country . '_refund_details')->result_array();
    }

    // 获取退款订单列表
    public function getRefund_bills($country, $whereData, $offset = 0, $per_page = 10) {
        $this->db->order_by('refund_id', 'desc');
        $this->db->where($whereData);
        $this->db->limit($per_page, $offset);
        $this->db->select('refund_id,order_number,refund_status,proposer_name,proposer_id,create_time');
        return $this->db->get($country . '_refund_bills')->result_array();
    }

    // 获取退款总数量
    public function refundCount($country, $whereData) {
        $this->db->where($whereData);
        return $this->db->count_all_results($country . '_refund_bills');
    }

    // 通过订单号 获取退款信息 getInfoByNumber
    public function getRefundByNumber($country, $order_number, $fields = 'refund_id,order_number,order_transaction_id,pay_type,refund_details,refund_reason,refund_resolution,refund_quantity,refund_amount,transaction_id,refund_status,proposer_name,proposer_id,operator,update_time,create_time') {
        $this->db->select($fields);
        $this->db->order_by('refund_id', 'desc');
        $this->db->where(array(
            'order_number' => $order_number,
            'refund_status !=' => 3
        ));

        return $this->db->get($country . '_refund_bills')->result_array();
    }

    // 通过退款id号 获取退款单详情
    function getInfoById($country, $refund_id, $fields = 'refund_id,order_number,order_transaction_id,pay_type,refund_details,refund_reason,refund_resolution,refund_quantity,refund_amount,transaction_id,refund_status,proposer_name,proposer_id,operator,update_time,create_time') {
        $this->db->select($fields);
        $this->db->where('refund_id', $refund_id);
        $this->db->limit(1);
        return $this->db->get($country . '_refund_bills')->row_array();
    }

    // 通过订单号 判断是否有未处理的退款单 getInfoByNumber
    public function getUntreated($country, $order_number, $fields = 'refund_id,order_number,order_transaction_id,pay_type,refund_details,refund_reason,refund_resolution,refund_quantity,refund_amount,transaction_id,refund_status,proposer_name,proposer_id,operator,update_time,create_time') {
        $this->db->select($fields);
        $this->db->where(array(
            'order_number' => $order_number,
            'refund_status' => 1
        ));

        return $this->db->get($country . '_refund_bills')->result_array();
    }
    
    
    
    //判断是否已经存此订单的未处理数据
    function getRefundStatus($country,$order_number){
    	$applyMongo = $this->mongo->{$country . '_refundApply'};
    	$result = $applyMongo->find(array("order_number"=>$order_number,'status'=>1), array("_id" => 1));
    	 
    	return iterator_to_array($result);
    }
    
    

    // 添加退款信息
    public function add_refund($country, $refund_bills, $refund_details, $order_log) {
        $this->db->trans_begin();
        $this->db->insert($country . '_refund_bills', $refund_bills);
        $this->db->insert_batch($country . '_refund_details', $refund_details);
        $this->db->insert($country . '_order_log', $order_log);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    // 单独修改退款表的状态
    public function up_refund($country, $refund_id, $refund_status, $operator, $transaction_id) {
        $data = array(
            'transaction_id' => $transaction_id,
            'refund_status' => $refund_status,
            'operator' => $operator,
            'update_time' => time()
        );

        $this->db->update($country . '_refund_bills', $data, array('refund_id' => $refund_id));
        return $this->db->affected_rows();
    }

    // 修改订单表的退款状态并且修改退款表的状态
    public function update_refundState($country, $order_number, $refund_id, $pay_status, $refund_status, $operator, $transaction_id) {
        $order_data = array(
            'pay_status' => $pay_status,
            'operator' => $operator,
            'update_time' => time()
        );

        $refund_data = array(
            'transaction_id' => $transaction_id,
            'refund_status' => $refund_status,
            'operator' => $operator,
            'update_time' => time()
        );

        $this->db->trans_begin();
        $this->db->update($country . '_order', $order_data, array('order_number' => $order_number));
        $this->db->update($country . '_refund_bills', $refund_data, array('refund_id' => $refund_id));


        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    // 获取退款金额
    public function refund_sum($country, $order_number) {
        $this->db->where(array(
            'order_number' => $order_number,
            'refund_status !=' => 3
        ));
        $this->db->select_sum('refund_amount');
        return $this->db->get($country . '_refund_bills')->row_array();
    }

    // 删除退款
    public function delete_refund($country, $refund_id) {
        $tables = array(
            $country . '_refund_bills',
            $country . '_refund_details'
        );
        $this->db->where('refund_id', $refund_id);
        $this->db->delete($tables);
        return $this->db->affected_rows();
    }

}

?>