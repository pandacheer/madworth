<?php

/**
 *  @说明  和erp_sku 映射控制器
 *  @作者  zhujian
 *  @qq    407284071
 */


if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );


class sku_mapping extends Pc_Controller {
	public function __construct() {
		parent::__construct();
		parent::_active('sku_mapping');
		$this->load->model('sku_mapping_model');
		$this->page['head'] = $this->load->view('head', $this->_category, true);
		$this->page['foot'] = $this->load->view('foot', $this->_category, true);
	}
	
	
	
	 public function index() {
	 	$per_page = 20;//每页记录数
	 	
	 	if ($this->input->post()) {
            $pagenum = 1;
            $keyword = $this->input->post('txtKeyWords') ? $this->input->post('txtKeyWords') : 'ALL';
        } else {
            $pagenum = ($this->uri->segment(4) === FALSE ) ? 1 : $this->uri->segment(4);
            $keyword = urldecode($this->uri->segment(3) ? $this->uri->segment(3) : 'ALL');
        }

        if ($keyword != '' and $keyword != 'ALL') {
            $whereData['sku like'] = "%$keyword%";
        } else {
            $whereData = [];
        }

        $this->page['txtKeyWords']=$keyword;
	 	
	 	
	 	$total_rows =$this->sku_mapping_model->count($whereData);
	 	$this->page['sku_list'] =$this->sku_mapping_model->getSku($whereData,($pagenum - 1) * $per_page, $per_page);
	 	

	 	//分页开始
        $this->load->library('pagination');
        $config['base_url'] = base_url() . 'sku_mapping/index/' . $keyword;
        $config['total_rows'] = $total_rows; //总记录数
        $config['per_page'] = $per_page; //每页记录数
        $config['num_links'] = 9; //当前页码边上放几个链接
        $config['uri_segment'] = 4; //页码在第几个uri上
        $this->pagination->initialize($config);
        $this->page['pages'] = $this->pagination->create_links();
        //分页结束
        //搜索条件赋值给前端
        $this->page['where'] = $keyword;
        $this->load->view('sku_mapping.php', $this->page);
	 }
	 
	 
	 
	 public function insert(){
	   $sku = $this->input->post ( 'sku' );
	   $erp_sku = $this->input->post ( 'erp_sku' );
	   $erp_quantity = $this->input->post ( 'erp_quantity' );
	   
       if(empty($sku) || empty($erp_sku)){
       		exit ( json_encode ( array ('success' => False,'resultMessage' => '数据不能为空' ) ) );
       }


       $mapping_Sku=$this->sku_mapping_model->bySku($sku);
       $mapping_ErpSku=$this->sku_mapping_model->byErpSku($erp_sku);
       
       if($mapping_Sku){
       		exit ( json_encode ( array ('success' => False,'resultMessage' => 'SKU:'.$mapping_Sku['sku'].'已存在' ) ) );
       }else if($mapping_ErpSku){
       	    exit ( json_encode ( array ('success' => False,'resultMessage' => 'ERP_SKU:'.$mapping_ErpSku['erp_sku'].'已存在' ) ) );
       }else{
       	    if($this->sku_mapping_model->add($sku,$erp_sku,$erp_quantity)){
       	      	exit ( json_encode ( array ('success' => True,'resultMessage' => '添加成功(●ˇ∀ˇ●)' ) ) );	
       	    }else{
       	    	exit ( json_encode ( array ('success' => False,'resultMessage' => '添加失败T-T' ) ) );	
       	    }
       }

	 }



	 public function del(){
	 	$id = $this->input->post ( 'id' );
        if($this->sku_mapping_model->del($id)){
        	exit ( json_encode ( array ('success' => True,'resultMessage' => '删除成功(●ˇ∀ˇ●)' ) ) );	
        }else{
       	    exit ( json_encode ( array ('success' => False,'resultMessage' => '删除失败T-T' ) ) );	
       	}
	 }



	 public function edit($id = 0){
	 	$this->page['mapping_info']=$this->sku_mapping_model->byId($id);
	 	$this->load->view('sku_mapping_edit', $this->page);
	 }



	 public function update(){
	   $id = $this->input->post ( 'id' );
	   $sku = $this->input->post ( 'sku' );
	   $erp_sku = $this->input->post ( 'erp_sku' );
	   $erp_quantity = $this->input->post ( 'erp_quantity' );
       
       if(empty($sku) || empty($erp_sku)){
       		exit ( json_encode ( array ('success' => False,'resultMessage' => '数据不能为空' ) ) );
       }


       //$mapping_Sku=$this->sku_mapping_model->bySku($sku);
       //$mapping_ErpSku=$this->sku_mapping_model->byErpSku($erp_sku);
       
       
	   /* if($mapping_Sku){
       		exit ( json_encode ( array ('success' => False,'resultMessage' => 'SKU:'.$mapping_Sku['sku'].'已存在' ) ) );
       }else if($mapping_ErpSku){
       	    exit ( json_encode ( array ('success' => False,'resultMessage' => 'ERP_SKU:'.$mapping_ErpSku['erp_sku'].'已存在' ) ) );
       }else{
       	    if($this->sku_mapping_model->update($id,$sku,$erp_sku)){
       	      	exit ( json_encode ( array ('success' => True,'resultMessage' => '修改成功(●ˇ∀ˇ●)' ) ) );	
       	    }else{
       	    	exit ( json_encode ( array ('success' => False,'resultMessage' => '修改失败T-T' ) ) );	
       	    }
       } */
       
       if($this->sku_mapping_model->update($id,$sku,$erp_sku,$erp_quantity)){
       	exit ( json_encode ( array ('success' => True,'resultMessage' => '修改成功(●ˇ∀ˇ●)' ) ) );
       }else{
       	exit ( json_encode ( array ('success' => False,'resultMessage' => '修改失败T-T' ) ) );
       }
       
       
       
	 }
	 
	 
}



?>
