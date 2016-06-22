<?php

/**
 *  order_model
 *  zhujian
 *  订单模型
 */
class order_model extends CI_Model {

    public function __construct() {
//        $this->load->database();
    }

    //添加订单
    /*
     *   $o_order      [订单数据]
     *   $o_append     [订单附加信息]
     *   $o_bill       [订单帐单信息]
     *   $o_ship       [订单送货地址]
     *   $order_time   [订单进度]
     *   $arr_details  [订单详情]
     */
    public function addOrder($country, $o_order, $o_append, $o_bill, $o_ship, $arr_details) {
        $arr_detailsTmp = array();
        foreach ($arr_details as $arr_detailTmp) {
            $arr_detailTmp['member_id'] = $o_order['member_id'];
            $arr_detailsTmp[] = $arr_detailTmp;
        }
        $this->db->trans_start();
        $this->db->insert($country . '_order', $o_order);
        $order_id = $this->db->insert_id();
        $this->db->insert($country . '_order_append', $o_append);
        $this->db->insert($country . '_order_bill', $o_bill);
        $this->db->insert($country . '_order_ship', $o_ship);
        $this->db->insert_batch($country . '_order_details', $arr_detailsTmp);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return false;
        } else {
            return $order_id;
        }
    }

    //获取前台订单
    public function getOrder($country, $member_id) {
        $this->db->where(array('member_id' => $member_id, 'pay_status >' => 0));
        $this->db->order_by('order_id', 'desc');
        $this->db->select('order_id,order_number,create_time,estimated_time,send_status,pay_status');
        return $this->db->get($country . '_order')->result_array();
    }

    //根据order_id获取订单信息
    function getInfoByID($country_code, $order_id, $fields = 'order_id,order_number,member_id,member_email,member_name,order_quantity,order_amount,payment_amount,offers_amount,coupons_id,order_insurance,order_giftbox,freight_amount,receive_name,create_time,send_status,is_resend,pay_status,doc_status,update_time,pay_type,estimated_time,transaction_id,operator') {
        $sql = "select {$fields} from {$country_code}_order where order_id={$order_id} limit 1";
        return $this->db->query($sql)->row_array();
    }

    //根据order_number获取订单信息
    function getInfoByNumber($country_code, $order_number, $fields = 'order_id,order_number,member_id,member_email,member_name,order_quantity,order_amount,payment_amount,offers_amount,coupons_id,order_insurance,order_giftbox,freight_amount,receive_name,create_time,send_status,is_resend,pay_status,doc_status,update_time,pay_type,estimated_time,transaction_id,operator') {
        $sql = "select {$fields} from {$country_code}_order where order_number={$order_number} limit 1";
        return $this->db->query($sql)->row_array();
    }

    //根据order_number获取订单留言
    function getNoteByNumber($country_code, $order_number, $fields = 'order_guestbook,landing_page,refer_site,order_weight') {
        $sql = "select {$fields} from {$country_code}_order_append where order_number={$order_number} limit 1";
        return $this->db->query($sql)->row_array();
    }

    //支付成功后的操作
    function payment($country_code, $member_id, $member_email, $coupons_id, $orderNumber, $paymentAmount, $transaction_id, $orderCreateTime, $address_id = 0, $bill_address_id = 0) {
        $this->db->trans_start();
        //更新订单状态和支付交易号
        $updateOrderQuery = 'update ' . $country_code . '_order set create_time=' . time() . ',pay_status=1,update_time=' . time() . ',transaction_id=? where order_number=' . $orderNumber;
        $this->db->query($updateOrderQuery, array($transaction_id));
        //更新统计信息
        $updateMemberAnalysisQuery = 'update ' . $country_code . '_member_analysis set member_orders=member_orders+1,last_order=' . $orderCreateTime . ',last_spent=' . $paymentAmount . ',order_spent=order_spent+' . $paymentAmount . ' where member_id=' . $member_id;
        $this->db->query($updateMemberAnalysisQuery);
        if ($coupons_id) {
            $this->load->model('coupons_model');
            $couponInfo = $this->coupons_model->getInfoById($country_code, $coupons_id, 'private,frequency,start,end');
            if ($couponInfo) {
                if ($couponInfo['private'] == 1) {
                    $sql = 'select surplus_times from ' . $country_code . '_coupons_member where member_email=? and coupons_id=? limit 1';
                    $row = $this->db->query($sql, array($member_email, $coupons_id))->row_array();
                    if ($row['surplus_times'] > 0) {
                        $updateCouponsMemberQuery = 'update ' . $country_code . '_coupons_member set surplus_times=surplus_times-1 where member_email=? and coupons_id=?';
                        $this->db->query($updateCouponsMemberQuery, array($member_email, $coupons_id));
                    }
                } else {
                    $sql = 'select surplus_times from ' . $country_code . '_coupons_member where member_email=? and coupons_id=? limit 1';
                    $rows = $this->db->query($sql, array($member_email, $coupons_id))->row_array();
                    if (empty($rows)) {
                        $insertPublicCoupons = 'insert into ' . $country_code . '_coupons_member(member_email,private,coupons_id,surplus_times,start,end) values(?,2,?,?,?,?)';
                        $this->db->query($insertPublicCoupons, array($member_email, $coupons_id, $couponInfo['frequency'] - 1, $couponInfo['start'], $couponInfo['end']));
                    } elseif ($rows['surplus_times'] > 0) {
                        $updateCouponsMemberQuery = 'update ' . $country_code . '_coupons_member set surplus_times=surplus_times-1 where member_email=? and coupons_id=?';
                        $this->db->query($updateCouponsMemberQuery, array($member_email, $coupons_id));
                    }
                }

                $updateCoupons = 'update ' . $country_code . '_coupons set used=used+1 where coupons_id=?';
                $this->db->query($updateCoupons, array($coupons_id));
            }
        }
        $this->db->trans_complete();
        if ($this->db->trans_status()) {
            //更改销售数量
            $sql = 'select product_id,product_sku,total_qty,img_url from ' . $country_code . '_order_details where order_number=' . $orderNumber;
            $rows = $this->db->query($sql)->result_array();
            $mongoTable = $this->mongo->{$country_code . '_product'};
            $haveTotal = [];
            $dateTimePRC = new DateTime('@' . (time() + 28800), new DateTimeZone("PRC"));
            $this->redis->hashInc('T:' . $dateTimePRC->format("Ymd") . ':' . $country_code . ':webSite', 'amount', $paymentAmount);
            $this->redis->hashInc('T:' . $dateTimePRC->format("Ymd") . ':' . $country_code . ':webSite', 'order', 1);
            foreach ($rows as $row) {
                //统计当天产品付款成功次数
                $redisKey = 'T:' . $dateTimePRC->format("Ymd") . ':' . $country_code . ':' . $row ['product_id'];
                $this->redis->hashInc($redisKey, 'sold', $row['total_qty']);
                if (array_search($row ['product_id'], $haveTotal) === false) {
                    $tmp = explode('/', $row['product_sku']);
                    $this->redis->hashSet($redisKey, array('sku' => $tmp[0]));
                    $this->redis->hashInc($redisKey, 'purchase', 1);
                    $this->redis->timeOut($redisKey, 259200);
                    $haveTotal[] = $row ['product_id'];
                }
                //统计当天产品付款成功次数 End
                $mongoTable->update(array('_id' => new MongoId($row['product_id'])), array('$inc' => array('sold.number' => (int) $row['total_qty'], 'sold.total' => (int) $row['total_qty'])));
                
                if($row['img_url']){
                    $this->mongo->selectCollection('SYS_diyimg')->update(array('imgurl' =>$row['img_url']),array('$set'=>array('pay'=>intval(date('Ymd')))));
                }                
                
                $row['country_code'] = $country_code;
                $row['buy_time'] = time();
                $this->redis->listPush("Buy_List", json_encode($row), 0, 0);
                $this->redis->listTrim("Buy_List", 0, 19);
            }
            $sql = 'select receive_firstName,receive_lastName,receive_company,receive_country,receive_province,receive_city,receive_add1,receive_add2,receive_zipcode,receive_phone from ' . $country_code . '_order_ship where order_number=' . $orderNumber . ' limit 1';
            $orderShipInfo = $this->db->query($sql)->row_array();
            //加入地址表
            if (!$address_id) {
                $sql = 'select is_default from ' . $country_code . '_member_receive where member_id=' . $member_id . ' limit 1';
                $row = $this->db->query($sql)->row_array();
                if (!$row) {
                    $orderShipInfo['member_id'] = $member_id;
                    $orderShipInfo['is_default'] = 2;
                    $this->db->insert($country_code . '_member_receive', $orderShipInfo);
                }
            }
            //加入帐单地址表
            if (!$bill_address_id) {
                $sql = 'select receive_firstName,receive_lastName,receive_company,receive_country,receive_province,receive_city,receive_add1,receive_add2,receive_zipcode,receive_phone from ' . $country_code . '_order_bill where order_number=' . $orderNumber . ' limit 1';
                $orderBillInfo = $this->db->query($sql)->row_array();
                $sql = 'select is_default from ' . $country_code . '_member_bill where member_id=' . $member_id . ' limit 1';
                $row = $this->db->query($sql)->row_array();
                if (!$row) {
                    $orderBillInfo['member_id'] = $member_id;
                    $orderBillInfo['is_default'] = 2;
                    $this->db->insert($country_code . '_member_bill', $orderBillInfo);
                }
            }

            $sql = 'select member_firstName from ' . $country_code . '_member where member_id=' . $member_id . ' limit 1';
            $row = $this->db->query($sql)->row_array();
            if ($row['member_firstName'] == '') {
                $this->db->trans_start();
                $sql = "update {$country_code}_member set member_name=?,member_firstName=?,member_lastName=? where member_id=" . $member_id;
                $this->db->query($sql, array($orderShipInfo['receive_firstName'] . ' ' . $orderShipInfo['receive_lastName'], $orderShipInfo['receive_firstName'], $orderShipInfo['receive_lastName']));
                $sql = "update {$country_code}_member_analysis set member_name=? where member_id=" . $member_id;
                $this->db->query($sql, array($orderShipInfo['receive_firstName'] . ' ' . $orderShipInfo['receive_lastName']));
                $this->db->trans_complete();
            }
            return TRUE;
        } else {
            return FALSE;
        }
    }

    //添加风险评估队列表
    /* public function addRiskQueue($data) {
        if ($this->db->insert('order_RiskQueue', $data)) {
            $this->redis->hashInc('SYS_Queue_Counter', 'risk', 1);
            return true;
        } else {
            return false;
        }
    } */
    
    public function addRiskQueue($country_code,$data) {
    	$datas = array(
    			'method' => 'riskqueue',
    			'data' => json_encode($data),
    			'create_time' => time(),
    			'level' => 2,
    			'status' => 1
    	);
    	
    	$this->db->insert('SYS_queue', $datas);
    	$Qdb=$this->db->insert_id();
    	
    	if ($Qdb) {
    		return true;
    	} else {
    		
    		//添加日志
    		$log_data=array(
    				'country'=>$country_code,
    				'type'=>'riskMysql',
    				'data'=>$datas,
    				'create_time'=>time()
    		);
    		
    		$table_name='SYS_log_'.date("Ym");
    		$logMongo = $this->mongo->selectCollection($table_name);
    		$result = $logMongo->insert($log_data);
    		return false;
    	}
    }
    
    
    

}

?>