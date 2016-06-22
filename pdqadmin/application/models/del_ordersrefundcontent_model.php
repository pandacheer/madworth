<?php  
/**
 *  ordersrefundcontent_model
 *  zhujian
 *  退款订单详情模型
 */
 class ordersrefundcontent_model extends CI_Model{
	public function __construct(){
        $this->load->database();
    }



    //获取退款详情信息
    public function getRefund_details($country,$refund_id){
        $this->db->order_by('refund_id','desc');
        $this->db->where('refund_id',$refund_id);
        return $this->db->get($country.'_refund_details')->result_array();
    }


    //获取退款信息
    public function getRefund_bills($country,$refund_id){
        $this->db->order_by('refund_id','desc');
        $this->db->where('refund_id',$refund_id);
        return $this->db->get($country.'_refund_bills')->row_array();
    }



    //修改订单支付状态(退款)
    public function up_pay_status($country,$status,$order_number){
      $this->db->update($country.'_order',array('pay_status'=>$status),array('order_number'=>$order_number));
      return $this->db->affected_rows();
    }



    //获取退款金额
    public function refund_sum($country,$order_number){
       $this->db->where('order_number',$order_number);
       $this->db->select_sum('refund_amount');
       return $query = $this->db->get($country.'_refund_bills')->row_array();
    }


    //获取订单支付总价
    public function get_payment($country,$order_number){
       $this->db->where('order_number',$order_number);
       $this->db->select('payment_amount,pay_status');
       return $this->db->get($country.'_order')->row_array();
    }



    //单独修改退款表的状态
    public function up_refund($country,$refund_id,$operator){
      $data = array(
            'refund_status'=>2,
            'operator' => $operator,
            'update_time'=>time(),
        );

      $this->db->update($country.'_refund_bills',$data,array('refund_id'=>$refund_id));
      return $this->db->affected_rows();
    }



    //修改订单表的退款状态为部分退款并且修改退款表的状态
    public function part_refund($country,$order_number,$refund_id,$operator){
       $order_data= array(
            'pay_status'=>3,
            'operator' => $operator.'_pay_status_2',
            'update_time'=>time(),
       );

       $refund_data= array(
            'refund_status'=>2,
            'operator' => $operator,
            'update_time'=>time(),
       );


       $this->db->trans_start();
          $this->db->update($country.'_order',$order_data,array('order_number'=>$order_number));
          $this->db->update($country.'_refund_bills',$refund_data,array('refund_id'=>$refund_id));
       $this->db->trans_complete();

       if($this->db->trans_status() === FALSE){
          $this->db->trans_rollback();
          return false;
       }else{
          $this->db->trans_commit();
          return true;
       }
    }






    //修改订单表的退款状态为全部退款并且修改退款表的状态
    public function all_refund($country,$order_number,$refund_id,$operator){
       $order_data= array(
            'pay_status'=>2,
            'operator' => $operator.'_pay_status_2',
            'update_time'=>time(),
       );

       $refund_data= array(
            'refund_status'=>2,
            'operator' => $operator,
            'update_time'=>time(),
       );


       $this->db->trans_start();
          $this->db->update($country.'_order',$order_data,array('order_number'=>$order_number));
          $this->db->update($country.'_refund_bills',$refund_data,array('refund_id'=>$refund_id));
       $this->db->trans_complete();

       if($this->db->trans_status() === FALSE){
          $this->db->trans_rollback();
          return false;
       }else{
          $this->db->trans_commit();
          return true;
       }
    }





}


?>