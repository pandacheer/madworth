<?php

/**
 *  comment_model
 *  zhujian
 *  评论模型
 */

class comment_model extends CI_Model {
	protected $CI;
	public function __construct(){
		$CI = & get_instance();
		$this->comment=$CI->mongo->selectCollection('product_comment');
	}
	
	
	public function insertComment($data){
		$result = $this->comment->insert ( $data );
		if ($result ['ok'] == 1) {
			return true;
		} else {
			return false;
		}
	}
	
	
	
	public function getComment($whereData,$offset,$per_page = 10){
		$commentInfo = $this->comment->find($whereData)->limit($per_page)->skip($offset)->sort(array("status" => 1,"create_time" => 1));
		return iterator_to_array($commentInfo);
	}
	
	
	
	public function count($whereData){
	  return $this->comment->find($whereData)->count();
	}
	


	public function updateStatus($comment_id,$status,$operator){
		$result=$this->comment->update(array("_id" => $comment_id), array('$set' => array("status" => $status,'operator'=>$operator)));
		if ($result ['ok'] == 1) {
			return true;
		} else {
			return false;
		}
	}
	
	
	
	public function delete($comment_id){
		$result=$this->comment->remove(array("_id" => $comment_id));
		if ($result ['ok'] == 1) {
			return true;
		} else {
			return false;
		}
	}
	
	
}


?>