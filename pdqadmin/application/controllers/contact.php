<?php
/**
   *  @作者   zhujian
   *  @qq   407284071
*/

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class contact extends Pc_Controller {
	
	public function __construct() {
		parent::__construct ();
		parent::_active ( 'contact' );
		$this->load->model ( 'contact_model' );
		$this->user = $this->session->userdata ( 'user_account' );
		$this->country = $this->session->userdata ( 'my_country' );
	}
	
	
	
	function index(){
		$per_page = 10; //每页记录数
		
		if ($this->input->post()) {
			$pagenum = 1;
			$keyword = $this->input->post('txtKeyWords') ? $this->input->post('txtKeyWords') : 'ALL';
			$keyword_s_status = $this->input->post('s_status') ? $this->input->post('s_status') : 'ALL';
		} else {
			$pagenum = ($this->uri->segment(5) === FALSE ) ? 1 : $this->uri->segment(5);
			$keyword = urldecode($this->uri->segment(3) ? $this->uri->segment(3) : 'ALL');
			$keyword_s_status = urldecode($this->uri->segment(4) ? $this->uri->segment(4) : 'ALL');
		}
		
		
		if (($keyword != '' and $keyword != 'ALL') || ($keyword_s_status != '' and $keyword_s_status != 'ALL') ) {
			
			if($keyword != '' and $keyword != 'ALL'){
				$whereData['$or'] = array(
					array('email' => new MongoRegex('/' . htmlspecialchars($keyword) . '/i'))
				);
			}
			
			if($keyword_s_status != '' and $keyword_s_status != 'ALL'){
				$whereData['status'] =  (int)$keyword_s_status;
			}
			
		} else {
			$whereData = array();
		}
		
		//搜索条件赋值给前端
		$this->page['where'] = $keyword;
		$this->page['whereStatus'] = $keyword_s_status;
		
		$total_rows =$this->contact_model->count($whereData);
		$this->page ['contacts'] = $this->contact_model->getContact ($whereData,($pagenum - 1) * $per_page, $per_page);
		


		
		//分页开始
		$this->load->library('pagination');
		$config['base_url'] = base_url() . 'contact/index/' . $keyword.'/'.$keyword_s_status;
		$config['total_rows'] = $total_rows; //总记录数
		$config['per_page'] = $per_page; //每页记录数
		$config['num_links'] = 9; //当前页码边上放几个链接
		$config['uri_segment'] = 5; //页码在第几个uri上
		$this->pagination->initialize($config);
		$this->page['pages'] = $this->pagination->create_links();
		//分页结束
		
		
		
		$this->page ['head'] = $this->load->view('head', $this->_category, true);
		$this->page ['foot'] = $this->load->view('foot', $this->_category, true);
		$this->load->view ( 'ContactUsForm', $this->page );
	}
	
	
	function updateStatus(){
		$contact_id = $this->input->post ( 'contact_id' );
		$result = $this->contact_model->updateStatus ( $contact_id,$this->user);
		
		if ($result) {
			exit ( json_encode ( array ('success' => true,'message'=>$this->user) ) );
		}else{
			exit ( json_encode ( array ('success' => False) ) );
		}

		
	}
	
	
	
	
	
	
	
}