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
        $this->user = $this->session->userdata ( 'user_account' );
        $this->country = $this->session->userdata('my_country');
    }
    
    
    
    function index(){
    	$per_page = 10; //每页记录数
    	
    	$this->load->model('Rbacuser_model');
    	$this->page ['userName']=$this->Rbacuser_model->getInfo();
    	
    	if ($this->input->post()) {
			$pagenum = 1;
			$keyword = $this->input->post('txtKeyWords') ? $this->input->post('txtKeyWords') : 'ALL';
			$keyword_creator =  $this->input->post('s_creator') ? $this->input->post('s_creator') : 'ALL';
			$keyword_s_reason = $this->input->post('s_reason') ? $this->input->post('s_reason') : 'ALL';
		} else {
			$pagenum = ($this->uri->segment(6) === FALSE ) ? 1 : $this->uri->segment(6);
			$keyword = urldecode($this->uri->segment(3) ? $this->uri->segment(3) : 'ALL');
			$keyword_creator = urldecode($this->uri->segment(4) ? $this->uri->segment(4) : 'ALL');
			$keyword_s_reason = urldecode($this->uri->segment(5) ? $this->uri->segment(5) : 'ALL');
		}
		
		
		if (($keyword != '' and $keyword != 'ALL') || ($keyword_creator != '' and $keyword_creator != 'ALL') || ($keyword_s_reason != '' and $keyword_s_reason != 'ALL') ) {
			
			if($keyword != '' and $keyword != 'ALL'){
				$whereData['$or'] = array(
						array('order_number' => new MongoRegex('/' . htmlspecialchars($keyword) . '/i')),
						array('refund_proSku' =>  new MongoRegex('/' . htmlspecialchars($keyword) . '/i')),
						array('refund_proName' => new MongoRegex('/' . htmlspecialchars($keyword) . '/i')),
				);
			}
			
			if($keyword_creator != '' and $keyword_creator != 'ALL'){
				$whereData['creator']=htmlspecialchars($keyword_creator);
			}
			
			
			if($keyword_s_reason != '' and $keyword_s_reason != 'ALL'){
				$whereData['refund_reason']=htmlspecialchars($keyword_s_reason);
			}
			
		} else {
			$whereData = array();
		}
		
		//搜索条件赋值给前端
		$this->page['where'] = $keyword;
		$this->page['whereCreator'] = $keyword_creator;
		$this->page['whereReason'] = $keyword_s_reason;
		
		
		$this->load->model('refundApply_model');
		$total_rows =$this->refundApply_model->count($this->country,$whereData);
    	$this->page ['refundApply'] = $this->refundApply_model->getInfoApply($this->country,$whereData,($pagenum - 1) * $per_page, $per_page);
    	
    	
    	
    	//分页开始
    	$this->load->library('pagination');
    	$config['base_url'] = base_url() . 'orderRefundApply/index/' . $keyword.'/'.$keyword_creator.'/'.$keyword_s_reason;
    	$config['total_rows'] = $total_rows; //总记录数
    	$config['per_page'] = $per_page; //每页记录数
    	$config['num_links'] = 9; //当前页码边上放几个链接
    	$config['uri_segment'] = 6; //页码在第几个uri上
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
    	$this->load->model('refundApply_model');
    	$this->load->model('orderscontent_model');
    	
    	$this->page ['refundApplyDetails'] =$this->refundApply_model->getInfoById($this->country,$id);
    	$this->page ['details'] =$this->orderscontent_model->getOrderDetailsById($this->country,$id);
    	
    	if($this->page ['refundApplyDetails']['refund_proName']){
	    	$this->load->model ( 'Product_model' );
	    	$pro=$this->Product_model->orderPics ( $this->country, $this->page ['details']['product_id'] );
	    	$img = IMAGE_DOMAIN.'/product/'.$this->page ['details']['product_sku'].'/'.$this->page ['details']['product_sku'].'.jpg';
	    	if(!@fopen($img,'r')){
	    		$img = IMAGE_DOMAIN.$pro['image'];
	    	}
	    	$this->page ['details']['image']=$img;
	    	$this->page ['IMAGE_DOMAIN'] =IMAGE_DOMAIN;
    	}
    	
    	
    	
    	$this->page ['head'] = $this->load->view('head', $this->_category, true);
    	$this->page ['foot'] = $this->load->view('foot', $this->_category, true);
    	$this->load->view('orderRefundApplyContent', $this->page);
    }
    
    
    
    //修改状态
    function up_status(){
    	$id=$this->input->post('d_id');
    	$this->load->model('refundApply_model');
    	
    	$result=$this->refundApply_model->updateStatus($this->country,$id,$this->user);
    	
    	if($result){
    		redirect('orderRefundApply/applyContent/'.$id);
    	}else{
           echo "出现错误,请联系技术部".$id;  die();
    	}
    }
    
    


    
 

}

?>
