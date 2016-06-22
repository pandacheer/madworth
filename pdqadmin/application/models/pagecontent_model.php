<?php

/**
 *  pagecontent_model
 *  zhujian
 *  cms内容模型
 */
class pagecontent_model extends CI_Model {
  protected $CI;

  public function __construct() {
    $this->CI = & get_instance();
  }


  //添加内容
  public function addPages($data){
    $pages=$this->CI->mongo->selectCollection('pages');
    return $pages->insert($data);
  }

  
  //id 获取内容
  public function getPagesContent($id){
    $pages=$this->CI->mongo->selectCollection('pages');
    $where = array('_id' => new MongoInt32($id));
    return $pages->findOne($where);
  }


  //修改内容
  public function updatePages($id,$data){
  	$pages=$this->CI->mongo->selectCollection('pages');
  	$newdata = array('$set' => $data);
  	$where = array('_id' => new MongoInt32($id));
    return $pages->update($where,$newdata);
  }



  //删除内容
  public function delPages($id){
  	$pages=$this->CI->mongo->selectCollection('pages');
  	$where = array('_id' => new MongoInt32($id));
  	return $pages->remove($where);
  }



}