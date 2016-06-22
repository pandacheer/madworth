<?php
class subscriptionlist_model extends CI_Model {
	protected $CI;
	public function __construct() {
		$this->CI = &get_instance ();
	}
	public function insert($country, $post) {
		$countdown = $this->CI->mongo->selectCollection ( $country . '_subscription' );
		return $countdown->insert ( $post );
	}
	public function findOne($country, $post) {
		$countdown = $this->CI->mongo->selectCollection ( $country . '_subscription' );
		return $countdown->findOne ( array (
				'_id' => $post 
		) );
	}
	function listData($country_code = 'AU', $whereData = array(), $fields = array(), $offset = 0, $per_page = 10) {
		$collection = $this->mongo->{$country_code . '_subscription'};
		if ($per_page == 'ALL') {
			return $collection->find ( $whereData, $fields )->sort ( array (
					'create_time' => - 1 
			) );
		} else {
			return $collection->find ( $whereData, $fields )->sort ( array (
					'create_time' => - 1 
			) )->limit ( $per_page )->skip ( $offset );
		}
	}
	public function count($country) {
		$countdown = $this->CI->mongo->selectCollection ( $country . '_subscription' );
		return $countdown->count ();
	}
	function count1($country_code = 'AU', $whereData = array()) {
		$collection = $this->mongo->{$country_code . '_subscription'};
		return $collection->find ( $whereData )->count ();
	}
	public function limit($country, $offset, $page) {
		$arr = array ();
		$countdown = $this->CI->mongo->selectCollection ( $country . '_subscription' );
		return $countdown->find ()->sort ( array (
				'create_time' => - 1 
		) )->limit ( $page )->skip ( $offset );
	}
	public function delete($country, $id) {
		$countdown = $this->CI->mongo->selectCollection ( $country . '_subscription' );
		return $countdown->remove ( array (
				'_id' => $id 
		) );
	}
	public function datepicker($country, $datepicker1, $datepicker2, $offset, $page) {
		$countdown = $this->CI->mongo->selectCollection ( $country . '_subscription' );
		$datepicker1 = new MongoInt32 ( $datepicker1 );
		$datepicker2 = new MongoInt32 ( $datepicker2 );
		return $countdown->find ( array (
				"create_time" => array (
						'$gt' => $datepicker1,
						'$lt' => $datepicker2 
				) 
		) )->sort ( array (
				'create_time' => - 1 
		) )->skip ( $offset )->limit ( $page );
		// return $countdown->find(array("create_time" => array('$gt' => $datepicker1,'$lt' => $datepicker2)))->sort(array('create_time'=>-1))->skip($offset)->limit($page);
	}
	public function datepickerCount($country, $datepicker1, $datepicker2) {
		$countdown = $this->CI->mongo->selectCollection ( $country . '_subscription' );
		$datepicker1 = new MongoInt32 ( $datepicker1 );
		$datepicker2 = new MongoInt32 ( $datepicker2 );
		return $countdown->count ( array (
				"create_time" => array (
						'$gt' => $datepicker1,
						'$lt' => $datepicker2 
				) 
		) );
	}
}

