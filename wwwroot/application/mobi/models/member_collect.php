<?php
 defined('BASEPATH') OR exit('No direct script access allowed');
 

 class Member_collect extends CI_Model {
    protected $CI;
    public function __construct(){
        $this->CI = & get_instance();
    }

    public function insert($country,$data){


    	$countdown=$this->CI->mongo->selectCollection($country.'_member_collect');

    	return $countdown->insert($data);
    }
    public function find($country,$arr){ 

    	$countdown=$this->CI->mongo->selectCollection($country.'_member_collect');
    	return $countdown->findOne($arr);

    }
    public function updata($country,$arr,$datay){
    	$countdown=$this->CI->mongo->selectCollection($country.'_member_collect');

    	$datay=array('$push'=>$datay);
    	$countdown->update($arr,$datay);
    }
    public function findPD($country,$findpd){
    	$countdown=$this->CI->mongo->selectCollection($country.'_member_collect');

    	return $countdown->findOne($findpd);

    }
    public function get($country,$arr){
    	$countdown=$this->CI->mongo->selectCollection($country.'_member_collect');

    	return $countdown->findOne($arr);
    }
    public function delete($country,$arr,$findpd){
    	$countdown=$this->CI->mongo->selectCollection($country.'_member_collect');
    	$find=array('$pull'=>$findpd);
    	return $countdown->update($arr,$find);	
    }
}