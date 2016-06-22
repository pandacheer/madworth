<?php

/**
 *  order_model
 *  zhujian
 *  订单模型
 */
class order_model extends CI_Model {
	public function __construct() {
		$this->load->database ();
	}
	
	// 获取今天的销售数据
	function currentSalesData($country) {
		$time = strtotime ( date ( 'Y-m-d' ) );
		
		$where = "create_time >= {$time} AND pay_status>0";
		$this->db->where ( $where );
		$this->db->select_sum ( 'payment_amount' );
		$query = $this->db->get ( $country . '_order' );
		$currentSalesData = $query->row_array ();
		return $currentSalesData ['payment_amount']?$currentSalesData ['payment_amount']:0;
	}
	
	// 获取昨天的销售数据
	function yesterdaySalesData($country) {
		$startTime = strtotime ( date ( 'Y-m-d', strtotime ( '-1 day' ) ) );
		//$time = date ( 'Y-m-d', strtotime ( date ( 'Y-m-d', strtotime ( '-1 day' ) ) ) ) . ' 23:59:59';
		$endTime = $startTime+3600*24-1;
		
		$where = "create_time >= {$startTime} AND create_time <= {$endTime} AND pay_status>0";
		$this->db->where ( $where );
		$this->db->select_sum ( 'payment_amount' );
		$query = $this->db->get ( $country . '_order' );
		$yesterdaySalesData = $query->row_array ();
		return $yesterdaySalesData ['payment_amount']?$yesterdaySalesData ['payment_amount']:0;
	}
	
	// 获取本月销售数据
	function currentMonthSalesData($country) {
		$BeginDate = date ( 'Y-m-01', strtotime ( date ( "Y-m-d" ) ) );
		$startTime = strtotime ( $BeginDate );
		$endDate = date ( 'Y-m-d', strtotime ( "$BeginDate +1 month -1 day" ) ) . ' 23:59:59';
		$endTime = strtotime ( $endDate );
		
		$where = "create_time >= {$startTime} AND create_time <= {$endTime} AND pay_status>0";
		$this->db->where ( $where );
		$this->db->select_sum ( 'payment_amount' );
		$query = $this->db->get ( $country . '_order' );
		$currentMonthSalesData = $query->row_array ();
		return $currentMonthSalesData ['payment_amount']?$currentMonthSalesData ['payment_amount']:0;
	}
	
	// 获取上个月的销售数据
	function lastMonthSalesData($country) {
		$BeginDate = date ( 'Y-m-01', strtotime ( date ( "Y-m-d" ) ) );
		$startTime = strtotime ( date ( 'Y-m-d', strtotime ( "$BeginDate -1 month" ) ) );
		$endDate = date ( 'Y-m-d', strtotime ( "$BeginDate +1 month -1 day" ) );
		$endTime = strtotime ( date ( 'Y-m-d', strtotime ( "$endDate -1 month " ) ) . ' 23:59:59' );
		
		$where = "create_time >= {$startTime} AND create_time <= {$endTime} AND pay_status>0";
		$this->db->where ( $where );
		$this->db->select_sum ( 'payment_amount' );
		$query = $this->db->get ( $country . '_order' );
		$lastMonthSalesData = $query->row_array ();
		return $lastMonthSalesData ['payment_amount']?$lastMonthSalesData ['payment_amount']:0;
	}
	
	// 订单状态修改(发货状态,付款状态(修改为已付款不支持),归档状态)(后台)
	public function update_status($country, $data) {
		$this->db->update_batch ( $country . '_order', $data, 'order_number' );
		return $this->db->affected_rows ();
	}
	
	// 订单状态修改(付款状态修改为已付款)(后台)
	public function pay_suceess($country, $order_number) {
		$this->db->update ( $country . '_order', array (
				'pay_status' => 1 
		), array (
				'order_number' => $order_number 
		) );
		return $this->db->affected_rows ();
	}
	
	// 修改订单进度表(后台)
	public function update_orderTime($country, $data) {
		$this->db->update_batch ( $country . '_order_time', $data, 'order_number' );
		return $this->db->affected_rows ();
	}
	
