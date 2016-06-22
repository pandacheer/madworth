<?php
class erp_model extends CI_Model {
	public function __construct() {
		$this->load->database ();
	}
	
	// erp 测试方法 通过订单号 获取订单详情
	public function getOrderDetails($country, $order_number) {
		return $this->db->get_where ( $country . '_order_details', array (
				'order_number' => $order_number 
		) )->result_array ();
	}
	
	// erp 测试方法 通过订单号 获取订单收获地址
	public function getOrderShip($country, $order_number) {
		return $this->db->get_where ( $country . '_order_ship', array (
				'order_number' => $order_number 
		), 1 )->row_array ();
	}
	
	// erp 测试方法 通过订单号 获取订单帐单地址
	public function getOrderBill($country, $order_number) {
		return $this->db->get_where ( $country . '_order_bill', array (
				'order_number' => $order_number 
		), 1 )->row_array ();
	}
	
	// erp 测试方法 通过订单号 获取订单
	public function getOrder($country, $ids, $limit, $page, $since_id, $created_at_min, $created_at_max, $updated_at_min, $updated_at_max, $status, $financial_status, $fulfillment_status) {
		$this->db->order_by ( 'order_id', 'desc' );
		
		if ($ids) {
			$this->db->where ( 'order_number', $ids );
		}
		
		if ($since_id) {
			$this->db->where ( 'order_number >= ', $since_id );
		}
		
		if ($created_at_min) {
			$this->db->where ( 'create_time >= ', $created_at_min );
		}
		
		if ($created_at_max) {
			$this->db->where ( 'create_time <= ', $created_at_max );
		}
		
		if ($updated_at_min) {
			$this->db->where ( 'update_time >= ', $updated_at_min );
		}
		
		if ($updated_at_max) {
			$this->db->where ( 'update_time <= ', $updated_at_max );
		}
		
		if ($status == 'open') {
			$this->db->where ( 'order_status', 1 );
		} else if ($status == 'closed') {
			$this->db->where ( 'order_status', 2 );
		}
		
		if ($financial_status == 'paid') {
			$this->db->where ( 'pay_status', 1 );
		} else if ($financial_status == 'refunded') {
			$this->db->where ( 'pay_status', 2 );
		} else if ($financial_status == 'partially_refunded') {
			$this->db->where ( 'pay_status', 3 );
		}
		
		if ($fulfillment_status == 'shipped') {
			$this->db->where ( 'send_status', 1 );
		} else if ($fulfillment_status == 'partial') {
			$this->db->where ( 'send_status', 2 );
		} else if ($fulfillment_status == 'unshipped') {
			$this->db->where ( 'send_status', 0 );
		}
		
		$this->db->limit ( $limit, $page );
		return $this->db->get ( $country . '_order' )->result_array ();
	}
	
	// erp 测试方法 通过订单号 获取订单附加信息
	public function getOrderAppend($country, $order_number) {
		return $this->db->get_where ( $country . '_order_append', array (
				'order_number' => $order_number 
		), 1 )->row_array ();
	}
	
	// erp 测试方法 获取用户信息
	public function getMemberInfo($country, $member_email) {
		$member = $this->db->get_where ( $country . '_member', array (
				'member_email' => $member_email 
		), 1 )->row_array ();
		
		return $member;
	}
	
	// erp 测试方法 获取发货信息
	public function getOrderSend($country, $order_number) {
		$orderSendInfo = $this->db->get_where ( $country . '_order_send', array (
				'order_number' => $order_number 
		), 1 )->row_array ();
		return $orderSendInfo;
	}
}

?>

