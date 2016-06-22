<?php
  /**
   *  @说明  退款订单详情控制器
   *  @作者  zhujian
   *  @qq    407284071
   */
 if (! defined('BASEPATH')) exit('No direct script access allowed');
 class ordersRefundContent extends Pc_Controller{

  public function __construct(){
    parent::__construct();
    parent::_active('refund');
    $this->country = $this->session->userdata('my_country'); 
    $this->load->model('ordersrefundcontent_model');
  }

 
  //显示退款订单详情
  public function index($refund_id=0){
    /*$this->output->enable_profiler(TRUE);*/
    $this->load->helper('form');

    $refund_id=$refund_id;
    $this->page['refund_details']=$this->ordersrefundcontent_model->getRefund_details($this->country,$refund_id);
    //print_r($this->page['refund_details']);exit;

    if(!count($this->page['refund_details'])){ 
      redirect("orderRefundList");
    }
    


    $this->page['refund_bills']=$this->ordersrefundcontent_model->getRefund_bills($this->country,$refund_id);


    $this->page['head'] = $this->load->view('head',$this->_category,true);
    $this->page['foot'] = $this->load->view('foot',$this->_category,true);
    $this->load->view('ordersRefundContent',$this->page);
  }




  //修改退款单状态 并根据情况进行订单状态修改
  public function up_refund_status(){
    $refund_id=$this->input->post('refund_id',TRUE);
    $order_number=$this->input->post('order_number',TRUE);


   
    $operator=$this->session->userdata('user_account');
    $all_amount=$this->ordersrefundcontent_model->refund_sum($this->country,$order_number);
    $payment_amount=$this->ordersrefundcontent_model->get_payment($this->country,$order_number);



    //退款总金额小于订单金额的时候   修改状态这方法到时候可以优化一个方法
    if($all_amount['refund_amount']<$payment_amount['payment_amount']){
       //修改订单状态为部分退款 在同时修改退款状态
       if($payment_amount['pay_status']==1){
          $this->ordersrefundcontent_model->part_refund($this->country,$order_number,$refund_id,$operator);
          redirect("ordersRefundContent/$refund_id");
       //直接修改退款状态 无需修改订单状态 
       }else if($payment_amount['pay_status']==3){
          $this->ordersrefundcontent_model->up_refund($this->country,$refund_id,$operator);
          redirect("ordersRefundContent/$refund_id");
       }
    
    //相等的话 修改订单状态为已退款(全额) 在同时修改退款状态
    }else if($all_amount['refund_amount']==$payment_amount['payment_amount']){
          $this->ordersrefundcontent_model->all_refund($this->country,$order_number,$refund_id,$operator);
          redirect("ordersRefundContent/$refund_id");
    }

    //老熊 在这下面写退款哦哦哦哦哦哦哦哦哦哦

  }



 }

?>