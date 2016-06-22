<?php

/**
 * @文件： Dashboard_model
 * @时间： 2015-6-24 9:40:16
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：
 */
class Dashboard_model extends CI_Model {
	function __construct() {
		parent::__construct ();
	}
	
	// 获取订单信息mongodb
	function getOrderAmount($countryCode=0, $startTime, $endTime, $field = array('date' => 1,'amount'=>1)) {
		$starDate = ( int ) str_replace ( '-', '', $startTime );
		$endDate = ( int ) str_replace ( '-', '', $endTime );
		
		if($countryCode){
			$where = array (
					'country' => $countryCode,
					'date' => array (
							'$gte' => $starDate,
							'$lte' => $endDate 
					) 
			);
		}else{
			$where = array (
					'date' => array (
							'$gte' => $starDate,
							'$lte' => $endDate
					)
			);
		}
		
		$websiteData = iterator_to_array ( $this->mongo->SYS_Total_website->find ( $where, $field ) );
		
		return $websiteData;
	}
	function getConversionData($countryCode, $startTime, $endTime) {
		$starDate = ( int ) str_replace ( '-', '', $startTime );
		$endDate = ( int ) str_replace ( '-', '', $endTime );
		
		$where = array (
				'country' => $countryCode,
				'date' => array (
						'$gte' => $starDate,
						'$lte' => $endDate 
				) 
		);
		$websiteData = iterator_to_array ( $this->mongo->SYS_Total_website->find ( $where ) );
		$memberData = iterator_to_array ( $this->mongo->SYS_Total_member->find ( $where ) );
		
		$conversion ['websiteData'] = $websiteData;
		$conversion ['memberData'] = $memberData;
		
		return $conversion;
	}
	
	// 获取所有注册用户
	function getUser($countryCode) {
		$where = array (
				'country' => $countryCode 
		);
		$memberData = iterator_to_array ( $this->mongo->SYS_Total_member->find ( $where ) );
		return $memberData;
	}
	
	// 查询销量最高的产品
	function getProductRank($countryCode, $where = array(), $field = array('_id' => 1, 'title' => 1,'sold'=>1)) {
		$collection = $this->mongo->{$countryCode . '_product'};
		$result = $collection->find ( $where, $field )->sort ( array (
				"sold.number" => - 1 
		) );
		return iterator_to_array ( $result );
	}
	
	// 获取指定时间内的销量最高产品
	function getProductRankByDate($countryCode, $startTime, $endTime, $where = array(), $field = array('product' => 1,'sold'=>1,'sku'=>1)) {
		$starDate = ( int ) str_replace ( '-', '', $startTime );
		$endDate = ( int ) str_replace ( '-', '', $endTime );
		
		if($countryCode){
			$where = array (
					'country' => $countryCode,
					'date' => array (
							'$gte' => $starDate,
							'$lte' => $endDate 
					),
					'sold' => array (
							'$gt' => 0,
					)
					
			);
		}else{
			$where = array (
					'date' => array (
							'$gte' => $starDate,
							'$lte' => $endDate
					),
					'sold' => array (
							'$gt' => 0,
					)
						
			);
		}
		
		$collection = $this->mongo->{'SYS_Total_product'};
		$result = $collection->find ( $where, $field )->sort ( array (
				"sold" => - 1 
		) );
		return iterator_to_array ( $result );
	}
	
	// 通过产品ID获取产品信息
	function getProductRankByID($countryCode, $productIds, $field = array('title' => 1,'price' => 1)) {
		$collection = $this->mongo->{$countryCode . '_product'};
		$where = array (
				'_id' => array (
						'$in' => $productIds 
				) 
		);
		$result = $collection->find ( $where, $field );
		return iterator_to_array ( $result );
	}
	
	
	
	//获取客户的使用终端比例
	function getTerminal($countryCode,$whereData){
		$whereData ['terminal']=1;
		$this->db->where($whereData);
		$this->db->where('pay_status >', 0);
		$this->db->from($countryCode.'_order');
		$pc=$this->db->count_all_results();
	
		
		$whereData ['terminal']=2;
		$this->db->where($whereData);
		$this->db->where('pay_status >', 0);
		$this->db->from($countryCode.'_order');
		$mobi=$this->db->count_all_results();
		
		
		$data['pc']=$pc;
		$data['mobi']=$mobi;
		
		return $data;
	}
	
	
	function getShippingType($countryCode,$whereData){
		$whereData ['freight_amount']=0;
		$this->db->where($whereData);
		$this->db->where('pay_status >', 0);
		$this->db->from($countryCode.'_order');
		$standard=$this->db->count_all_results();
		
		
		
		unset($whereData ['freight_amount']);
		$whereData ['freight_amount >']=0;
		$this->db->where($whereData);
		$this->db->where('pay_status >', 0);
		$this->db->from($countryCode.'_order');
		$express=$this->db->count_all_results();
		
		
		$data['standard']=$standard;
		$data['express']=$express;
		
		return $data;
	}
	
	
	
}
