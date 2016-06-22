<?php
class home extends Pc_Controller {
	public function __construct() {
		parent::__construct ();
		parent::_active ( 'dashboard' );
		$this->country = $this->session->userdata ( 'my_country' );
	}
	public function index() {
		$this->load->model ( 'order_model' );
		$this->load->model ( 'dashboard_model' );
		$this->load->model ( 'collection_model' );
		$this->load->model ( 'orderscontent_model' );
		
		$startTime = $this->input->post ( 'startTime' ) ? $this->input->post ( 'startTime' ) : date ( "Y-m-d" );
		$endTime = $this->input->post ( 'endTime' ) ? $this->input->post ( 'endTime' ) : date ( "Y-m-d" );
		
		// 组装折线图
		if ($this->input->post ( 'startTime' )) {
			// mongodb 组装有条件折线图
			$lineChartData = $this->dashboard_model->getOrderAmount ( $this->country, $startTime, $endTime );
		} else {
			// mongodb 组装无条件折线图
			$startTime2 = date ( "Y-m-d", strtotime ( "-6 day" ) );
			$lineChartData = $this->dashboard_model->getOrderAmount ( $this->country, $startTime2, $endTime );
		}
		
		// 转化率
		$conversionData = $this->dashboard_model->getConversionData ( $this->country, $startTime, $endTime );
		$conversions = array ();
		foreach ( $conversionData ['memberData'] as $memberData ) {
			@$conversions ['reg'] += $memberData ['reg'];
			@$conversions ['autoreg'] += $memberData ['autoreg'];
		}
		
		foreach ( $conversionData ['websiteData'] as $websiteData ) {
			@$conversions ['order'] += $websiteData ['order'];
			@$conversions ['click'] += $websiteData ['click'];
			@$conversions ['addtocart'] += $websiteData ['addtocart'];
			@$conversions ['checkout'] += $websiteData ['checkout'];
			@$conversions ['pay'] += $websiteData ['pay'];
			@$conversions ['amount'] += $websiteData ['amount'];
			@$conversions ['purchase'] += $websiteData ['purchase'];
		}
		
		$memberNumber = 0;
		$users = $this->dashboard_model->getUser ( $this->country );
		foreach ( $users as $user ) {
			$memberNumber += $user ['reg'];
			$memberNumber += $user ['autoreg'];
		}
		
		$conversions ['memberNumber'] = $memberNumber;
		$this->page ['conversions'] = $conversions;
		
		$this->page ['lineChartData'] = $lineChartData;
		
		$this->page ['start'] = $startTime;
		$this->page ['end'] = $endTime;
		
		$whereData ['create_time >='] = strtotime ( $startTime . '00:00:00' );
		$whereData ['create_time <='] = strtotime ( $endTime . '23:59:59' );
		
		// 获取订单营销方式
		$orders = $this->order_model->getOrder ( $this->country, $whereData, 0, 0, 'order_number,payment_amount' );
		$marketing = array ();
		if (count ( $orders ) > 0) {
			foreach ( $orders as $key => $order ) {
				$order_append = $this->orderscontent_model->getOrderAppend ( $this->country, $order ['order_number'], 'landing_page' );
				if (strpos ( $order_append ['landing_page'], "utm_medium=email" )) {
					@$marketing ['edm'] += 1;
					@$marketing ['edm_amount'] += $order ['payment_amount'];
				} elseif (strpos ( $order_append ['landing_page'], "gclid" )) {
					@$marketing ['google'] += 1;
					@$marketing ['google_amount'] += $order ['payment_amount'];
				} else {
					@$marketing ['normal'] += 1;
					@$marketing ['normal_amount'] += $order ['payment_amount'];
				}
			}
		}
		$this->page ['marketing'] = $marketing;
		
		// 获取终端比例
		$this->page ['terminal'] = $this->dashboard_model->getTerminal ( $this->country, $whereData );
		
		// 获取运费比例
		$this->page ['shippingType'] = $this->dashboard_model->getShippingType ( $this->country, $whereData );
		
		// 获取各个国家的销量和订单情况
		$countryProductSales = $this->dashboard_model->getOrderAmount ( 0, $startTime, $endTime, array (
				'country' => 1,
				'amount' => 1,
				'order' => 1 
		) );
		$data = array ();
		$countrys = array (
				'AU',
				'NZ',
				'US',
				'CA',
				'GB',
				'IE',
				'SG' 
		);
		for($i = 0; $i < count ( $countrys ); $i ++) {
			foreach ( $countryProductSales as $key => $productSales ) {
				if ($countrys [$i] == $productSales ['country']) {
					@$data [$i] ['country'] = $productSales ['country'];
					@$data [$i] ['amount'] += $productSales ['amount'];
					@$data [$i] ['order'] += $productSales ['order'];
					unset ( $countryProductSales [$key] );
				}
			}
		}
		$this->page ['countrySales'] = $data;
		
		// 组装某个国家产品销量排行
		$poductsRank = $this->dashboard_model->getProductRankByDate ( $this->country, $startTime, $endTime );
		if (count ( $poductsRank )) {
			foreach ( $poductsRank as $productRank ) {
				$productArr [] = new MongoId ( $productRank ['product'] );
			}
			
			$products = $this->dashboard_model->getProductRankByID ( $this->country, $productArr );
			foreach ( $poductsRank as $key => $productRank ) {
				foreach ( $products as $pro ) {
					if ($productRank ['product'] == $pro ['_id']) {
						$poductsRank [$key] ['title'] = $pro ['title'];
						$poductsRank [$key] ['price'] = $pro ['price'];
					}
				}
			}
			
			// 合并重复的产品
			$resProductRank = array ();
			foreach ( $poductsRank as $item ) {
				if (! empty ( $item ['sku'] )) {
					if (! isset ( $resProductRank [@$item ['sku']] )) {
						@$resProductRank [$item ['sku']] = $item;
					} else {
						@$resProductRank [$item ['sku']] ['sold'] += $item ['sold'];
					}
				}
			}
			$this->page ['productRank'] = $this->arraySort ( $resProductRank, 'sold', 'desc' );
		} else {
			$this->page ['productRank'] = 0;
		}
		
		
		// 组装collection销量排行
		if ($this->page ['productRank']) {
			$collectionRank = array ();
			foreach ( $this->page ['productRank'] as $k=>$product ) {
				$starDate =  strtotime ( $startTime . '00:00:00' );
				$endDate = strtotime ( $endTime . '23:59:59' );
				$price=$this->order_model->getOrderAmountbyproductId($this->country,$product['product'],array($starDate,$endDate));
				$this->page ['productRank'][$k]['price']=$price;

				
				$collections = iterator_to_array ( $this->collection_model->getInfoByProID ( $this->country, $product ['product'], 'title' ) );
				foreach ( $collections as $key => $collection ) {
					if (array_key_exists ( $collection ['_id'], $collectionRank )) {
						$collectionRank [$key] ['sold'] += $product ['sold'];
						$collectionRank [$key] ['price'] += $price;
					} else {
						$collectionRank [$key] ['id'] = $collection ['_id'];
						$collectionRank [$key] ['title'] = $collection ['title'];
						$collectionRank [$key] ['sold'] = $product ['sold'];
						$collectionRank [$key] ['price'] = $price;
					}
				}
			}

			$this->page ['collectionRank'] = $this->arraySort ( $collectionRank, 'sold', 'desc' );
		} else {
			$this->page ['collectionRank'] = 0;
		}
		
		// 组装所有国家产品销量排行
		$allPoductsRank = $this->dashboard_model->getProductRankByDate ( 0, $startTime, $endTime );
		// 合并重复的产品
		$resAllProductRank = array ();
		foreach ( $allPoductsRank as $allItem ) {
			if (! empty ( $allItem ['sku'] )) {
				if (! isset ( $resAllProductRank [@$allItem ['sku']] )) {
					@$resAllProductRank [$allItem ['sku']] = $allItem;
				} else {
					@$resAllProductRank [$allItem ['sku']] ['sold'] += $allItem ['sold'];
				}
			}
		}
		$this->page ['allProductRank'] = $this->arraySort ( $resAllProductRank, 'sold', 'desc' );
		
		$this->page ['head'] = $this->load->view ( 'head', $this->_category, true );
		$this->page ['foot'] = $this->load->view ( 'foot', $this->_category, true );
		$this->load->view ( 'dashboard', $this->page );
	}
	