	// 获取订单信息(后台)
	/*
	 * $sort [asc,desc,用户排序][order_id+desc]
	 */
	public function getOrder($country, $whereData, $offset = 0, $per_page = 10, $fields = 'doc_status,order_number,member_name,create_time,pay_status,send_status,payment_amount,order_risk') {
		//if(!count($whereData)){
			$this->db->where ( 'pay_status >', 0 );
		//}
		
		$this->db->order_by ( 'order_id', 'desc' );
		$this->db->where ( $whereData );
		
		if ($per_page) {
			$this->db->limit ( $per_page, $offset );
		}
		$this->db->select ( $fields );
		
		return $this->db->get ( $country . '_order' )->result_array ();
	}
	
	// 获取订单总数量
	public function orderCount($country, $whereData) {
		$this->db->where ( 'pay_status >', 0 );
		$this->db->where ( $whereData );
		
		return $this->db->count_all_results ( $country . '_order' );
	}
	
	// 获取收获地址对应的id
	public function getShipOrderNum($country, $whereData) {
		$this->db->like ( 'receive_add1', $whereData );
		$fields = 'order_number';
		$this->db->select ( $fields );
		return $this->db->get ( $country . '_order_ship' )->result_array ();
	}
	
	// 获取条件的数量
	public function getShipOrderCount($country, $whereData) {
		$this->db->where_in ( 'order_number', $whereData );
		return $this->db->count_all_results ( $country . '_order' );
	}
	
	// 获取条件的信息
	public function getShipOrder($country, $whereData, $offset = 0, $per_page = 10) {
		$this->db->order_by ( 'order_id', 'desc' );
		$this->db->where_in ( 'order_number', $whereData );
		$this->db->limit ( $per_page, $offset );
		$fields = 'doc_status,order_number,member_name,create_time,pay_status,send_status,payment_amount';
		$this->db->select ( $fields );
		return $this->db->get ( $country . '_order' )->result_array ();
	}
	
	/*
	 * //通过ID获取订单信息(后台)
	 * public function findOrderId($arr){
	 * foreach ($arr as $value) {
	 * $this->db->or_where('order_id',$value);
	 * }
	 * $this->db->order_by("order_id", "asc");
	 * $this->db->select('member_id,payment_amount,create_time');
	 * $query = $this->db->get('order');
	 * return $query->result_array();
	 * }
	 */
	
	// 修改分析表里的数据(后台)
	public function order_analysis($data) {
		/*
		 * //获取对应的数据
		 * foreach ($arr as $key => $value) {
		 * $this->db->select('member_orders,order_spent');
		 * $result=$this->db->get_where('member_analysis',array('member_id' => $value['member_id']),1)->result_array();
		 * echo $result[0]['order_spent'];exit();
		 * $member_orders=$result[0]['member_orders']+1;
		 * $order_spent=$result[0]['order_spent']+$value['payment_amount'];
		 * $data=array('member_orders'=>$member_orders,'last_order'=>$value['create_time'],'order_spent'=>$order_spent);
		 * $this->db->update('member_analysis', $data, array('member_id' => $value['member_id']));
		 * }
		 */
		foreach ( $data as $value ) {
			$sql = 'update member_analysis set order_spent=order_spent+' . $value ['order_spent'] . ',member_orders=member_orders+1,last_order=' . $value ['last_order'] . '  where member_id=' . $value ['member_id'] . '';
			$this->db->query ( $sql );
		}
	}
	function getListByMemberId($country_code, $whereData, $sort = 'create_time', $order = 'desc', $offset = 0, $per_page = 10, $fields = '*') {
		$this->db->select ( $fields );
		$this->db->from ( $country_code . '_order' );
		$this->db->where ( $whereData );
		$this->db->limit ( $per_page, $offset );
		$this->db->order_by ( $sort, $order );
		$query = $this->db->get ();
		return $query->result_array ();
	}
	function getListByMemberIdCount($country_code, $whereData) {
		$this->db->where ( $whereData );
		return $this->db->count_all_results ( $country_code . '_order' );
	}
	
