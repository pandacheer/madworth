<?php

/**
 * @时间： 2016-1-13
* @编码： utf8
* @作者： zhujian

*/
class slide extends MY_Controller {
	public function __construct() {
		parent::__construct();
		
		$this->terminal = $this->session->userdata('isMobile');
        $this->load->model('template_model');
        $headView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'head');
        $this->page['head'] = $this->load->view($headView, $this->page, true);
        $footView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'foot');
        $this->page['foot'] = $this->load->view($footView, $this->page, true);
	}
	
	
	
	function index(){
		$slideView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'slide');
		$this->load->view($slideView, $this->page);
	}
}



?>