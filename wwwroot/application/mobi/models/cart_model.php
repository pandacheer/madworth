<?php

/**
 * @作者： zhujian
 * @QQ：   407284071
 * @emai： 407284071@qq.com
 * 说明：获取产品分类
 */
class cart_model extends CI_Model {
	private $CI;
	private $cart;
	public function __construct() {
		$this->CI = & get_instance ();
	}
	
	// 显示购物车
	public function getCart($country, $member_email) {
		$where = array (
				'_id' => $member_email 
		);
		$cart = $this->CI->mongo->selectCollection ( $country . '_cart' );
		return $cart->findOne ( $where, array (
				'info' => true 
		) );
	}
	
	// 添加购物车
	public function addCart($country, $member_email, $arr) {
		
	//可以放外面 记得优化
		$time = time ();
		$cart = $this->CI->mongo->selectCollection ( $country . '_cart' );
		

		//判断购物车是否有这个用户
		if($cart->count ( array ('_id' => $member_email ))){
			$p_sku=$arr ['product_sku'];
			$where = array (
					'_id' => $member_email,
					'info.product_sku' => new MongoRegex("/^$p_sku$/i"),
			);
			//有这个用户的话在判断用户购物车是否有这个商品 ,没有的话直接追加进该用户的购物车
			if ($cart->count ( $where )) {
				$data = array (
						'$inc' => array (
								'info.$.product_qty' => + $arr ['product_qty'],
						),
						'$set' => array (
								'time' => $time
						)
				);
				$result = $cart->update ( $where, $data, array("multiple" => true));
			}else{
				$where = array ('_id' => $member_email);
			    $data = array (
					'$push' => array (
							'info' => $arr 
					),
					'$set' => array (
							'time' => $time 
					) 
			    );
			    $result = $cart->update ( $where, $data );
			}
			
		}else{
		  //没有用户直接加入购物车
			$data = array (
					'_id' => $member_email,
					'info' =>  array ($arr),
					'time' => $time
			);
			$result = $cart->insert ( $data );
		}
		
		if ($result ['ok'] == 1) {
			return true;
		} else {
			return false;
		}
			
	}
	

	// 登录添加购物车
	public function addCart_login($country, $member_email, $arr) {
		$cart = $this->CI->mongo->selectCollection ( $country . '_cart' );
		$time = time ();
		
		if ($cart->count ( array ('_id' => $member_email ) )) {
			foreach ( $arr as $value ) {
				$p_sku=$value ['product_sku'];
				// 查询是否有值, 有值得话删除咯
				$where = array (
						'_id' => $member_email,
						'info' => array (
								'$elemMatch' => array (
										'product_sku' => new MongoRegex("/^$p_sku$/i"),
								) 
						) 
				);
				$data = array (
						'$pull' => array (
								'info' => array (
										'product_sku' => new MongoRegex("/^$p_sku$/i"),
								) 
						) 
				);
				$cart->update ( $where, $data );
			}
			
			$where = array (
					'_id' => $member_email 
			);
			$data = array (
					'$pushAll' => array (
							'info' => $arr 
					),
					'$set' => array (
							'time' => $time 
					) 
			);
			
			$result = $cart->update ( $where, $data );
		} else {
			$data = array (
					'_id' => $member_email,
					'info' => $arr,
					'time' => $time 
			);
			$result = $cart->insert ( $data );
		}
		
		if ($result ['ok'] == 1) {
			return true;
		} else {
			return false;
		}
	}
	
	
	
	//获取购物车数量
	public function getCount($country, $member_email) {
		$where = array ('_id' => $member_email);
		$cart = $this->CI->mongo->selectCollection ( $country . '_cart' );
		return $cart->findOne ( $where, array ('info' => true) );
	}
	
	
	// 修改购物车
	public function updateCart($country, $member_email, $product_sku = 0, $product_qty = 0, $state) {
		$time = time ();
		$cart = $this->CI->mongo->selectCollection ( $country . '_cart' );
		
		$where = array (
				'_id' => $member_email,
				'info' => array (
						'$elemMatch' => array (
								'product_sku' => new MongoRegex("/^$product_sku$/i"),
						) 
				) 
		);
		
		if ($state == 0) {
			
			$data = array (
					'$inc' => array (
							'info.$.product_qty' => + 1 
					),
					'$set' => array (
							'time' => $time 
					) 
			);
			$result = $cart->update ( $where, $data );
		} else if ($state == 1) {
			$where = array (
					'_id' => $member_email,
					'info' => array (
							'$elemMatch' => array (
									'product_sku' => new MongoRegex("/^$product_sku$/i"),
									'product_qty' => array (
											'$gt' => 1 
									)
							) 
					) 
			);
			$data = array (
					'$inc' => array (
							'info.$.product_qty' => - 1 
					),
					'$set' => array (
							'time' => $time 
					) 
			);
			$result = $cart->update ( $where, $data );
		} else {
			
			$data = array (
					'info.$.product_qty' => $product_qty,
					'time' => $time 
			);
			$result = $cart->update ( $where, array (
					'$set' => $data 
			) );
		}
		
		if ($result ['ok'] == 1) {
			return true;
		} else {
			return false;
		}
	}
	
	// 删除购物车
	public function delCart($country, $member_email, $product_sku, $product_bundle, $state) {
		$time = time ();
		$cart = $this->CI->mongo->selectCollection ( $country . '_cart' );
		
		if ($state == 1) {
			if ($product_bundle == 1) {
				
				$where = array (
						'_id' => $member_email,
						'info' => array (
								'$elemMatch' => array (
										'product_sku' => new MongoRegex("/^$product_sku$/i"),
								) 
						) 
				);
				$data = array (
						'$pull' => array (
								'info' => array (
										'product_sku' => new MongoRegex("/^$product_sku$/i"),
								) 
						),
						'$set' => array (
								'time' => $time 
						) 
				);
				
				$result = $cart->update ( $where, $data );
			}
		} else {
			$where = array (
					'_id' => $member_email 
			);
			$result = $cart->remove ( $where );
		}
		
		if ($result ['ok'] == 1) {
			return true;
		} else {
			return false;
		}
	}
}