	// 通过订单号 获取订单信息
	function getInfoByNumber($country_code, $order_number, $fields = 'order_id,order_number,member_id,member_email,member_name,order_quantity,order_insurance,order_giftbox,order_amount,payment_amount,offers_amount,coupons_id,freight_amount,receive_name,create_time,order_status,send_status,is_resend,pay_status,doc_status,update_time,pay_type,estimated_time,transaction_id,operator,order_risk,ip_address,terminal') {
		$this->db->select ( $fields );
		$this->db->where ( 'order_number', $order_number );
		$this->db->limit ( 1 );
		return $this->db->get ( $country_code . '_order' )->row_array ();
	}
	
	// 通过订单号获取此订单号的风险程度
	function getRiskByNumber($country_code, $order_number, $fields = 'order_number,longitude,latitude,payCountry,creditCardCountry,shippingCountry,ipAddressScore,riskScore') {
		$this->db->select ( $fields );
		$this->db->where ( 'order_number', $order_number );
		$this->db->limit ( 1 );
		return $this->db->get ( $country_code . '_order_risk' )->row_array ();
	}
	function getOrderbySku($country_code = 'US', $sku = '') {
		if (! $sku)
			return;
		$this->db->select ( 'order_number' );
		$this->db->from ( $country_code . '_order_details' );
		$this->db->where ( array (
				'upper(product_sku) like' => trim ( strtoupper ( $sku ) ) . '%' 
		) );
		$query = $this->db->get ();
		return $query->result_array ();
	}
	
	// 获取订单总数量
	public function getOrderbySkuorderCount($country = 'US', $order_number = array()) {
		if (! $order_number)
			return 0;
		$this->db->where_in ( 'order_number', $order_number );
		$this->db->where ( 'pay_status !=', 0 );
		return $this->db->count_all_results ( $country . '_order' );
	}
        
        function getOrderAmountbyproductId($country_code = 'US', $productId = '',$time=array(),$return=false) {
		if (! $productId)
			return 0;
                if(empty($time)){
                    $time = array(strtotime(date('Y-m-d')),strtotime(date('Y-m-d'))+24*3600-1);
                }
		$this->db->select ( 'sum('.$country_code . '_order_details.payment_amount) as pa,sum('.$country_code.'_order_details.product_quantity) as qty' );
		$this->db->from ( $country_code . '_order_details' );
                $this->db->join($country_code . '_order', $country_code . '_order_details.order_number='.$country_code . '_order.order_number','left');
		$this->db->where ( array (
				'product_id' => $productId,
                    'pay_status >'=>0
		) );
                $this->db->where ('create_time between '.$time[0].' and '.$time[1]);
		$query = $this->db->get ();
		$res = $query->row_array ();
                //退货
                $this->db->select ( 'sum('.$country_code . '_refund_details.refund_amount) as fa,sum('.$country_code.'_refund_details.refund_quantity) as qty' );
		$this->db->from ( $country_code . '_refund_details' );
                $this->db->join($country_code . '_order', $country_code . '_refund_details.order_number='.$country_code . '_order.order_number','left');
                $this->db->join($country_code . '_refund_bills', $country_code . '_refund_details.order_number='.$country_code . '_refund_bills.order_number','left');
		$this->db->where ( array (
				'product_id' => $productId,
                    'refund_status'=>2
		) );
                $this->db->where ($country_code . '_order.create_time between '.$time[0].' and '.$time[1]);
		$query = $this->db->get ();
		$refund = $query->row_array ();
                if($refund&&isset($refund['fa']))$fa = (int)$refund['fa'];
                else $fa = 0;
                if($refund&&isset($refund['qty']))$qty = (int)$refund['qty'];
                else $qty = 0;
                if($return){
                    return array('amount'=>($res['pa']-$fa)/100,'qty'=>$res['qty']-$qty);
                }else{
                    return ($res['pa']-$fa)/100;
                }
	}
	public function _getOrder($country, $whereData, $offset = 0, $per_page = 10, $fields = 'doc_status,order_number,member_name,create_time,pay_status,send_status,payment_amount,order_risk') {
		if (empty ( $whereData ))
			return array ();
		$this->db->order_by ( 'order_id', 'desc' );
		$this->db->where_in ( 'order_number', $whereData );
		$this->db->where ( 'pay_status !=', 0 );
		$this->db->limit ( $per_page, $offset );
		$this->db->select ( $fields );
		return $this->db->get ( $country . '_order' )->result_array ();
	}
}

?>
