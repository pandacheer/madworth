<?php
  /**
   *  @说明  cms 内容控制器
   *  @作者  zhujian
   *  @qq    407284071
   */
if (! defined('BASEPATH')) exit('No direct script access allowed');
class pageContent extends Pc_Controller{

	public function __construct(){
      parent::__construct();
      parent::_active('pages');
      $this->country = $this->session->userdata('my_country'); 
	}


	public function index(){
		$this->page['head'] = $this->load->view('head',$this->_category,true);
      $this->page['foot'] = $this->load->view('foot',$this->_category,true);
      $this->load->view('pageContentAdd',$this->page);
	}

}



?>