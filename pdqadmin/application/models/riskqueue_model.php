<?php

class riskqueue_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    //获取队列信息
    public function getInfo($id) {
        $this->db->where('id', $id);
        return $this->db->get('SYS_queue')->row_array();
    }

    //添加风险评估   成功后删除队列信息
    function addRisk($country_code, $data,$order_risk) {
        $this->db->trans_begin();
        $this->db->insert($country_code . '_order_risk', $data);
        $this->db->update($country_code . '_order', array('order_risk' => $order_risk), array('order_number' => $data['order_number']));
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    //添加失败后修改处理次数
    function updateRiskQueue($order_number) {
        $sql = 'update order_RiskQueue set status+1 where order_number=' . $order_number . '';
        return $this->db->query($sql);
    }
    
    
    function updates($order_number) {
    	$data = array('status' => 6);
    	$this->db->where('order_number', $order_number);
    	$this->db->update('order_RiskQueue', $data);
    }
    
    
    
    //添加日志信息
    function addLog($data){
    	$table_name='SYS_log_'.date("Ym");
    	$logMongo = $this->mongo->selectCollection($table_name);
    	$result = $logMongo->insert($data);
    	return $result['ok'];
    }
    
    
    

}

?>