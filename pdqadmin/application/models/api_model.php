<?php

class api_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }
    
    
    
    // erp 获取对应订单国家的 州 地址 缩写 province_code
    public function getProvinceCode($country,$province) {
    	$zoneListKey = 'SYS_Zone_' . $country;
    	$result=$this->redis->hashGet($zoneListKey, NULL, 2);
    	return array_search($province,$result);
    }
    
    
    // erp 用过国家代码 获取国家信息
    public function getCountryInfo($country) {
        $this->db->where(array('iso_code_2' => $country, 'status' => 2));
        $this->db->select('name,currency_payment');
        $this->db->limit(1);
        return $this->db->get('country')->row_array();
    }

    // erp  产品sku对比erp_sku
    public function sku_mapping($sku) {
        $this->db->select('erp_sku,erp_quantity');
        $sku = $this->db->get_where('mapping_products', array('sku' => $sku), 1)->row_array();
        return $sku;
    }
    
    
    // erp  产品erp_sku对比sku
    public  function erpSku_mapping($erpSku) {
        $this->db->select('sku');
        $sku = $this->db->get_where('mapping_products', array('erp_sku' => $erpSku), 1)->row_array();
        return $sku;
    }

    // erp 通过订单号 获取订单详情
    public function getOrderDetails($country, $order_number,$field=0) {
    	if($field){
    		$this->db->select($field);
    	}
        return $this->db->get_where($country . '_order_details', array(
                    'order_number' => $order_number
                ))->result_array();
    }

    // erp 通过订单号 获取订单收获地址
    public function getOrderShip($country, $order_number) {
        return $this->db->get_where($country . '_order_ship', array(
                    'order_number' => $order_number
                        ), 1)->row_array();
    }

    // erp 通过订单号 获取订单帐单地址
    public function getOrderBill($country, $order_number) {
        return $this->db->get_where($country . '_order_bill', array(
                    'order_number' => $order_number
                        ), 1)->row_array();
    }

    // erp 通过订单号 获取订单
    public function getOrder($country, $ids, $limit, $page, $since_id, $created_at_min, $created_at_max, $updated_at_min, $updated_at_max, $status, $financial_status, $fulfillment_status,$sort) {
        if ($ids) {
            $this->db->where('order_number', $ids);
        }

        if ($since_id) {
            $this->db->where('order_id >', $since_id);
        }

        if ($created_at_min) {
            $this->db->where('create_time >= ', $created_at_min);
        }

        if ($created_at_max) {
            $this->db->where('create_time <= ', $created_at_max);
        }

        if ($updated_at_min) {
            $this->db->where('update_time >= ', $updated_at_min);
        }

        if ($updated_at_max) {
            $this->db->where('update_time <= ', $updated_at_max);
        }

        if ($status == 'open') {
            $this->db->where('order_status', 1);
        } else if ($status == 'closed') {
            $this->db->where('order_status', 2);
        }

        if ($financial_status == 'paid') {
            $this->db->where('pay_status', 1);
        } else if ($financial_status == 'refunded') {
            $this->db->where('pay_status', 2);
        } else if ($financial_status == 'partially_refunded') {
            $this->db->where('pay_status', 3);
        }

        if ($fulfillment_status == 'shipped') {
            $this->db->where('send_status', 1);
        } else if ($fulfillment_status == 'partial') {
            $this->db->where('send_status', 2);
        } else if ($fulfillment_status == 'unshipped') {
            $this->db->where('send_status', 0);
        }

        if ($limit > 250) {
            $limit = 250;
        }
        
        if($sort){
        	$this->db->order_by('order_id', 'DESC');
        }
        
        
        $this->db->limit($limit, $page);
        return $this->db->get($country . '_order')->result_array();
    }
    
    
    //erp通过订单号 获取订单付款信息
    public function orderPayStatus($country, $order_number) {
    	$this->db->select('pay_status');
    	$payStatus = $this->db->get_where($country . '_order', array(
                    'order_number' => $order_number
                        ), 1)->row_array();

        return $payStatus;
    }
    
    
    

    // erp 通过订单号 获取订单附加信息
    public function getOrderAppend($country, $order_number) {
        return $this->db->get_where($country . '_order_append', array(
                    'order_number' => $order_number
                        ), 1)->row_array();
    }

    // erp 获取用户信息
    public function getMemberInfo($country, $member_email) {
        $member = $this->db->get_where($country . '_member', array(
                    'member_email' => $member_email
                        ), 1)->row_array();

        return $member;
    }

    // erp 获取发货信息
    public function getOrderSend($country, $order_number) {
        $orderSendInfo = $this->db->get_where($country . '_order_send', array(
                    'order_number' => $order_number
                        ), 1)->row_array();
        return $orderSendInfo;
    }

    // erp 判断国家是否合法
    public function exist_country($country) {
        $countryInfoKey = 'SYS_CountryCodeSet';
        return $this->redis->setSearch($countryInfoKey, $country);
    }

    // erp 查询此订单是否存在
    public function exist_order($country, $order_number) {
        $this->db->where('order_number', $order_number);
        return $this->db->count_all_results($country . '_order');
    }
    
    
    //通过订单号获取此订单号的风险程度
    function getRiskByNumber($country_code, $order_number,$fields = 'order_number,longitude,latitude,payCountry,creditCardCountry,shippingCountry,ipAddressScore,riskScore'){
    	$this->db->select($fields);
    	$this->db->where('order_number', $order_number);
    	$this->db->limit(1);
    	return $this->db->get($country_code . '_order_risk')->row_array();
    }
    
    
    //erp 保存错误的发货信息
    public function sendError($data) {
        /* $CI = & get_instance();
        $mongo = 'mongodb://192.168.10.123';
        $m = new MongoClient($mongo);
        $CI->mongo = $m->selectDB('pdq'); */
        $sends = $this->mongo->selectCollection('sendError_log');

        $id = date('Y-m-d H:i:s', time());
        $info = array(
            '_id' => $id,
            'message' => $data
        );
        $result = $sends->insert($info);
        return $result['ok'];
    }

    // erp 添加到api_send表中
    public function addSend($data) {
    	$data = array(
    	    'method' => 'apiSend',
    		'data' => json_encode($data),
    		'create_time' => time(),
    		'level' => 4,
    		'status' => 1
    	);
    	 
       if ($this->db->insert('SYS_queue', $data)) {
           $this->redis->deinc('SYS_queue',1,1);
            return $this->db->insert_id();
        } else {
            return 0;
        }
    }

    // erp 获取发货信息 修改状态
    public function orderSend() {
        $data = $this->db->limit(10)->get('api_send')->result_array();
        if ($data) {
            $time = time();
            foreach ($data as $value) {

                if ($value ['send_status'] == 1) {
                    $doc_status = 2;
                    $log_status = 1;
                    $order_memo = 'Fulfilled';
                } else if ($value ['send_status'] == 2) {
                    $doc_status = 1;
                    $log_status = 1;
                    $order_memo = 'Partially Fulfilled';
                } else if ($value ['send_status'] == 3) {
                    $doc_status = 1;
                    $log_status = 1;
                    $order_memo = 'Dispatched';
                }

                $order = array(
                    'send_status' => $value ['send_status'],
                    'doc_status' => $doc_status,
                    'update_time' => $time,
                    'is_resend' => $value ['is_resend'] + 1
                );

                $order_send = array(
                    'order_number' => $value ['order_number'],
                    'send_status' => $value ['send_status'],
                    'track_name' => $value ['track_name'],
                    'track_code' => $value ['track_code'],
                    'track_url' => $value ['track_url'],
                    'send_bill' => $value ['send_bill'],
                    'send_time' => $value ['send_time'],
                    'logistics' => $value ['track_name'],
                    'is_resend' => $value ['is_resend'] + 1,
                    'create_time' => $time,
                    'operator' => $value ['operator']
                );

                $order_log = array(
                    'order_number' => $value ['order_number'],
                    'order_status' => $log_status,
                    'order_memo' => $order_memo,
                    'create_time' => $time,
                    'operator' => $value ['operator']
                );

                $this->db->trans_begin();
                $this->db->update($value ['country'] . '_order', $order, array('order_number' => $value ['order_number']));
                $this->db->insert($value ['country'] . '_order_send', $order_send);
                $this->db->insert($value ['country'] . '_order_log', $order_log);

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                } else {
                    $this->db->trans_commit();
                    $this->db->delete('api_send', array('id' => $value ['id']));
                }
            }
        }
    }

}
?>

