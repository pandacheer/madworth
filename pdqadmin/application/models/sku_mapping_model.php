<?php

/**
 *  pagecontent_model
 *  zhujian
 *  cms列表模型
 */
class sku_mapping_model extends CI_Model {
	
  public function __construct() {
    $this->load->database();
  }



  public function count($whereData){
    $this->db->where($whereData);
    return $this->db->count_all_results('mapping_products');
  }
  
  
  
  public function getSku($whereData,$offset = 0, $per_page = 10){
  	$this->db->order_by('id', 'desc');
  	$this->db->limit($per_page, $offset);
    $this->db->where($whereData);
  	return $this->db->get('mapping_products')->result_array();
  }



  public function bySku($sku){
    $this->db->select('sku,erp_sku');
    return $this->db->get_where('mapping_products', array('sku' => $sku),1)->row_array();
  }
  
  public function byErpSku($erp_sku){
  	$this->db->select('erp_sku');
  	return $this->db->get_where('mapping_products', array('erp_sku' => $erp_sku),1)->row_array();
  }


  public function byId($id){
    return $this->db->get_where('mapping_products', array('id' => $id),1)->row_array();
  }
  


  public function add($sku,$erp_sku,$erp_quantity){
      $data=array(
        'sku'=> $sku,
        'erp_sku'=>$erp_sku,
      	'erp_quantity'=>$erp_quantity
      );
      return $this->db->insert('mapping_products',$data);
  }



  public function del($id){
    return $this->db->delete('mapping_products', array('id' => $id));
  }



  public function update($id,$sku,$erp_sku,$erp_quantity){
      $data=array(
        'sku'=> $sku,
        'erp_sku'=>$erp_sku,
      	'erp_quantity'=>$erp_quantity
      );
      return $this->db->update('mapping_products', $data, array('id' => $id));
  }
  
  
  
  
}