	// 国家间切换
	function changeCountry() {
		$country_code = $this->input->post ( 'country_code' );
		$this->load->model ( 'country_model' );
		$countryInfo = $this->country_model->getInfoByCode ( $country_code, array (
				'name',
				'currency_symbol',
				'currency_payment' 
		) );
		if ($countryInfo) {
			$this->session->set_userdata ( 'my_country', $country_code );
			$this->session->set_userdata ( 'my_currency', $countryInfo ['currency_symbol'] );
			$this->session->set_userdata ( 'my_currencyPayment', $countryInfo ['currency_payment'] );
			
			$this->session->set_userdata ( 'my_countryName', $countryInfo ['name'] );
			exit ( json_encode ( array (
					'success' => true 
			) ) );
		} else {
			exit ( json_encode ( array (
					'success' => FALSE,
					'error' => '网站已关闭！' 
			) ) );
		}
	}
	function test() {
		$this->load->helper ( 'captcha' );
		$vals = array (
				'word' => 'Random word',
				'img_path' => './captcha/',
				'img_url' => 'http://admin.pdq.com/captcha/',
				
				// 'font_path' => './system/fonts/texb.ttf',
				'img_width' => '150',
				'img_height' => 30,
				'expiration' => 7200 
		);
		
		$cap = create_captcha ( $vals );
		echo $cap ['image'];
		exit ();
		
		// $this->load->model('shipformula_model');
		// $country_code = 'FR';
		// $product_price=12.5;
		// $weight = 1200;
		// var_dump($this->shipformula_model->calculateShipping($country_code,$product_price, $weight));
		// exit;
		// exit;
		// $this->load->model('countdown_model');
		// $start = 1435716000;
		// $cy = 864000;
		//
		// // $start=1435680000;
		// // $cy=2678400;
		// echo date('Y-m-d H:i:s',$start);
		// echo '----';
		// echo date('Y-m-d H:i:s',$start+$cy);
		// echo '<br><br>';
		// $result = $this->countdown_model->getEndTime($start, $cy);
		// echo $result['day'] . '天' . $result['hour'] . '小时' . $result['minute'] . '分钟' . $result['second'] . '秒';
		// exit;
		// $product_id = '559de9cf403254144d000029';
		// $country_codes = 'US';
		// $countdown_id = 1;
		// $this->countdown_model->addOneProduct($product_id, $country_codes, $countdown_id);
		// exit;
		
		$country_code = 'US';
		$product_id = '55a86130096fcc840aab125c';
		$collection = $this->mongo->{$country_code . '_collection'};
		$where = array (
				'allow' => new MongoId ( $product_id ) 
		);
		$data = $collection->find ( $where );
		echo '<pre />';
		foreach ( $data as $value ) {
			var_dump ( $value );
			echo '<br>--------------------------------<br>  ';
		}
		
		exit ();
		// foreach ($data as $value) {
		// var_dump($value);echo '<br>';
		// }
		// $collection = $this->mongo->US_product_details;
		// $where = array('_id' => '559a48448104a');
		// $updateData = array(
		// '$inc' => array(
		// 'details..price' => 5
		// )
		// );
		// var_dump($collection->update($where, $updateData));
	}
	
	/**
	 * arraySort php二维数组排序 按照指定的key 对数组进行排序
	 *
	 * @param array $arr
	 *        	将要排序的数组
	 * @param string $keys
	 *        	指定排序的key
	 * @param string $type
	 *        	排序类型 asc | desc
	 * @return array
	 */
	function arraySort($arr, $keys, $type = 'asc') {
		$keysvalue = $new_array = array ();
		foreach ( $arr as $k => $v ) {
			$keysvalue [$k] = $v [$keys];
		}
		$type == 'asc' ? asort ( $keysvalue ) : arsort ( $keysvalue );
		reset ( $keysvalue );
		foreach ( $keysvalue as $k => $v ) {
			$new_array [$k] = $arr [$k];
		}
		return $new_array;
	}
}
