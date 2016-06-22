<?php  
/**
 *  ordersContent_model
 *  zhujian
 *  订单详情模型
 */
 class orderscontent_model extends CI_Model{

    protected $CI;
    public function __construct(){
        $this->CI = & get_instance();
    }

     

     //通过订单号 获取订单详情
	public function getOrderDetails($country,$order_number,$fields="details_id,member_id,order_number,product_id,product_name,product_sku,product_attr,payment_price,product_quantity,payment_amount,bundle_skus,total_qty,bundle_type,comments_star"){
		$this->db->select($fields);
		return $this->db->get_where($country.'_order_details', array('order_number' => $order_number))->result_array();
	}
	
	
	//通过id号 获取订单详情
	public function getOrderDetailsById($country,$id){
		return $this->db->get_where($country.'_order_details', array('details_id' => $id),1)->row_array();
	}



     //通过订单号 获取订单收获地址
	public function getOrderShip($country,$order_number){
        return $this->db->get_where($country.'_order_ship', array('order_number' => $order_number),1)->row_array();
	}


    //通过订单号 获取订单帐单地址
    public function getOrderBill($country,$order_number){
        return $this->db->get_where($country.'_order_bill', array('order_number' => $order_number),1)->row_array();
    }


     //通过订单号 获取订单附加信息
	public function getOrderAppend($country,$order_number,$fields='order_number,order_guestbook,landing_page,refer_site,order_weight'){
		$this->db->select($fields);
        return $this->db->get_where($country.'_order_append', array('order_number' => $order_number),1)->row_array();
	}


    //通过订单号 获取退款详情信息
    public function getOrderRefundDetail($country,$order_number){
        $this->db->where ( array ('order_number' => $order_number,'refund_status !=' => 3));
        $this->db->select('refund_id');
        $refund_id=$this->db->get($country.'_refund_bills')->result_array();
        
        $r_id=array();
        foreach($refund_id as $id){
            $r_id[]=$id['refund_id'];
        }
        
        if(!count($r_id)){
        	$r_id=0;
        }
        
        
        $this->db->where_in('refund_id', $r_id);
        $this->db->select('product_sku,product_attr,refund_quantity');

        return $this->db->get($country.'_refund_details')->result_array();
    }




    //通过订单号获取订单备注
    public function getOrderMemo($country,$order_number){
        $order_message=$this->CI->mongo->selectCollection($country.'_order_message');

        $where = array('_id' => $order_number);
        $message = $order_message->findOne($where);
        
        return $message['message'];
    }

     
     //后台管理员添加订单备注
    public function addOrderMemo($country,$order_number,$memo,$user){
        $message=$memo.','.date('Y-m-d H:i:s',time()).','.$user;
        $order_message=$this->CI->mongo->selectCollection($country.'_order_message');
        
        if($order_message->count(array('_id'=>$order_number))){
            $where=array('_id' => $order_number);
            $data = array(
                '$push'=>array('message'=>$message),
            );
            $result= $order_message->update($where,$data);
        }else{
            $data = array(
                '_id'  => $order_number,
                'message' => array($message),
            );
            $result=$order_message->insert($data);
        }
        
        return $result['ok'];
    }



     //修改收获地址
    public function editOrderShip($country,$order_number,$data){
        return $this->db->update($country.'_order_ship', $data, array('order_number' => $order_number));
    }



    //获取订单日志信息
    public function getOredrLog($country,$order_number){
       $this->db->where('order_number',$order_number);
       $this->db->order_by('create_time', 'desc');
       $this->db->select('order_status,order_memo,create_time,operator');
       return $this->db->get($country.'_order_log')->result_array();
    }


    
    //获取发货的信息
    public function getSend($country,$order_number,$is_resend){
      $this->db->where('order_number',$order_number);
      if($is_resend){
        $this->db->where('is_resend',$is_resend);
        $this->db->select('send_status,track_code,track_url,is_resend');
      }else{
        $this->db->where_not_in('send_status',3);
        $this->db->select('send_bill,send_time,track_code,logistics');
      }
      
      return $this->db->get($country.'_order_send')->result_array();
    }
    

    //获取投诉的发货信息
    public function complaint_send($country,$send_bill,$order_number){
        $this->db->where(array('send_bill'=>$send_bill,'order_number'=>$order_number));
        $this->db->select('send_time,track_code,logistics');
        $this->db->limit(1);

        return $this->db->get($country.'_order_send')->row_array();
    }

    

    //添加投诉信息
    public function addComplaints($country,$complaint){
      return $this->db->insert($country.'_order_complaints',$complaint);
    }


    //获取投诉信息
    public function getComplaints($country,$order_number){
        $this->db->where('order_number',$order_number);
        $this->db->select('complaints_id,send_bill,send_time,dispose');
        return $this->db->get($country.'_order_complaints')->result_array();
    }



    
    //修改为重寄
    public function addRedirect($country,$order_number,$user){
      $sql = 'update '.$country.'_order set is_resend=is_resend+1,send_status=0,doc_status=1 where order_number='.$order_number.'';
      return  $this->db->query($sql);
    }




     //修改账单地址
    public function editOrderBill($country,$order_number,$data){
        return $this->db->update($country.'_order_bill', $data, array('order_number' => $order_number));
    }



    //修改归档地址
    public function updateArchive($country,$order_number,$archive,$user){
        $data = array(
            'doc_status' => $archive,
            'update_time'=>time(),
            'operator'=>$user.'_doc_status'
        );

        return $this->db->update($country.'_order', $data, array('order_number' => $order_number));
    }
    
    
    
    //修改订单状态
    public function updateStatus($country,$order_number,$order_status,$user){
    	$data = array(
    			'order_status' => $order_status,
    			'update_time'=>time(),
    			'operator'=>$user.'_order_status'
    	);
    
    	return $this->db->update($country.'_order', $data, array('order_number' => $order_number));
    }





     
     //修改发货状态 
    public function send_success($country,$order_number,$order_data,$send_data,$order_log){
        /*//先查询
        $this->db->where('order_number',$order_number);
        $this->db->select('track_code,track_url,doc_status,send_status');
        $order = $this->db->get($country.'_order')->row_array();


        if($send_status==1){
            $doc_status=2;
            $order_memo='Fulfilled';
            $track_code=$order['track_code'].','.$express_code;
            $track_url=$order['track_url'].','.$express_url;
        }else if($send_status==2){
            $order_memo='Partially Fulfilled';
            $doc_status=$order['doc_status'];
            $track_code=$order['track_code'].','.$express_code;
            $track_url=$order['track_url'].','.$express_url;
        }else if($send_status==3){
            $order_memo='Dispatched';
            $doc_status=$order['doc_status'];
            $track_code=$order['track_code'];
            $track_url=$order['track_url'];
        }


        $data = array(
            'send_status' =>$send_status,
            'doc_status'=>$doc_status,
            'update_time'=>$time,
            'operator'=>$user.'_send_status_'.$send_status,
            'track_code'=>$track_code,
            'track_url'=>$track_url,
        );*/
      
        $this->db->trans_begin();
            $this->db->update($country.'_order',$order_data, array('order_number' => $order_number));
            $this->db->insert($country.'_order_send',$send_data);   
            $this->db->insert($country.'_order_log',$order_log); 


        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }else{
            $this->db->trans_commit();
            return true;
        }
    }

 

    /*//修改订单支付状态(退款)
    public function up_pay_status($country,$status,$order_number){
      $this->db->update($country.'_order',array('pay_status'=>$status),array('order_number'=>$order_number));
      return $this->db->affected_rows();
    }*/
    
    
    


 }

?>

