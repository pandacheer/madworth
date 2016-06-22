<?php

/**
 * @时间： 2015-12-11
 * @编码： utf8
 * @作者： zhujian

 */
class contact extends MY_Controller {
	
	public function addContact(){
		
		$this->load->helper('language');
		$this->lang->load('sys_contact');
		
		
		$contack_type=$this->input->post ( 'contack_type', TRUE );
		$email=$this->input->post ( 'email', TRUE );
		$contents=$this->input->post ( 'content', TRUE );
		$content = str_replace(array("\n", "\r\n") , "<br/>", $contents);

		if($contack_type!=5 && $contack_type!=6 && $contack_type!=7){
			exit(json_encode(array('success' => false,'resultMessage' => lang('error'))));
		}
		
		if(empty($email)){
			exit(json_encode(array('success' => false,'resultMessage' => lang('email_empty'))));
		}

		if(empty($content)){
			exit(json_encode(array('success' => false,'resultMessage' => lang('content_empty'))));
		}
		
		
		if(strlen($content)>1000){
			exit(json_encode(array('success' => false,'resultMessage' => lang('content_error'))));
		}
		
		
		if ($this->session->userdata('Verification')) {
        	$verifySession = $this->session->userdata('Verification');
        	$verifySession['clickTimes'] += 3;
            $this->session->set_userdata('Verification', $verifySession);
        } else {
            $this->session->set_userdata('Verification', array('clickTimes' => 3, 'verifyCode' => ''));
        }
        
        if ($this->session->userdata('Verification')['clickTimes'] > 3) {//对验证码
        	if (strtolower($this->input->post('verifyCode',TRUE)) != $this->session->userdata('Verification')['verifyCode']) {
            	exit(json_encode(array('success' => FALSE, 'clickTimes' => $this->session->userdata('Verification')['clickTimes'], 'resultMessage' => lang('code_error'))));
            }
        }
        
        
		$data=array(
			'_id'=>time(),
			'country_code' => $this->page['country'],
			'contack_type'=>$contack_type,
			'email'=>$email,
			'content'=>$content,
			'status'=>1,
		);
		
		
		$contactMongo = $this->mongo->{'contact'};
		$result = $contactMongo->insert($data);
		
		if ($result ['ok'] == 1) {
			exit(json_encode(array('success' => true)));
		} else {
			exit(json_encode(array('success' => false,'resultMessage' => lang('add_error'))));
		}
	
	}
	
}