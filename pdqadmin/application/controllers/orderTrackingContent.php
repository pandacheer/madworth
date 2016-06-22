<?php
  /**
   *  @说明  订单投诉详情控制器
   *  @作者  zhujian
   *  @qq    407284071
   */
if (! defined('BASEPATH')) exit('No direct script access allowed');
class orderTrackingContent extends Pc_Controller{
    public function __construct(){
      parent::__construct();
      parent::_active('complaints');
      $this->country = $this->session->userdata('my_country');
      $this->user = $this->session->userdata('user_account');
      $this->load->model('ordertracking_model');
    }

    //显示信息
    public function index($complaints_id=0){
      $this->load->helper('form');

      $complaints_id=$complaints_id;
      $this->page['complaintsDetails']=$this->ordertracking_model->getComplaintsDetails($this->country,$complaints_id);
      if(!count($this->page['complaintsDetails'])){ 
         redirect("orderTracking");
      }
      
      //获取产品
      $this->load->model('orderscontent_model');
      $this->page['order_detail']=$this->orderscontent_model->getOrderDetails($this->country,$this->page['complaintsDetails']['order_number']);



      $this->page['head'] = $this->load->view('head',$this->_category,true);
      $this->page['foot'] = $this->load->view('foot',$this->_category,true);
      $this->load->view('orderTrackingContent',$this->page);
    }



     
    //修改投诉信息
    public function updateComplaints(){
      $complaints_id=$this->input->post('complaints_id',TRUE);
      $dispose=$this->input->post('dispose',TRUE);

      if($dispose==5){
        $coupon=$this->input->post('coupon',TRUE);
      }else{
        $coupon=0;
      }
      

      if($dispose==0 || $dispose==1 || $dispose==5){
        $refund_amount=0;
      }else{
        $refund_amount=$this->input->post('refund_amount',TRUE);
      }


      $data= array(
         'question_type'  => $this->input->post('question_type',TRUE),
         'department'     => $this->input->post('department',TRUE),
         'question_remark'=> $this->input->post('question_remark',TRUE),
         'dispose'        => $dispose,
         'coupon'         => $coupon,
         'refund_amount'  => $refund_amount,
         'refund_remark'  => $this->input->post('refund_remark',TRUE),
         'update_time'    => time(),
         'operator'       => $this->user 
      );


      if($this->ordertracking_model->updateComplaints($this->country,$data,$complaints_id)){
         redirect("orderTrackingContent/$complaints_id");
      }
     
      

    }





}


?>