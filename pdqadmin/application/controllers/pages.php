<?php
  /**
   *  @说明  cms控制器
   *  @作者  zhujian
   *  @qq    407284071
   */
if (! defined('BASEPATH')) exit('No direct script access allowed');
class pages extends Pc_Controller{

	public function __construct(){
      parent::__construct();
      parent::_active('pages');
      $this->load->model('pages_model'); 
      $this->country = $this->session->userdata('my_country'); 
	}


	public function index(){
      $per_page = 10;//每页记录数
      if ($this->input->post()) {
        $pagenum = 1;
      } else {
        $pagenum = ($this->uri->segment(3) === FALSE ) ? 1 : $this->uri->segment(3);
        $keyword = urldecode($this->uri->segment(4));
      }

      $total_rows =$this->pages_model->pagesCount($this->country);
      $this->page['pages_list'] =$this->pages_model->getPages($this->country,($pagenum - 1) * $per_page, $per_page);

      //分页开始
      $this->load->library('pagination');
      $config['base_url'] = base_url() . 'pages/index/' . $keyword;
      $config['total_rows'] = $total_rows; //总记录数
      $config['per_page'] = $per_page; //每页记录数
      $config['num_links'] = 2; //当前页码边上放几个链接
      $config['uri_segment'] = 3; //页码在第几个uri上
      $this->pagination->initialize($config);
      $this->page['pages'] = $this->pagination->create_links();
      //分页结束



	   $this->page['head'] = $this->load->view('head',$this->_category,true);
      $this->page['foot'] = $this->load->view('foot',$this->_category,true);
      $this->load->view('pagesList',$this->page);
	}


   

}



?>