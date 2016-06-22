<?php

class Orderrefund_model extends CI_Model {
	private $CI;
    function __construct() {
       $this->CI = & get_instance ();
    }
    
 
    //判断是否已经申请过产品
    function getRefund($country,$id){
    	$applyMongo = $this->mongo->{$country . '_refundApply'};
    	$result = $applyMongo->find(array("_id"=>$id), array("_id" => 1,"status"=>1));
    	 
    	return iterator_to_array($result);
    }
    
    
    
    
    //添加退货申请
    function addRefund($country,$data){
    	$refund= $this->CI->mongo->selectCollection ( $country . '_refundApply' );
    	$result = $refund->insert ( $data );
    	
    	if ($result ['ok'] == 1) {
    		return true;
    	} else {
    		return false;
    	}
    }
    
    

    //判断是否已经存在此订单退货次数(一个订单多个获取退货申请)
    function getMoreRefund($country,$order_number){
    	$applyMongo = $this->mongo->{$country . '_refundApply'};
    	return $applyMongo->find(array("order_number"=>$order_number))->count();
    }
    
    
    //判断此订单产品已经申请过退款
    function isRefund($country,$id){
    	$applyMongo = $this->mongo->{$country . '_refundApply'};
    	return $applyMongo->find(array("_id"=>$id))->count();
    }
    
    
    
    
    
    //有同订单投诉的话 修改状态
    function up_equalOrder($country,$order_number){
    	$applyMongo = $this->mongo->{$country . '_refundApply'};
    	$opts = array('upsert'=>0,'multiple'=>1);
    	$result= $applyMongo->update(array("order_number"=>$order_number), array('$set' => array("equal_order" => 2)),$opts);
    	
    	if ($result ['ok'] == 1) {
    		return true;
    	} else {
    		return false;
    	}
    }
    
    

    

}
