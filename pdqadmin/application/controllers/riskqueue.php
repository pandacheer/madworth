<?php

//use MaxMind\MinFraud;

/**
 * @文件： riskQueue
 * @编码： utf8
 * @作者： zhujian
 * @emai： 407284071@qq.com
 * 说明：
 */


class Riskqueue extends Pc_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('riskqueue_model');
    }
	
    
    
    function index($id=0) {
    	
    	echo 1;
    	
        //$result = $this->riskqueue_model->getInfo($id);
        
        
        /*
        if (count($result)) {
            //获取需要处理的数据
            require_once 'vendor/autoload.php';
            
            $riskQueue=json_decode($result['data'], true);
            
            
            //风险ID 和 key  收费滴
            $mf = new MinFraud(106097, 'AbektTltHSME');

                $withDevice = json_decode($riskQueue['withDevice'], true);
                $withEmail = json_decode($riskQueue['withEmail'], true);
                $withAddress = json_decode($riskQueue['withAddress'], true);
                $withCreditCard = json_decode($riskQueue['withCreditCard'], true);

                $request = $mf->withDevice([
                            'ip_address' => $withDevice['ip_address'],
                            'user_agent' => $withDevice['user_agent'],
                            'accept_language' => $withDevice['accept_language'],
                        ])->withEmail([
                            'address' => $withEmail['address'],
                            'domain' => $withEmail['domain'],
                        ])->withBilling([
                            'first_name' => $withAddress['first_name'],
                            'last_name' => $withAddress['last_name'],
                            'company' => $withAddress['company'],
                            'address' => $withAddress['address'],
                            'address_2' => $withAddress['address_2'],
                            'city' => $withAddress['city'],
                            'region' => $withAddress['region'],
                            'country' => $withAddress['country'],
                            'postal' => $withAddress['postal'],
                            'phone_number' => $withAddress['phone_number'],
                            'phone_country_code' => $withAddress['phone_country_code'],
                        ])->withShipping([
                    'first_name' => $withAddress['first_name'],
                    'last_name' => $withAddress['last_name'],
                    'company' => $withAddress['company'],
                    'address' => $withAddress['address'],
                    'address_2' => $withAddress['address_2'],
                    'city' => $withAddress['city'],
                    'region' => $withAddress['region'],
                    'country' => $withAddress['country'],
                    'postal' => $withAddress['postal'],
                    'phone_number' => $withAddress['phone_number'],
                    'phone_country_code' => $withAddress['phone_country_code'],
                ]);
                        
                                    

               if ($riskQueue['payType'] == 1) {
                    $request = $request->withCreditCard([
                        'issuer_id_number' => $withCreditCard['issuer_id_number'],
                        'last_4_digits' => $withCreditCard['last_4_digits'],
                        'bank_name' => $withCreditCard['bank_name'],
                        'bank_phone_country_code' => $withCreditCard['bank_phone_country_code'],
                        'bank_phone_number' => $withCreditCard['bank_phone_number'],
                        'avs_result' => $withCreditCard['avs_result'],
                        'cvv_result' => $withCreditCard['cvv_result'],
                    ]);
                }

                # To get the minFraud Insights response model, use ->insights():
                $insightsResponse = $request->insights();

                # To get the minFraud Score response model, use ->score():
                # $scoreResponse = $request->score();
                //得到返回的数据 添加到数据库

                if(empty($insightsResponse->ipAddress->raw['registered_country']['iso_code'])){
                	$payCountry='XX';
                	$ipAddressScore=66.66;
                }else{
                	$payCountry=$insightsResponse->ipAddress->raw['registered_country']['iso_code'];
                	$ipAddressScore=$insightsResponse->ipAddress->risk;
                }
                
                $data = array(
                    'order_number' => $riskQueue['order_number'],
                    'longitude' => $insightsResponse->shippingAddress->longitude,
                    'latitude' => $insightsResponse->shippingAddress->latitude,
                    'payCountry' => $payCountry,
                    'creditCardCountry' => $riskQueue['payType'] == 1 ? $insightsResponse->creditCard->country : 0,
                    'shippingCountry' => $withAddress['country'],
                    'ipAddressScore' => $ipAddressScore,
                    'riskScore' => $insightsResponse->riskScore,
                );
                
                
                if($data['ipAddressScore']>10 || $data['riskScore']>10){
                	$order_risk=3;
                }else if($data['shippingCountry']!= $data['payCountry'] ){
                	$order_risk=2;
                }elseif($data['creditCardCountry'] && $data['creditCardCountry']!= $data['shippingCountry']){
                	$order_risk=2;
                }else{
                	$order_risk=1;
                }
                
                $risk = $this->riskqueue_model->addRisk($withAddress['country'], $data,$order_risk);
                if ($risk) {
                	//添加日志
                	$log_data=array(
                		'country'=>$withAddress['country'],
                		'type'=>'risk',
                		'data'=>$riskQueue['order_number'].'_SUCCESS',
                		'create_time'=>time()
                	);
                	
                	$this->riskqueue_model->addLog($log_data);
                    echo 1;
                } else {
                    //添加日志
                    $log_data=array(
                    	'country'=>$withAddress['country'],
                    	'type'=>'risk',
                    	'data'=>$riskQueue['order_number'].'_ADD_ERROR',
                    	'create_time'=>time()
                    );
                    
                    $this->riskqueue_model->addLog($log_data);
                    echo 0;
                }
            }
            
            */
        }

}


