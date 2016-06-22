<?php

/**
 *  orderTracking_model
 *  zhujian
 *  订单投诉列表模型
 */
class ordertracking_model extends CI_Model {
    public function __construct() {
        $this->load->database();
    }



    //获取投诉列表信息
    public function getComplaints($country,$offset = 0, $per_page = 10){
        $this->db->order_by('complaints_id', 'desc');
    	  $this->db->limit($per_page, $offset);
        $this->db->select('complaints_id,order_number,member_name,send_bill,send_time,track_code,operator');
        return $this->db->get($country.'_order_complaints')->result_array();
    }



    //获取投诉总数量
    public function complaintsrCount($country){
      return $this->db->count_all_results($country . '_order_complaints');
    }
    
    
    //修改投诉信息
    public function updateComplaints($country,$data,$complaints_id){
    	return $this->db->update($country.'_order_complaints', $data, array('complaints_id' => $complaints_id));
    }
    
    
    
    //获取投诉列表信息
    public function getComplaintsDetails($country,$complaints_id){
    	$this->db->where('complaints_id',$complaints_id);
    	$this->db->limit(1);
    	return $this->db->get($country.'_order_complaints')->row_array();
    }



    //获取导表所需要的数据
    public function getExcel($country,$timeStart,$timeEnd){
      $this->db->select('create_time,member_name,order_number,send_bill,send_time,products,question_type,question_remark,logistics,track_code,department,dispose,refund_amount,refund_remark,coupon,operator');
      $array = array('create_time >=' => $timeStart, 'create_time <=' => $timeEnd);
      $this->db->where($array); 
      return $this->db->get($country.'_order_complaints')->result_array();
    }


}