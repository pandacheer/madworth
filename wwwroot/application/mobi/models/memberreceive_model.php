<?php

/**
 * @文件： memberReceive_model
 * @时间： 2015-6-17 15:15:22
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：会员地址表
 */
class memberReceive_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /*     * **********************************************
     * 前端调用模块
     * ************************************************ */

    //根据会员ID获取会员收货地址列表
    //字段：receive_id,member_id,receive_name,receive_company,receive_country,receive_province,receive_city,receive_add1,receive_add2,receive_zipcode,receive_phone,is_default
    function listAddsByMbId($country_code, $member_id, $fields = 'receive_id,receive_firstName,receive_lastName,receive_company,receive_country,receive_province,receive_city,receive_add1,receive_add2,receive_zipcode,receive_phone,is_default') {
        $sql = "select $fields from {$country_code}_member_receive where member_id=$member_id order by is_default desc, receive_id desc";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    
    //根据会员ID获取会员账单地址列表
    //字段：receive_id,member_id,receive_name,receive_company,receive_country,receive_province,receive_city,receive_add1,receive_add2,receive_zipcode,receive_phone,is_default
    function getBillAddressById($country_code, $member_id, $fields = 'receive_id,receive_firstName,receive_lastName,receive_company,receive_country,receive_province,receive_city,receive_add1,receive_add2,receive_zipcode,receive_phone,is_default') {
    	$sql = "select $fields from {$country_code}_member_bill where member_id=$member_id order by is_default desc, receive_id desc";
    	$query = $this->db->query($sql);
    	return $query->result_array();
    }
    
    
    

    //根据ID获取收货地址信息
    function getInfoById($country_code, $member_id, $receive_id, $fields = 'receive_id,member_id,receive_firstName,receive_lastName,receive_company,receive_country,receive_province,receive_city,receive_add1,receive_add2,receive_zipcode,receive_phone,is_default') {
        $sql = "select {$fields} from {$country_code}_member_receive where receive_id={$receive_id} limit 1";
        $query = $this->db->query($sql);
        $row = $query->row_array();
        if ($row['member_id'] == $member_id) {
            unset($row['member_id']);
            return $row;
        } else {
            return false;
        }
    }
    
    
    
    //根据ID获取账单地址信息
    function getBillInfoById($country_code, $member_id, $receive_id, $fields = 'receive_id,member_id,receive_firstName,receive_lastName,receive_company,receive_country,receive_province,receive_city,receive_add1,receive_add2,receive_zipcode,receive_phone,is_default') {
    	$sql = "select {$fields} from {$country_code}_member_bill where receive_id={$receive_id} limit 1";
    	$query = $this->db->query($sql);
    	$row = $query->row_array();
    	if ($row['member_id'] == $member_id) {
    		unset($row['member_id']);
    		return $row;
    	} else {
    		return false;
    	}
    }
    


    function insert($country_code, $data,$count='') {
        if($count<5){
            if ($count){
                return $this->db->insert($country_code . '_member_receive', $data);
            } else {
                $data['is_default']=2;
                return $this->db->insert($country_code . '_member_receive', $data);
        }
        } else {
            return false;
        }
    }
    
    
    
    function BillAddressinsert($country_code, $data,$count='') {
    	if($count<5){
    		if ($count){
    			return $this->db->insert($country_code . '_member_bill', $data);
    		} else {
    			$data['is_default']=2;
    			return $this->db->insert($country_code . '_member_bill', $data);
    		}
    	} else {
    		return false;
    	}
    }
    
    
    
    

    function delete($country_code, $member_id, $receive_id) {
        return $this->db->delete($country_code . '_member_receive', array('receive_id' => $receive_id, 'member_id' => $member_id));
    }
    
    //删除账单地址
    function deleteBill($country_code, $member_id, $receive_id) {
    	return $this->db->delete($country_code . '_member_bill', array('receive_id' => $receive_id, 'member_id' => $member_id));
    }
    
    
    
    

    function update($country_code, $member_id, $receive_id, $data) {
        $this->db->where('receive_id', $receive_id);
        $this->db->where('member_id', $member_id);
        return $this->db->update($country_code . '_member_receive', $data);
    }
    
    
    //修改账单地址
    function updateBillAddress($country_code, $member_id, $receive_id, $data) {
    	$this->db->where('receive_id', $receive_id);
    	$this->db->where('member_id', $member_id);
    	return $this->db->update($country_code . '_member_bill', $data);
    }
    
    

		// 设置默认地址
	public function addressDefault($country,$member_id,$receive_id) {
		$sql = "update {$country}_member_receive set is_default=1 where member_id={$member_id}";
		if($this->db->query($sql)){
			$this->db->update($country . '_member_receive', array('is_default' => 2), array('receive_id' => $receive_id));
			return $this->db->affected_rows();
		}
	}
	
	
	// 设置账单默认地址
	public function billAddressDefault($country,$member_id,$receive_id) {
		$sql = "update {$country}_member_bill set is_default=1 where member_id={$member_id}";
		if($this->db->query($sql)){
			$this->db->update($country . '_member_bill', array('is_default' => 2), array('receive_id' => $receive_id,'member_id'=>$member_id));
			return $this->db->affected_rows();
		}
	}
	
	
	
	
	
    public function count($country, $member_id){
        $this->db->where('member_id',$member_id);
        $this->db->from($country.'_member_receive');
        return $this->db->count_all_results();
    }
    
    
    //账单地址数量
    public function billCount($country, $member_id){
    	$this->db->where('member_id',$member_id);
    	$this->db->from($country.'_member_bill');
    	return $this->db->count_all_results();
    }
    
    

}
