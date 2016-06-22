<?php

/**
 *  contact_model
 *  zhujian
 */

class contact_model extends CI_Model {
	protected $CI;
	public function __construct(){
		$CI = & get_instance();
		$this->contact=$CI->mongo->selectCollection('contact');
	}
	
	
	public function getContact($whereData,$offset,$per_page = 10){
		$commentInfo = $this->contact->find($whereData)->limit($per_page)->skip($offset)->sort(array("status" => 1,"_id" => 1));
		return iterator_to_array($commentInfo);
	}
	
	
	
	public function count($whereData){
		return $this->contact->find($whereData)->count();
	}
	
	
	public function updateStatus($contact_id,$operator){
		$result=$this->contact->update(array("_id" => (int)$contact_id), array('$set' => array("status" => 2,'operator'=>$operator)));
		if ($result ['ok'] == 1) {
			return true;
		} else {
			return false;
		}
	}
	
	
	
	
	
}