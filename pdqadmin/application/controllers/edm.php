<?php
/**
   *  @说明  	edm控制器
   *  @作者  	zhujian
   *  @qq   407284071
   */
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class edm extends Pc_Controller {
	public function __construct() {
		parent::__construct ();
		parent::_active ( 'orders' );
		$this->country = $this->session->userdata ( 'my_country' );
	}
	
	// 显示视图
	public function index() {
		$this->load->model ( 'order_model' );
		$this->load->model ( 'orderscontent_model' );
		
		$startTime = $this->input->post ( 'startTime' ) ? $this->input->post ( 'startTime' ) : date ( "Y-m-d");
		$endTime = $this->input->post ( 'endTime' ) ? $this->input->post ( 'endTime' ) : date ( "Y-m-d" ) ;
		$this->page ['start'] = $startTime;
		$this->page ['end'] = $endTime;
		

		$whereData ['create_time >='] = strtotime ( $startTime.'00:00:00' );
		$whereData ['create_time <='] = strtotime ( $endTime.'23:59:59' );
		
		
		if($this->input->post ( 'campaign' )){
			$campaign=$this->input->post ( 'campaign' );
			$this->page ['campaign'] = $campaign;
		}
		
		
		if($this->input->post ( 'country' )){
			$country=$this->input->post ( 'country' );
		}else{
			$country=$this->country;
		}
		
		
		
		$orders = $this->order_model->getOrder ( $country, $whereData, 0,0, 'order_number,payment_amount' );
		$totalOrder = 0;
		$totalRevenue = 0;
		
		if (count ( $orders ) > 0) {
			foreach ( $orders as $key => $order ) {
				$order_details = $this->orderscontent_model->getOrderDetails ( $country, $order ['order_number'], 'product_name,product_quantity' );
				$order_append = $this->orderscontent_model->getOrderAppend ( $country, $order ['order_number'], 'landing_page' );
				
				if(!strpos($order_append['landing_page'],"utm_medium=email")){
					unset($orders[$key]);
					continue;
				}
				
				if(!empty($campaign)){
					if(!strpos($order_append['landing_page'], "utm_campaign=".$campaign)){
						unset($orders[$key]);
						continue;
					}
				}
				
				
				
				$totalRevenue += $order ['payment_amount'];
				$totalOrder++;
				$orders [$key] ['products'] = $order_details;
				$orders [$key] ['landing_page'] = $order_append ['landing_page'];
			}
		}
		
		

		$this->page ['country'] = $country;
		$this->page ['totalOrder'] = $totalOrder;
		$this->page ['totalRevenue'] = $totalRevenue;
		$this->page ['orders'] = $orders;
		
		$this->page ['head'] = $this->load->view ( 'head', $this->_category, true );
		$this->page ['foot'] = $this->load->view ( 'foot', $this->_category, true );
		$this->load->view ( 'edm', $this->page );
	}
	
	

	

}

?>