<?php
class Mail extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('mail_model');
    }
    
    public function index(){
        $data = $this->mail_model->getOne();
        if(!empty($data)){
            $this->load->library('email');
            // 因为端口原因，暂时写死发送方，以后再改为 $data[0]['from']  DrGrab Support Team
            $this->email->from('support@drgrab.com',$data[0]['sender']);
            $this->email->to($data[0]['to']);
            $this->email->subject($data[0]['title']);
            $this->email->message($data[0]['content']);
            if($this->email->send()){
                $this->mail_model->remove($data[0]['id']);
            }else{
                $this->email->print_debugger();
                $this->mail_model->error($data[0]['id']);
            }
        }
    }
    
    public function unpaid(){
        // 检查未付款订单
    }
	
	public function birthday(){
		// 检查生日
	}
}