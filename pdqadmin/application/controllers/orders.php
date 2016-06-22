<?php
/**
   *  @说明  订单控制器
   *  @作者  zhujian
   *  @qq    407284071
   */
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class orders extends Pc_Controller {
	public function __construct() {
		parent::__construct ();
		parent::_active ( 'orders' );
		$this->country = $this->session->userdata ( 'my_country' );
		$this->load->model ( 'order_model' );
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
		$this->page ['sysDocStatus'] = [ 
				'1' => 'Processing',
				'2' => 'Archived',
				'3' => 'Canceled' 
		];
	}
	
	// 显示订单
	public function index() {
		$this->load->helper ( 'form' );
		$per_page = 10; // 每页记录数
		
		if ($this->input->post ()) {
			$pagenum = 1;
			$keyword = $this->input->post ( 'search' ) ? trim ( $this->input->post ( 'search' ) ) : 'ALL';
			$keyword2 = $this->input->post ( 's_status' ) ? $this->input->post ( 's_status' ) : 'ALL';
		} else {
			$pagenum = ($this->uri->segment ( 5 ) === FALSE) ? 1 : $this->uri->segment ( 5 );
			$keyword = urldecode ( $this->uri->segment ( 3 ) ? $this->uri->segment ( 3 ) : 'ALL' );
			$keyword2 = urldecode ( $this->uri->segment ( 4 ) ? $this->uri->segment ( 4 ) : 'ALL' );
		}
		
		if ($keyword != 'ALL') {
			if ($keyword2 == 'ALL') {
				redirect ( "orders" );
			}
			
			if ($keyword2 == 'receive_add1') {
				$order_number = $this->order_model->getShipOrderNum ( $this->country, $keyword );
				$arr;
				
				if ($order_number) {
					foreach ( $order_number as $key => $value ) {
						$arr [] = $value ['order_number'];
					}
					
					$total_rows = $this->order_model->getShipOrderCount ( $this->country, $arr );
					$this->page ['orders'] = $this->order_model->getShipOrder ( $this->country, $arr, ($pagenum - 1) * $per_page, $per_page );
				} else {
					$arr = 0;
					$total_rows = $this->order_model->getShipOrderCount ( $this->country, $arr );
					$this->page ['orders'] = $this->order_model->getShipOrder ( $this->country, $arr, ($pagenum - 1) * $per_page, $per_page );
				}
			} elseif ($keyword2 == 'sku') {
				$order_numbers = $this->order_model->getOrderbySku ( $this->country, $keyword );
				$tmp = array ();
				if ($order_numbers) {
					foreach ( $order_numbers as $k => $v ) {
						$tmp [] = $v ['order_number'];
					}
				}
				$total_rows = $this->order_model->getOrderbySkuorderCount ( $this->country, $tmp );
				$this->page ['orders'] = $this->order_model->_getOrder ( $this->country, $tmp, ($pagenum - 1) * $per_page, $per_page );
			} else {
				if (strstr ( $keyword, ',' )) {
					$whereData = '';
					$conditions = explode ( ",", $keyword );
					foreach ( $conditions as $key => $v ) {
						if (count ( $conditions ) == $key + 1) {
							$whereData .= "$keyword2 = $v";
						} else {
							$whereData .= "$keyword2 = $v OR ";
						}
					}
				} else {
					$whereData [$keyword2 . ' like'] = "%$keyword%";
				}
				
				$total_rows = $this->order_model->orderCount ( $this->country, $whereData );
				$this->page ['orders'] = $this->order_model->getOrder ( $this->country, $whereData, ($pagenum - 1) * $per_page, $per_page );
			}
		} else {
			$whereData = [ ];
			$total_rows = $this->order_model->orderCount ( $this->country, $whereData );
			$this->page ['orders'] = $this->order_model->getOrder ( $this->country, $whereData, ($pagenum - 1) * $per_page, $per_page );
		}
		
		// 查询订单用户是否有留言
		foreach ( $this->page ['orders'] as $key => $order ) {
			$message = $this->orderscontent_model->getOrderAppend ( $this->country, $order ['order_number'], 'order_guestbook' );
			$this->page ['orders'] [$key] ['message'] = $message ['order_guestbook'];
		}
		
		// 分页开始
		$this->load->library ( 'pagination' );
		$config ['base_url'] = base_url () . 'orders/index/' . $keyword . '/' . $keyword2;
		$config ['total_rows'] = $total_rows; // 总记录数
		$config ['per_page'] = $per_page; // 每页记录数
		$config ['num_links'] = 10; // 当前页码边上放几个链接
		$config ['uri_segment'] = 5; // 页码在第几个uri上
		$this->pagination->initialize ( $config );
		$this->page ['pages'] = $this->pagination->create_links ();
		// 分页结束
		
		$this->page ['head'] = $this->load->view ( 'head', $this->_category, true );
		$this->page ['foot'] = $this->load->view ( 'foot', $this->_category, true );
		
		// 赋值搜索条件到前台
		$this->page ['where'] = array (
				$keyword,
				$keyword2 
		);
		$this->load->view ( 'orders', $this->page );
	}
	
	// 发货
	public function fulfil() {
		$this->load->view ( 'ffau', $this->page );
	}
	
	// 业务返回码为成功时，修改付款状态(记得传过去的订单号加密)
	public function pay_suceess() {
		$order_number = 1111;
		$result = $this->order_model->pay_suceess ( $this->country, $order_number );
		if ($result) {
			echo "success";
		} else {
			echo "error";
		}
	}
	
	// 全部退款成功后 修改付款状态
	public function all_refund() {
		// 获取选中的订单(order_number)
		$num = array (
				1111,
				2222 
		);
		
		foreach ( $num as $key => $value ) {
			$data [$key] ['order_number'] = $value;
			$data [$key] ['pay_status'] = 2;
		}
		
		$result = $this->order_model->update_status ( $this->country, $data );
		if ($result) {
			echo "success";
		} else {
			echo "error";
		}
	}
	
	// 部分退款成功后 修改付款状态
	public function part_refund() {
		// 获取选中的订单(order_number)
		$num = array (
				1111,
				2222 
		);
		
		foreach ( $num as $key => $value ) {
			$data [$key] ['order_number'] = $value;
			$data [$key] ['pay_status'] = 3;
		}
		
		$result = $this->order_model->update_status ( $this->country, $data );
		if ($result) {
			echo "success";
		} else {
			echo "error";
		}
	}
	
	// 归档状态 修改归档状态为已完成
	public function doc_complete() {
		// 获取选中的订单(order_number)
		$num = array (
				1111,
				2222 
		);
		
		foreach ( $num as $key => $value ) {
			$data [$key] ['order_number'] = $value;
			$data [$key] ['doc_status'] = 2;
		}
		
		$result = $this->order_model->update_status ( $this->country, $data );
		if ($result) {
			echo "success";
		} else {
			echo "error";
		}
	}
	
	// 归档状态 修改归档状态为已取消
	public function doc_cancel() {
		// 获取选中的订单(order_number)
		$num = array (
				1111,
				2222 
		);
		
		foreach ( $num as $key => $value ) {
			$data [$key] ['order_number'] = $value;
			$data [$key] ['doc_status'] = 3;
		}
		
		$result = $this->order_model->update_status ( $this->country, $data );
		if ($result) {
			echo "success";
		} else {
			echo "error";
		}
	}
	
	// 更改发货状态为已发货 修改订单进度表信息
	public function send_success() {
		// 获取选中的订单(order_number)
		$num = array (
				1111,
				2222 
		);
		
		foreach ( $num as $key => $value ) {
			$data [$key] ['order_number'] = $value;
			$data [$key] ['send_status'] = 2;
		}
		
		$result_order = $this->order_model->update_status ( $this->country, $data );
		if ($result_order) {
			$time = time ();
			foreach ( $num as $key => $value ) {
				$order_time [$key] ['order_number'] = $value;
				$order_time [$key] ['send_date'] = $time;
				$order_time [$key] ['update_date'] = $time;
			}
			$result_orderTime = $this->order_model->update_orderTime ( $this->country, $order_time );
			if ($result_orderTime) {
				echo "seccess";
			} else {
				echo "ordr_time_error";
			}
		} else {
			echo "order_error";
		}
	}
	
	// 对会员分析表修改数据(先做个方法进行测试,到时候放在其它方法内部进行处理)
	public function addMemberAnalysis() {
		$data = array (
				array (
						'member_id' => 1,
						'last_order' => 1434595208,
						'order_spent' => 85 
				),
				array (
						'member_id' => 1,
						'last_order' => 1634595208,
						'order_spent' => 50 
				),
				array (
						'member_id' => 1,
						'last_order' => 4434595208,
						'order_spent' => 20 
				) 
		);
		$this->order_model->order_analysis ( $data );
	}
	
	// 导入老订单方法 3个月前的
	public function importShopifyOrder($i = 1) {
		
		// 测试国家为au的api地址 测试添加表为US 记住修改
		$country = 'IE';
		$url = 'https://325753f5cfaf12ed43aa0e4c016e4d1d:ae2a605a911df7f2dbca13c634152cba@drgrab-ie.myshopify.com/admin/orders.json?limit=250&status=any&since_id=2147729985&page=' . $i;
		echo '本次采集地址:<br/>' . $url . '<br/>';
		$json = $this->_getJson ( $url );
		$shopifyOrders = json_decode ( $json, true );
		
		if (! count ( $shopifyOrders ['orders'] )) {
			exit ( '<br/><span style="color:green">木有数据啦  已经全部导完啦~~~</span><br/>' );
		}
		
		foreach ( $shopifyOrders ['orders'] as $key => $order ) {
			$product = array ();
			$data = array ();
			
			foreach ( $order ['line_items'] as $products ) {
				$product [] = array (
						'title' => $products ['title'],
						'quantity' => $products ['quantity'],
						'price' => $products ['price'],
						'attr' => $products ['variant_title'] 
				);
			}
			
			$data = array (
					'_id' => $order ['name'],
					'email' => $order ['email'],
					'created_at' => strtotime ( $order ['created_at'] ),
					'total_price' => $order ['total_price'],
					'subtotal_price' => $order ['subtotal_price'],
					'product' => $product 
			);
			
			// 加入数据库 没有模型 直接在这里加吧 ^_^
			$orderShopifyMongo = $this->mongo->{$country . '_shopifyOrders'};
			
			// $isOrderShopify=$orderShopifyMongo->find(array("_id" => $data['_id']))->count();
			$result = $orderShopifyMongo->insert ( $data );
			if ($result ['ok']) {
				echo '<br/><span style="color:green">添加成功 导入订单ID为 ' . $data ['_id'] . ' 可喜可贺</span><br/>';
			} else {
				exit ( '<br/><span style="color:red">添加失败 T_T  <pre>  加入数据的老订单数据为:<br/> ' . print_r ( $data ) . '</span><br/>' );
			}
		}
		
		$this->page ['goto'] = '/orders/' . __FUNCTION__ . '/' . ($i + 1);
		$this->load->view ( 'excel', $this->page );
	}
	private function _getJson($url) {
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		return curl_exec ( $ch );
	}
}

?>
