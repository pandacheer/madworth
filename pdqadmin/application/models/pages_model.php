<?php

/**
 *  pagecontent_model
 *  zhujian
 *  cms列表模型
 */
class pages_model extends CI_Model {
  protected $CI;

  public function __construct() {
    $this->CI = & get_instance();
  }


  //获取所在国家所有的pages
  public function getPages($country,$offset = 0, $per_page = 10){
  	$pages=$this->CI->mongo->selectCollection('pages');
    $where = array('country' => $country);
    return $pages->find($where)->limit($per_page)->skip($offset)->sort(array("update_time" => -1));
  }



  //获取所在国家所有的pages总数量
  public function pagesCount($country){
   $pages=$this->CI->mongo->selectCollection('pages');
   $where = array('country' => $country);
   return $pages->count($where);
  }
}