<?php

/**
 *  @说明  退款申请订单控制器
 *  @作者  zhujian
 *  @qq    407284071
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class orderRefundApply extends Pc_Controller {

    public function __construct() {
        parent::__construct();
        parent::_active('refundApply');
        $this->user_name = $this->session->userdata('user_name');
        $this->country = $this->session->userdata('my_country');
    }
    
    
    
    function index(){
    	$per_page = 10; //每页记录数
    	
    	if ($this->input->post()) {
			$pagenum = 1;
			$keyword = $this->input->post('txtKeyWords') ? $this->input->post('txtKeyWords') : 'ALL';
		} else {
			$pagenum = ($this->uri->segment(4) === FALSE ) ? 1 : $this->uri->segment(4);
			$keyword = urldecode($this->uri->segment(3) ? $this->uri->segment(3) : 'ALL');
		}
		
		
		if ($keyword != '' and $keyword != 'ALL') {
			$whereData = array(
				'order_number' => new MongoRegex ( "/$keyword/i" )
			);
		} else {
			$whereData = array();
		}
		
		//搜索条件赋值给前端
		$this->page['where'] = $keyword;
		
		$this->load->model('refundApply_model');
		$total_rows =$this->refundApply_model->count($this->country,$whereData);
    	$this->page ['refundApply'] = $this->refundApply_model->getInfoApply($this->country,$whereData,($pagenum - 1) * $per_page, $per_page);
    	
    	
    	
    	//分页开始
    	$this->load->library('pagination');
    	$config['base_url'] = base_url() . 'orderRefundApply/index/' . $keyword;
    	$config['total_rows'] = $total_rows; //总记录数
    	$config['per_page'] = $per_page; //每页记录数
    	$config['num_links'] = 9; //当前页码边上放几个链接
    	$config['uri_segment'] = 4; //页码在第几个uri上
    	$this->pagination->initialize($config);
    	$this->page['pages'] = $this->pagination->create_links();
    	//分页结束
    	
    	$this->page ['head'] = $this->load->view('head', $this->_category, true);
    	$this->page ['foot'] = $this->load->view('foot', $this->_category, true);
    	$this->load->view('orderRefundApplyList', $this->page);
    }
    
    
    
    
    //获取退款申请内容
    function applyContent($id){
    	$this->load->helper ( 'form' );
    	
    	$this->load->model ( 'order_model' );
    	$this->load->model('Product_model');
    	$this->load->model('refundApply_model');
    	$this->load->model('refundBills_model');
    	$this->load->model('orderscontent_model');
    	
    	$this->page ['refundApplyDetails'] =$this->refundApply_model->getInfoById($this->country,$id);
    	$proDetails =$this->orderscontent_model->getOrderDetails($this->country,$this->page ['refundApplyDetails']['order_number']);
    	$this->page ['detail'] =$proDetails;
    	
    	
    	// 获取订单信息
    	$this->page ['orders'] = $this->order_model->getInfoByNumber ( $this->country, $this->page ['refundApplyDetails']['order_number'],'order_number,freight_amount,payment_amount');
    	
    	// 获取订单退款
    	$this->page ['refunds'] = $this->refundBills_model->getRefundByNumber ( $this->country, $this->page ['refundApplyDetails']['order_number'], $fields = 'refund_id,proposer_name,operator,refund_amount,refund_status,create_time,update_time,refund_details' );
    	// 获取订单退款详情
    	$refunds_detail = $this->orderscontent_model->getOrderRefundDetail ( $this->country, $this->page ['refundApplyDetails']['order_number'] );
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
    	//获取本次退货产品的详情
    	foreach ($proDetails as $key=>$pro){
    		foreach($this->page ['refundApplyDetails']['pro'] as $k=>$v){
    			if($pro['details_id']==$k){
    				$this->page ['detail'][$key]['apply_quantity']=$v;
    				$proDetails[$key]['product_quantity']=$v;
    				$products[]=$proDetails[$key];
    			}
    			
    		}
    	}
    	
    	
    	$this->page ['products'] =$products;
    	$refund_quantity=0;
    	$refund_amount=0;
    	//组装本次退货的总数量和退货总价 和图片
    	foreach ($this->page ['products'] as $key => $detail) {
    		$refund_quantity+=$detail['product_quantity'];
    		$refund_amount+=$detail['product_quantity']*$detail['payment_price'];
    		$pro = $this->Product_model->orderPics($this->country, $detail['product_id']);
    		$img = IMAGE_DOMAIN . '/product/' . $detail['product_sku'] . '/' . $detail['product_sku'] . '.jpg';
    		if (!@fopen($img, 'r')) {
    			$img = IMAGE_DOMAIN . $pro['image'];
    		}
    	
    		$this->page ['products'][$key]['image'] = $img;
    	}
    	$this->page ['refund_quantity']=$refund_quantity;
    	$this->page ['refund_amount']=$refund_amount;
    	
    	$this->page ['head'] = $this->load->view('head', $this->_category, true);
    	$this->page ['foot'] = $this->load->view('foot', $this->_category, true);
    	$this->load->view('orderRefundApplyContent', $this->page);
    }
    
    


    
 

}

?>
