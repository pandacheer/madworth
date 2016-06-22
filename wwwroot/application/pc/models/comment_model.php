<?php

/**
 * @文件： comment_model
 * @时间： 2015-10-30 14:01:55
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：
 */
class comment_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function insert($data) {
        return $this->redis->listPush("Comment_List", json_encode($data),  1,  0);
    }
    
    
    function getInfoByProductId($p_id){
    	$comment = $this->mongo->{'product_comment'};
    	$whereData =array("product_id" =>(string) $p_id,"status" => "2");

    	$commentInfo = $comment->find($whereData)->sort(array("create_time" => -1));
    	return iterator_to_array($commentInfo);
    }
    
    
    function getInfoByCollectionId($c_id){
    	$comment = $this->mongo->{'product_comment'};
    	$whereData =array("collection_id" => /* (int) */$c_id,"status" => "2");
    	
    	$commentInfo = $comment->find($whereData)->sort(array("create_time" => -1));
    	return iterator_to_array($commentInfo);
    }

}
