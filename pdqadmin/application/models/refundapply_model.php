<?php

/**
 *  refundApply_model
 *  zhujian
 *  退款申请模型
 */
class refundApply_model  extends CI_Model{
    

    public function __construct() {
    	parent::__construct();
    }
    
    
    //获取退货申请列表
    public function getInfoApply($country,$whereData,$offset,$per_page = 10){
    	$applyMongo = $this->mongo->{$country . '_refundApply'};
     	$refundApply = $applyMongo->find($whereData)->limit($per_page)->skip($offset)->sort(array("status" => 1,"create_time" => 1));
    	return iterator_to_array($refundApply);
    }
    
    
    //总数量
    public function count($country,$whereData){
    	$applyMongo = $this->mongo->{$country . '_refundApply'};
    	return $applyMongo->find($whereData)->count();
    }
    
    
    
    //根据id获取数据
    public function getInfoById($country,$id){
    	$applyMongo = $this->mongo->{$country . '_refundApply'};
    	$refundApplyDetails = $applyMongo->findOne(array("_id" => $id));
    	return $refundApplyDetails;
    }
    
    
    //修改状态
    public function updateStatus($country,$id,$operator){
    	$applyMongo = $this->mongo->{$country . '_refundApply'};
    	$result=$applyMongo->update(array("_id" => $id), array('$set' => array("status" => 2,"operator"=>$operator)));
    	
    	if ($result ['ok'] == 1) {
    		return true;
    	} else {
    		return false;
    	}

    }

}

?>