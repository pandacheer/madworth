<?php
/**
   *  @说明  订单细节控制器
   *  @作者  zhujian
   *  @qq    407284071
   */
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class ordersContent extends Pc_Controller {
	private $user;
	public function __construct() {
		parent::__construct ();
		parent::_active ( 'orders' );
		$this->country = $this->session->userdata ( 'my_country' );
		$this->user = $this->session->userdata ( 'user_account' );
		$this->user_id = $this->session->userdata ( 'user_id' );
		$this->load->model ( 'order_model' );
		$this->load->model ( 'refundBills_model' );
		$this->load->model ( 'orderscontent_model' );
		$this->page ['sysSendStatus'] = [ 
				'0' => 'Unfulfilled',
				'1' => 'Fulfilled',
				'2' => 'Partially Fulfilled',
				'3' => 'Dispatched' 
		];
		$this->page ['sysPayStatus'] = [ 
				'0' => 'Unpaid',
				'1' => 'Paid',
				'2' => 'Refund',
				'3' => 'Partially Refund' 
		];
		$this->page ['orderStatus'] = [ 
				'0' => 'payment',
				'1' => 'shipments',
				'2' => 'refund',
				'3' => 'dispatched' 
		];
		$this->page ['dispose'] = [ 
				'0' => '发货',
				'1' => '重寄',
				'2' => '退款',
				'3' => '退运费',
				'4' => '退关税',
				'5' => 'Coupon' 
		];
	}
	
	// 单击订单号显示订单详情
	public function index($order_number = 0) {
		$this->load->helper ( 'form' );
		
		// 获取订单详情
		$this->page ['detail'] = $this->orderscontent_model->getOrderDetails ( $this->country, $order_number );
		if (! count ( $this->page ['detail'] )) {
			redirect ( "orders" );
		}
		
		$this->load->model ( 'Product_model' );
		foreach ( $this->page ['detail'] as $key => $detail ) {
			$pro = $this->Product_model->orderPics ( $this->country, $detail ['product_id'] );
			$img = IMAGE_DOMAIN . '/product/' . $detail ['product_sku'] . '/' . $detail ['product_sku'] . '.jpg';
			if (! @fopen ( $img, 'r' )) {
				$img = IMAGE_DOMAIN . $pro ['image'];
			}
			
			$this->page ['detail'] [$key] ['image'] = $img;
		}
		
		// 获取订单收获地址
		$this->page ['shipping'] = $this->orderscontent_model->getOrderShip ( $this->country, $order_number );
		// 获取订单账单地址
		$this->page ['billing'] = $this->orderscontent_model->getOrderBill ( $this->country, $order_number );
		// 获取订单信息
		$this->page ['orders'] = $this->order_model->getInfoByNumber ( $this->country, $order_number );
		// 获取订单附加信息
		$this->page ['append'] = $this->orderscontent_model->getOrderAppend ( $this->country, $order_number );
		// 获取订单日志信息
		$this->page ['log'] = $this->orderscontent_model->getOredrLog ( $this->country, $order_number );
		// 获取订单退款
		$this->page ['refunds'] = $this->refundBills_model->getRefundByNumber ( $this->country, $order_number, $fields = 'refund_id,proposer_name,operator,refund_amount,refund_status,create_time,update_time,refund_details' );
		// 获取订单退款详情
		$refunds_detail = $this->orderscontent_model->getOrderRefundDetail ( $this->country, $order_number );
		// 获取是否有未处理的退款单
		$this->page ['is_untreated'] = $this->refundBills_model->getUntreated ( $this->country, $order_number, $fields = 'refund_id' );
		// 获取是否有未处理的客户退款申请单
		$this->page ['is_Apply'] = $this->refundBills_model->getRefundStatus ( $this->country, $order_number );
		// 获取订单留言信息
		$this->page ['order_message'] = $this->orderscontent_model->getOrderMemo ( $this->country, $order_number );
		// 获取投诉信息
		$this->page ['order_complaints'] = $this->orderscontent_model->getComplaints ( $this->country, $order_number );
		// 获取订单的风险
		$this->page ['order_risk'] = $this->order_model->getRiskByNumber ( $this->country, $order_number );
		
		// 获取订单发货信息
		$result = $this->page ['orders'] ['is_resend'];
		if ($result > 1) {
			for($i = 1; $i < $result; $i ++) {
				$arr_send [$i] = $this->orderscontent_model->getSend ( $this->country, $order_number, $i );
			}
			$this->page ['is_send'] = 1;
			$this->page ['arr_send'] = $arr_send;
		} else {
			$this->page ['is_send'] = 0;
		}
		
		$this->page ['track'] = $this->orderscontent_model->getSend ( $this->country, $order_number, $result );
		
		$this->page ['complaint'] = $this->orderscontent_model->getSend ( $this->country, $order_number, 0 );
		
		// 组装总订单退款价格
		$amount = 0;
		foreach ( $this->page ['refunds'] as $value ) {
			$amount += $value ['refund_amount'];
		}
		
		// 把退货数据组装到订单详情数据中
		foreach ( $refunds_detail as $v ) {
			foreach ( $this->page ['detail'] as $key => $value ) {
				if ($v ['product_sku'] == $value ['bundle_skus']) {
					@$this->page ['detail'] [$key] ['refund_quantity'] += $v ['refund_quantity'];
				}
			}
		}
		
		$this->page ['amount'] = $amount;
		$this->page ['head'] = $this->load->view ( 'head', $this->_category, true );
		$this->page ['foot'] = $this->load->view ( 'foot', $this->_category, true );
		$this->load->view ( 'orderscontent', $this->page );
	}
	
	// 后台管理员添加订单备注(修改订单表)
	public function addOrderMemo() {
		$order_number = $this->input->post ( 'order_number', TRUE );
		$memo = $this->input->post ( 'memo', TRUE );
		
		$result = $this->orderscontent_model->addOrderMemo ( $this->country, $order_number, $memo, $this->user );
		if ($result) {
			$message = $memo . ',' . date ( 'Y-m-d H:i:s', time () ) . ',' . $this->user;
			exit ( json_encode ( $message ) );
		} else {
			exit ( json_encode ( 'error' ) );
		}
	}
	
	// 修改收货地址
	public function editOrderShip() {
		$order_number = $this->input->post ( 'order_number', TRUE );
		
		// 收货人姓名
		$receive_firstName = $this->input->post ( 'receive_firstName', TRUE );
		$receive_lastName = $this->input->post ( 'receive_lastName', TRUE );
		// 收件人公司
		$receive_company = $this->input->post ( 'receive_company', TRUE );
		// 国家
		$receive_country = $this->input->post ( 'receive_country', TRUE );
		// 省
		$receive_province = $this->input->post ( 'receive_province', TRUE );
		// 市
		$receive_city = $this->input->post ( 'receive_city', TRUE );
		// 地址1
		$receive_add1 = $this->input->post ( 'receive_add1', TRUE );
		// 地址2
		$receive_add2 = $this->input->post ( 'receive_add2', TRUE );
		// 邮编
		$receive_zipcode = $this->input->post ( 'receive_zipcode', TRUE );
		// 收货人电话
		$receive_phone = $this->input->post ( 'receive_phone', TRUE );
		
		$data = array (
				'receive_firstName' => $receive_firstName,
				'receive_lastName' => $receive_lastName,
				'receive_company' => $receive_company,
				'receive_country' => $receive_country,
				'receive_province' => $receive_province,
				'receive_city' => $receive_city,
				'receive_add1' => $receive_add1,
				'receive_add2' => $receive_add2,
				'receive_zipcode' => $receive_zipcode,
				'receive_phone' => $receive_phone 
		);
		
		$result = $this->orderscontent_model->editOrderShip ( $this->country, $order_number, $data );
		redirect ( "ordersContent/$order_number" );
	}
	
	// 修改账单地址
	public function editOrderBill() {
		$order_number = $this->input->post ( 'order_number', TRUE );
		
		// 收货人姓名
		$receive_firstName = $this->input->post ( 'receive_firstName', TRUE );
		$receive_lastName = $this->input->post ( 'receive_lastName', TRUE );
		// 收件人公司
		$receive_company = $this->input->post ( 'receive_company', TRUE );
		// 国家
		$receive_country = $this->input->post ( 'receive_country', TRUE );
		// 省
		$receive_province = $this->input->post ( 'receive_province', TRUE );
		// 市
		$receive_city = $this->input->post ( 'receive_city', TRUE );
		// 地址1
		$receive_add1 = $this->input->post ( 'receive_add1', TRUE );
		// 地址2
		$receive_add2 = $this->input->post ( 'receive_add2', TRUE );
		// 邮编
		$receive_zipcode = $this->input->post ( 'receive_zipcode', TRUE );
		// 收货人电话
		$receive_phone = $this->input->post ( 'receive_phone', TRUE );
		
		$data = array (
				'receive_firstName' => $receive_firstName,
				'receive_lastName' => $receive_lastName,
				'receive_company' => $receive_company,
				'receive_country' => $receive_country,
				'receive_province' => $receive_province,
				'receive_city' => $receive_city,
				'receive_add1' => $receive_add1,
				'receive_add2' => $receive_add2,
				'receive_zipcode' => $receive_zipcode,
				'receive_phone' => $receive_phone 
		);
		
		$result = $this->orderscontent_model->editOrderBill ( $this->country, $order_number, $data );
		
		// $this->index($order_number);
		redirect ( "ordersContent/$order_number" );
	}
	
	// 修改订单的归档状态
	public function updateArchive() {
		$order_number = $this->input->post ( 'order_number', TRUE );
		$archive = $this->input->post ( 'archive', TRUE );
		
		$result = $this->orderscontent_model->updateArchive ( $this->country, $order_number, $archive, $this->user );
		
		if ($result) {
			exit ( json_encode ( array (
					'success' => true 
			) ) );
		} else {
			exit ( json_encode ( array (
					'success' => False 
			) ) );
		}
	}
	
	// 修改订单的状态
	public function updateStatus() {
		$order_number = $this->input->post ( 'order_number', TRUE );
		$order_status = $this->input->post ( 'order_status', TRUE );
		
		$result = $this->orderscontent_model->updateStatus ( $this->country, $order_number, $order_status, $this->user );
		
		if ($result) {
			exit ( json_encode ( array (
					'success' => true 
			) ) );
		} else {
			exit ( json_encode ( array (
					'success' => False 
			) ) );
		}
	}
	
	// 根据发货单查询信息
	public function get_complaint() {
		$send_bill = $this->input->post ( 'send_bill', TRUE );
		$order_number = $this->input->post ( 'order_number', TRUE );
		
		$sends = $this->orderscontent_model->complaint_send ( $this->country, $send_bill, $order_number );
		
		$sends ['send_time'] = date ( 'Y-m-d H:i:s', $sends ['send_time'] );
		
		if ($sends) {
			exit ( json_encode ( $sends ) );
		} else {
			exit ( json_encode ( 'error' ) );
		}
	}
	
	// 添加投诉信息
	public function addComplaints() {
		// 获取页面传来的数据
		$order_number = $this->input->post ( 'order_number', TRUE );
		$dispose = $this->input->post ( 'dispose', TRUE );
		if ($dispose == 5) {
			$coupon = $this->input->post ( 'coupon', TRUE );
		} else {
			$coupon = 0;
		}
		
		if ($dispose == 0 || $dispose == 1 || $dispose == 5) {
			$refund_amount = 0;
		} else {
			$refund_amount = $this->input->post ( 'refund_amount', TRUE );
		}
		
		$send_time = strtotime ( $this->input->post ( 'send_time', TRUE ) );
		$detail = $this->orderscontent_model->getOrderDetails ( $this->country, $order_number );
		
		$products = '';
		foreach ( $detail as $value ) {
			$products .= $value ['product_name'] . '×' . $value ['product_sku'] . '×' . $value ['product_attr'] . '×' . $value ['product_quantity'] . ',';
		}
		
		$complaint = array (
				'order_number' => $order_number,
				'member_name' => $this->input->post ( 'member_name', TRUE ),
				'send_bill' => $this->input->post ( 'send_bill', TRUE ),
				'send_time' => $send_time,
				'products' => $products,
				'logistics' => $this->input->post ( 'logistics', TRUE ),
				'track_code' => $this->input->post ( 'track_code', TRUE ),
				'question_type' => $this->input->post ( 'question_type', TRUE ),
				'question_remark' => $this->input->post ( 'question_remark', TRUE ),
				'refund_remark' => $this->input->post ( 'refund_remark', TRUE ),
				'department' => $this->input->post ( 'department', TRUE ),
				'dispose' => $dispose,
				'refund_amount' => $refund_amount,
				'coupon' => $coupon,
				'create_time' => time (),
				'operator' => $this->user 
		);
		
		if ($orders = $this->orderscontent_model->addComplaints ( $this->country, $complaint )) {
			redirect ( "ordersContent/$order_number" );
		} else {
			echo 'error';
		}
	}
	
	// 修改发货状态
	public function send_success() {
		$order_number = $this->input->post ( 'order_number', TRUE );
		$send_status = $this->input->post ( 'send_status', TRUE );
		$sexpress_name = $this->input->post ( 'express_name', TRUE );
		$express_code = $this->input->post ( 'express_code', TRUE );
		$express_url = $this->input->post ( 'express_url', TRUE );
		$time = time ();
		$orders = $this->order_model->getInfoByNumber ( $this->country, $order_number, 'is_resend' );
		
		if ($send_status == 1) {
			$doc_status = 2;
			$log_status = 1;
			$order_memo = 'Fulfilled';
		} else if ($send_status == 2) {
			$doc_status = $orders ['is_resend'];
			$log_status = 1;
			$order_memo = 'Partially Fulfilled';
		} else if ($send_status == 3) {
			$doc_status = $orders ['is_resend'];
			$log_status = 3;
			$order_memo = 'Dispatched';
		}
		
		$order_data = array (
				'send_status' => $send_status,
				'doc_status' => $doc_status,
				'update_time' => $time,
				'operator' => $user . '_send_status_' . $send_status 
		);
		
		// 假数据
		$send_bill = 'SX' . $time;
		
		$send_data = array (
				'order_number' => $order_number,
				'send_status' => $send_status,
				'track_name' => $sexpress_name,
				'track_code' => $express_code,
				'track_url' => $express_url,
				'send_bill' => $send_bill,
				'send_time' => $time,
				'logistics' => 'EMS',
				'is_resend' => $orders ['is_resend'],
				'create_time' => $time 
		);
		
		$order_log = array (
				'order_number' => $order_number,
				'order_status' => $log_status,
				'order_memo' => $order_memo,
				'create_time' => $time,
				'operator' => $this->user 
		);
		
		if ($this->orderscontent_model->send_success ( $this->country, $order_number, $order_data, $send_data, $order_log )) {
			redirect ( "ordersContent/$order_number" );
		} else {
			echo 'error';
		}
	}
	
	// 修改为重寄
	public function addRedirect() {
		$order_number = $this->input->post ( 'order_number', TRUE );
		
		if ($this->orderscontent_model->addRedirect ( $this->country, $order_number, $this->user )) {
			redirect ( "ordersContent/$order_number" );
		} else {
			echo 'error';
		}
	}
	
	// 添加退款
	public function add_refund() {
		$order_number = $this->input->post ( 'order_number', TRUE );
		
		// 获取已退款总金额
		$all_amount = $this->refundBills_model->refund_sum ( $this->country, $order_number );
		// 获取总金额
		$payment_amount = $this->order_model->getInfoByNumber ( $this->country, $order_number, $fields = 'payment_amount,order_insurance,order_giftbox' );
		$payment_amount ['payment_amount'] = $payment_amount ['payment_amount'] - $payment_amount ['order_insurance'] - $payment_amount ['order_giftbox'];
		
		// 获取本次退款的金额
		$amount = $this->input->post ( 're_amount', TRUE ) * 100;
		
		if (! $amount) {
			redirect ( "ordersContent/$order_number" );
		}
		if ($all_amount ['refund_amount'] >= $payment_amount ['payment_amount']) {
			redirect ( "ordersContent/$order_number" );
		} else if ($all_amount ['refund_amount'] + (int)$amount  > $payment_amount ['payment_amount']) {
			redirect ( "ordersContent/$order_number" );
		} else {
			$this->load->model ( 'Sequence_model' );
			$refund_id = $this->Sequence_model->CreateRefundId ();
			
			// 退款数量 多个
			$quantity = $this->input->post ( 're_quantity', TRUE );
			
			$time = time ();
			
			$detail = $this->orderscontent_model->getOrderDetails ( $this->country, $order_number );
			$order_info = $this->order_model->getInfoByNumber ( $this->country, $order_number, 'transaction_id,pay_type' );
			
			// 循环组装退款详情表 并获取总数量(也可都表获取数据 不用post)
			$quantitys = 0;
			foreach ( $quantity as $key => $value ) {
				// 数量为0的数据不组装
				if ($value > 0) {
					$refund_details [$key] ['refund_id'] = $refund_id;
					$refund_details [$key] ['order_number'] = $order_number;
					$refund_details [$key] ['refund_price'] = $detail [$key] ['payment_price'];
					$refund_details [$key] ['refund_quantity'] = $value;
					$refund_details [$key] ['refund_amount'] = $detail [$key] ['payment_price'] * $value;
					$refund_details [$key] ['product_name'] = $detail [$key] ['product_name'];
					$refund_details [$key] ['product_id'] = $detail [$key] ['product_id'];
					$refund_details [$key] ['product_sku'] = $detail [$key] ['bundle_skus'];
					$refund_details [$key] ['product_attr'] = $detail [$key] ['product_attr'];
					$quantitys += $value;
				}
			}
			
			// 组装数组进行添加退款表
			$refund_bills ['refund_id'] = $refund_id;
			$refund_bills ['order_number'] = $order_number;
			$refund_bills ['order_transaction_id'] = $order_info ['transaction_id'];
			$refund_bills ['pay_type'] = $order_info ['pay_type'];
			$refund_bills ['refund_details'] = $this->input->post ( 're_details', TRUE );
			$refund_bills ['refund_reason'] = $this->input->post ( 're_reason', TRUE );
			$refund_bills ['refund_resolution'] = $this->input->post ( 're_resolution', TRUE );
			$refund_bills ['refund_quantity'] = $quantitys;
			$refund_bills ['refund_amount'] = $amount;
			$refund_bills ['proposer_name'] = $this->user;
			$refund_bills ['proposer_id'] = $this->user_id;
			$refund_bills ['create_time'] = $time;
			
			if (! isset ( $refund_details )) {
				$refund_details [0] ['refund_id'] = $refund_id;
				$refund_details [0] ['order_number'] = $order_number;
				$refund_details [0] ['refund_price'] = 0;
				$refund_details [0] ['refund_quantity'] = 0;
				$refund_details [0] ['refund_amount'] = 0;
				$refund_details [0] ['product_name'] = 0;
				$refund_details [0] ['product_id'] = 0;
				$refund_details [0] ['product_sku'] = 0;
				$refund_details [0] ['product_attr'] = 0;
			}
			
			$order_log = array (
					'order_number' => $order_number,
					'order_status' => 2,
					'order_memo' => 'applicant_refund',
					'create_time' => $time,
					'operator' => $this->user 
			);
			
			$refund_status = $this->input->post ( 'refund_status', TRUE );
			if ($this->refundBills_model->add_refund ( $this->country, $refund_bills, $refund_details, $order_log )) {
				if ($refund_status == 1) {
					redirect ( "ordersContent/$order_number" );
				} else {
					$this->load->model ( 'refundApply_model' );
					$r_id = $this->input->post ( 'r_id', TRUE );
					if ($this->refundApply_model->updateStatus ( $this->country, $r_id )) {
						redirect ( "orderRefundApply/applyContent/$r_id" );
					} else {
						echo 'ERROR_2';
						die ();
					}
				}
			} else {
				echo 'ERROR';
				die ();
			}
		}
	}
}

?>
