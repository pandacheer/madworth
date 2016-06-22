<?php

class maildata_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	}
	
	
	//获取订单信息
	function getInfoByNumber($country, $order_number, $fields = 'member_email,member_name') {
		$this->db->select($fields);
		$this->db->where('order_number', $order_number);
		$this->db->limit(1);
		return $this->db->get($country . '_order')->row_array();
	}
	
	
	//获取收获地址
	function getOrderShip($country,$order_number,$fields = 'receive_firstName,receive_lastName,receive_country,receive_province,receive_city,receive_add1,receive_add2,receive_zipcode,receive_phone,express_type'){
		$this->db->select($fields);
		return $this->db->get_where($country.'_order_ship', array('order_number' => $order_number),1)->row_array();
	}
	
	//获取订单详情
    function getOrderDetails($country,$order_number,$product_sku,$fields = 'product_name,product_quantity') {
		$this->db->select($fields);
		
		if($product_sku){
			$Arr = explode(',', $product_sku);
			$skus=array_filter($Arr);
		}
		
		$this->db->where_in('bundle_skus', $skus);
		$this->db->where('order_number', $order_number);
		return $this->db->get($country . '_order_details')->result_array();
	}
	
	
	
	// 通过退款id号 获取退款单详情
	function getInfoById($country, $refund_id, $fields = 'order_number,refund_amount') {
		$this->db->select($fields);
		$this->db->where('refund_id', $refund_id);
		$this->db->limit(1);
		return $this->db->get($country . '_refund_bills')->row_array();
	}
	
	
	
	// 获取退款详情信息
	function getRefund_detailsById($country, $refund_id, $fields = 'refund_quantity,refund_amount,product_name') {
		$this->db->select($fields);
		$this->db->order_by('refund_id', 'desc');
		$this->db->where('refund_id', $refund_id);
		return $this->db->get($country . '_refund_details')->result_array();
	}
	
	

}




?>