<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Collect extends MY_Controller {
  private $id;
    function __construct() {
        parent::__construct();
        $this->load->model('member_collect');
        $this->id=$this->session->userdata('member_id');
    }

    public function index() {
        $country=$this->page['country'];
        $arr=array("_id"=>$this->id);
        $result=$this->member_collect->get($country,$arr);
        var_dump($result);
    }
    public function find(){
    	$product_id='洗发水44';
    	$country=$this->page['country'];
    	if(empty($this->id)){
    		return  0;
    	}
    	
        $findpd=array("_id"=>$this->id,"product_id"=>$product_id);
    	if($this->member_collect->findpd($country,$findpd)){

    		return 1;

    	}else{

    		return 0;
    	}
    }
    public function insert(){
    	
    	$this->load->helper('language');
    	$this->lang->load('sys_collect');
    	
        $data=array();
        $datay=array();
        $country=$this->page['country'];
        //$data['_id']=$this->session->userdata('member_id');
        if (!$this->session->userdata('member_email')){
            exit(json_encode(array('success' => False, 'resultMessage' => lang('collect_login'))));
        }
		$data['_id']=$this->session->userdata('member_id');

        $id=$data['_id'];
        $arr=array("_id"=>$id);
        
        
        $product_id=$this->input->post('product_id',TRUE);

        $findpd=array("_id"=>$id,"product_id"=>$product_id);
        if($this->member_collect->findpd($country,$findpd)){
           exit(json_encode(array('success' => TRUE, 'resultMessage' => lang('collect_Success'))));
        }else{
           if($this->member_collect->find($country,$arr)){ 
                $datay=array('product_id'=>$product_id);
                $this->member_collect->updata($country,$arr,$datay);
                exit(json_encode(array('success' => TRUE, 'resultMessage' => lang('collect_Success')))); 

           }else{
                $data['product_id']=array($product_id);
                if ($this->member_collect->insert($country,$data)){
                    exit(json_encode(array('success' => TRUE, 'resultMessage' => lang('collect_Success'))));
                }else{
                    exit(json_encode(array('success' => False, 'resultMessage' => lang('collect_Error'))));
                }

            }
        }  
    }
    public function delete(){
        $array=array();
        $country=$this->page['country'];
        $id=$this->session->userdata('member_id');
        $product_id='洗发水3';
        $arr=array("_id"=>$id);
        $findpd=array("product_id"=>$product_id);
        $this->member_collect->delete($country,$arr,$findpd);

    }
}
