<?PHP
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class fulfillau extends Pc_Controller {
	public function index() {
		sleep ( 1 );
		if (! $_POST ['order_number'] || ! $_POST ['track_code'] || ! $_POST ['send_bill']) {
			echo "Number Id or Tracking Number or Send Id is empty.";
		} else {
			$this->fulfill ( $_POST ['country'], $_POST ['order_number'], $_POST ['track_code'], $_POST ['track_name'], $_POST ['send_bill'], $_POST ['product_sku'],$_POST ['is_resend'],$_POST ['ifNotify'] );
		}
	}
	function fulfill($country, $order_number, $track_code, $track_name, $send_bill,$product_sku,$is_resend,$if_notify = true) {
		$url = site_url("api/orderSend");
		$track_url = $this->getTrackUrl ( $country, $track_name );
		//判断是否是部分收货
		if($product_sku){
			$send_status=2;
		}else{
			$send_status=1;
		}
		
		//判断是否是重寄
		if(!$is_resend){
			$is_resend=0;
		}
		
		$data = array (
				'apikey' => "pandacheer",
				'orders' => array (
						array (
								'country' => $country,
								'order_number' => $order_number,
								'send_status' => $send_status,
								'track_name' => $track_name,
								'track_code' => $track_code,
								'track_url' => $track_url,
								'send_bill' => $send_bill,
								'product_sku'=>$product_sku,
								'send_time' => time (),
								'is_resend' => (int)$is_resend,
								'operator' => $this->session->userdata ( 'user_account' )
						) 
				) 
		);
		$content = json_encode ( $data );

		$curl = curl_init ( $url );
		curl_setopt ( $curl, CURLOPT_HEADER, false );
		curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, true );
		curl_setopt ( $curl, CURLOPT_HTTPHEADER, array (
				"Content-type: application/json" 
		) );
		curl_setopt ( $curl, CURLOPT_POST, true );
		curl_setopt ( $curl, CURLOPT_POSTFIELDS, $content );
		curl_setopt ( $curl, CURLOPT_FOLLOWLOCATION, true );
		
		$result = curl_exec ( $curl );
		curl_close ( $curl );
		
		$message = json_decode ( $result, true );
		
		if (empty ( $message ['message'] )) {
			echo $message [0] ['errorInfo'];
		} else {
			echo "success";
		}
	}
	
	
	
	
	
	function getTrackUrl($country, $track_name) {
		$track_url;
		switch ($country) {
			case 'AU' :
				$track_url = $this->AU_url ( $track_name );
				break;
			case 'NZ' :
				$track_url = $this->NZ_url ( $track_name );
				break;
			case 'US' :
				$track_url = $this->US_url ( $track_name );
				break;
			case 'CA' :
				$track_url = $this->CA_url ( $track_name );
				break;
			case 'GB' :
				$track_url = $this->UK_url ( $track_name );
				break;
			case 'FR' :
				$track_url = $this->FR_url ( $track_name );
				break;
			case 'MY' :
				$track_url = $this->MY_url ( $track_name );
				break;
			case 'SG' :
				$track_url = $this->SG_url ( $track_name );
				break;
			case 'IE' :
				$track_url = $this->IE_url ( $track_name );
				break;
			case 'BE' :
				$track_url = $this->BE_url ( $track_name );
				break;
			case 'ES' :
				$track_url = $this->ES_url ( $track_name );
				break;
			default :
				$track_url = "ERROR_URL";
				break;
		}
		
		return $track_url;
	}
	
	
	
	
	function AU_url($track_name) {
		$url;
		switch ($track_name) {
			case 'eyoubao' :
				$url = 'http://auspost.com.au/track/track.html';
				break;
			case 'saicheng' :
				$url = 'http://auspost.com.au/track/track.html';
				break;
			case 'xiaobao' :
				$url = 'http://www.17track.net/en/';
				break;
			case 'shunfeng' :
				$url = 'http://intl.sf-express.com/?a=trackEn';
				break;
			case 'untraceable' :
				$url = 'http://www.drgrab.com.au/pages/faq';
				break;
			default :
				$url = "ERROR_URL_AU";
				break;
		}
		
		return $url;
	}
	
	
	
	
	function NZ_url($track_name) {
		$url;
		switch ($track_name) {
			case 'saicheng' :
				$url = 'https://www.nzpost.co.nz/tools/tracking';
				break;
			case 'xiaobao' :
				$url = 'https://www.nzpost.co.nz/tools/tracking';
				break;
			case 'untraceable' :
				$url = 'http://www.drgrab.co.nz/pages/faq';
				break;
			default :
				$url = "ERROR_URL_NZ";
				break;
		}
		
		return $url;
	}
	
	
	
	
	function US_url($track_name) {
		$url;
		switch ($track_name) {
			case 'eyoubao' :
				$url = 'https://tools.usps.com/go/TrackConfirmAction_input';
				break;
			case 'ems' :
				$url = 'https://tools.usps.com/go/TrackConfirmAction_input';
				break;
			case 'shunfeng' :
				$url = 'http://intl.sf-express.com/?a=trackEn';
				break;
			case 'xiaobao' :
				$url = 'http://www.drgrab.com/pages/faq';
				break;
			case 'untraceable' :
				$url = 'http://www.drgrab.com/pages/faq';
				break;
			default :
				$url = "ERROR_URL_US";
				break;
		}
		
		return $url;
	}
	
	
	
	function CA_url($track_name) {
		$url;
		switch ($track_name) {
			case 'eyoubao' :
				$url = 'https://www.canadapost.ca/cpotools/apps/track/personal/findByTrackNumber?execution=e1s1';
				break;
			case 'ems' :
				$url = 'https://www.canadapost.ca/cpotools/apps/track/personal/findByTrackNumber?execution=e1s1';
				break;
			case 'xiaobao' :
				$url = 'http://www.17track.net/en/';
				break;
			case 'untraceable' :
				$url = 'http://www.drgrab.ca/pages/faq';
				break;
			case 'Express Shipping' :
				$url = 'http://www.canadapost.ca/cpo/mc/default.jsf?LOCALE=en';
				break;
			case 'Standard Shipping' :
				$url = 'http://www.canadapost.ca/cpo/mc/default.jsf?LOCALE=en';
				break;
			default :
				$url = "ERROR_URL_CA";
				break;
		}
		
		return $url;
	}
	
	
	
	
	function UK_url($track_name) {
		$url;
		switch ($track_name) {
			case 'eyoubao' :
				$url = 'http://www.royalmail.com/';
				break;
			case 'tekuai' :
				$url = 'http://www.ems.com.cn/english.html';
				break;
			case 'xiaobao' :
				$url = 'http://www.royalmail.com/';
				break;
			case 'untraceable' :
				$url = 'http://www.drgrab.co.uk/pages/faq';
				break;
			default :
				$url = "ERROR_URL_UK";
				break;
		}
		
		return $url;
	}
	
	
	
	
	function FR_url($track_name) {
		$url;
		switch ($track_name) {
			case 'eyoubao' :
				$url = 'http://www.laposte.fr/particulier';
				break;
			case 'xiaobao' :
				$url = 'http://www.laposte.fr/particulier';
				break;
			case 'untraceable' :
				$url = 'http://www.drgrab.com/pages/faq';
				break;
			default :
				$url = "ERROR_URL_FR";
				break;
		}
		
		return $url;
	}
	
	
	
	
	function MY_url($track_name) {
		$url;
		switch ($track_name) {
			case 'zhuanxian' :
				$url = 'http://www.citylinkexpress.com/MY/Home.aspx';
				break;
			case 'xiaobao' :
				$url = 'http://www.17track.net/en/';
				break;
			case 'untraceable' :
				$url = 'http://my.drgrab.com/pages/faq';
				break;
			default :
				$url = "ERROR_URL_MY";
				break;
		}
		
		return $url;
	}
	
	
	
	
	function SG_url($track_name) {
		$url;
		switch ($track_name) {
			case 'zhuanxian' :
				$url = 'http://www.citylinkexpress.com/SG/Home.aspx';
				break;
			case 'xiaobao' :
				$url = 'http://www.17track.net/en/';
				break;
			case 'untraceable' :
				$url = 'http://www.drgrab.sg/pages/faq';
				break;
			default :
				$url = "ERROR_URL_SG";
				break;
		}
		
		return $url;
	}
	
	
	
	
	function IE_url($track_name) {
		$url;
		switch ($track_name) {
			case 'saicheng' :
				$url = 'http://203.86.9.92:8086/Details.aspx';
				break;
			case 'shunfeng' :
				$url = 'http://intl.sf-express.com/?a=trackEn';
				break;
			case 'xiaobao' :
				$url = 'http://www.17track.net/en/';
				break;
			case 'untraceable' :
				$url = 'http://ie.drgrab.com/pages/faq';
				break;
			default :
				$url = "ERROR_URL_IE";
				break;
		}
		
		return $url;
	}
	
	
	
	
	function BE_url($track_name) {
		$url;
		switch ($track_name) {
			case 'saicheng' :
				$url = 'http://www.saichenglogistics.com/EN/index.asp';
				break;
			case 'shunfeng' :
				$url = 'http://intl.sf-express.com/?a=trackEn';
				break;
			case 'xiaobao' :
				$url = 'http://www.17track.net/en/';
				break;
			case 'untraceable' :
				$url = 'http://www.drgrab.com/pages/faq';
				break;
			default :
				$url = "ERROR_URL_BE";
				break;
		}
		
		return $url;
	}
	
	
	
	
	function ES_url($track_name) {
		$url;
		switch ($track_name) {
			case 'saicheng' :
				$url = 'http://www.saichenglogistics.com/EN/index.asp';
				break;
			case 'shunfeng' :
				$url = 'http://intl.sf-express.com/?a=trackEn';
				break;
			case 'xiaobao' :
				$url = 'http://www.17track.net/en/';
				break;
			case 'untraceable' :
				$url = 'http://www.drgrab.com/pages/faq';
				break;
			case 'xibanya' :
				$url = 'http://www.saichenglogistics.com/EN/index.asp';
				break;
			default :
				$url = "ERROR_URL_ES";
				break;
		}
		
		return $url;
	}
}

?>
