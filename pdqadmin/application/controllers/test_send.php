<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
 class test_send extends CI_Controller {
	
	public function index() {
		
		
		$json_data = file_get_contents("php://input");
		
		
		echo $json_data;
		
		
		/* $test = array (
				'order_send' => array (
						'country' => 'US - >这是国家字段',
						'order_number' => '153650548500001  ->这是订单号',
						'send_status' => '1   ->这是发货状态  发货状态(1-已发货，2-部分发货,3-备货)',
						'track_name' => '顺丰  ->这是快递方式',
						'track_code' => '123456789  ->这是快递单号',
						'track_url' => 'www.baidu.com  ->这是快递url',
						'send_bill' => '12344555  ->这是发货单号',
						'is_resend' => '0 ->默认为0  重寄一次加1' 
				) 
		);
	    $test_json=json_encode ( $test );
		
		
		
		$data=json_decode($test_json,true);
		
		echo '<pre/>';
		print_r($test); */
		
		
		//下面开始做处理
		
		
	}
}

?>