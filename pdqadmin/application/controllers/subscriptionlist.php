<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class subscriptionlist extends Pc_Controller {
	public $per_page;
	function __construct() {
		parent::__construct ();
		$this->per_page = 20; /* * 每页多少条* */
		$this->load->helper ( 'form' );
		$this->load->model ( 'subscriptionlist_model' );
		$this->country = $this->session->userdata ( 'my_country' );
		$this->page ['time'] = date ( "Y-m-d", time () );
		$this->page ['head'] = $this->load->view ( 'head', $this->_category, true );
		$this->page ['foot'] = $this->load->view ( 'foot', $this->_category, true );
	}
	public function index() {
		
		/* * 获取总条数 */
		$this->page ['count'] = $this->subscriptionlist_model->count ( $this->country );
		/* * 载入分页类* */
		$this->load->library ( 'pagination' );
		$this->load->helper ( 'url' );
		$config ['base_url'] = site_url ( 'subscriptionlist/index' );
		$config ['uri_segment'] = 3;
		$config ['total_rows'] = $this->page ['count'];
		$config ['per_page'] = $this->per_page;
		$config ['num_links'] = 9;
		$this->pagination->initialize ( $config );
		$this->page ['pageArticle'] = $this->pagination->create_links ();
		/* * 获取偏移量* */
		$offset = intval ( $this->uri->segment ( 3 ) );
		$offset = $offset - 1;
		if ($offset < 0) {
			$offset = 0;
		}
		$offset = $offset * $config ['per_page'];
		/* * 获取每页数据* */
		$result = $this->subscriptionlist_model->limit ( $this->country, $offset, $config ['per_page'] );
		$this->page ['time1'] = '';
		$this->page ['list'] = '';
		foreach ( $result as $value ) {
			$this->page ['list'] .= '<tr>';
			$this->page ['list'] .= '<td>' . $value ['_id'] . '</td>';
			$this->page ['list'] .= '<td class="text-center">' . date ( "Y-m-d H:i:s", $value ['create_time'] ) . '</td>';
			$this->page ['list'] .= '<td ><button class="delete btn btn-default" id="' . $value ['_id'] . '"value="' . $value ['_id'] . '"><i class="fa fa-trash-o fa-lg"></i></button></td>';
			$this->page ['list'] .= '</tr>';
		}
		$this->page ['where'] = '';
		$this->load->view ( 'subscriptionlist', $this->page );
	}
	public function delete() {
		$id = $this->input->post ( 'id' );
		if ($this->subscriptionlist_model->delete ( $this->country, $id )) {
			exit ( json_encode ( array (
					'success' => $id 
			) ) );
		} else {
			exit ( json_encode ( array (
					'success' => 0,
					'info' => 'delete fail' 
			) ) );
		}
	}
	public function inquire($post = '') {
		if ($this->input->post ()) {
			$post = $this->input->post ( 'input' );
		}
		if ($post) {
			$page = $this->uri->segment ( 4 ) === false ? 1 : $this->uri->segment ( 4 );
		} else {
			$page = $this->uri->segment ( 3 ) === false ? 1 : $this->uri->segment ( 3 );
		}
		$offset = ($page - 1) * $this->per_page;
		$result1 = $this->subscriptionlist_model->listData ( $this->country, array (
				'_id' => new MongoRegex ( '/' . $post . '/' ) 
		), array (), $offset, $this->per_page );
		$this->page ['count'] = 0;
		$this->page ['list'] = '';
		if (! empty ( $result1 )) {
			foreach ( $result1 as $k => $result ) {
				if ($result) {
					$this->page ['list'] .= '<tr>';
					$this->page ['list'] .= '<td>' . $result ['_id'] . '</td>';
					$this->page ['list'] .= '<td class="text-center">' . date ( "Y-m-d H:i:s", $result ['create_time'] ) . '</td>';
					$this->page ['list'] .= '<td ><button class="delete btn btn-default" id="' . $result ['_id'] . '"value="' . $result ['_id'] . '"><i class="fa fa-trash-o fa-lg"></i></button></td>';
					$this->page ['list'] .= '</tr>';
				}
			}
		}
		$this->page ['count'] = $this->subscriptionlist_model->count1 ( $this->country, array (
				'_id' => new MongoRegex ( '/' . $post . '/' ) 
		) );
		$this->load->library ( 'pagination' );
		$this->load->helper ( 'url' );
		$config ['base_url'] = site_url ( 'subscriptionlist/inquire/' . $post );
		$config ['uri_segment'] = $post ? 4 : 3;
		$config ['total_rows'] = $this->page ['count'];
		$config ['per_page'] = $this->per_page;
		$config ['num_links'] = 9;
		$this->pagination->initialize ( $config );
		$this->page ['pageArticle'] = $this->pagination->create_links ();
		$this->page ['time1'] = '';
		$this->page ['where'] = $post;
		$this->load->view ( 'subscriptionlist', $this->page );
	}
	public function datepicker() {
		if ($this->uri->segment ( 4 )) {
			$datepicker1 = $this->uri->segment ( 3 );
			$datepicker2 = $this->uri->segment ( 4 );
		} else {
			$datepicker1 = $this->input->post ( 'datepicker1' );
			$datepicker2 = $this->input->post ( 'datepicker2' );
			/* * 把接受到的日期时间转成时间戳并且把到期时间+1天* */
			$datepicker1 = strtotime ( $datepicker1 );
			$datepicker2 = strtotime ( $datepicker2 );
			if ($datepicker1 === false || $datepicker1 == - 1) {
				$datepicker1 = '';
			}
			if ($datepicker2 > 0) {
				$datepicker2 = $datepicker2 + 60 * 60 * 24;
				/* * 减一秒是代表到59分59秒* */
				// $datepicker1 = $datepicker1 - 1;
				$datepicker2 = $datepicker2 - 1;
			} else {
				$datepicker2 = '';
			}
		}
		$this->page ['time1'] = '';
		if (! empty ( $datepicker1 )) {
			$this->page ['time1'] = date ( 'Y-m-d', $datepicker1 );
		}
		if (! empty ( $datepicker2 )) {
			$this->page ['time'] = date ( 'Y-m-d', $datepicker2 );
		}
		/* * 获取总数* */
		$this->page ['count'] = $this->subscriptionlist_model->datepickerCount ( $this->country, $datepicker1, $datepicker2 );
		/* * 获取偏移量* */
		$page = intval ( $this->uri->segment ( 5 ) );
		$offset = $page - 1;
		if ($offset < 0) {
			$offset = 0;
		}
		/* * 载入分页类* */
		$this->load->library ( 'pagination' );
		$config ['base_url'] = site_url ( 'subscriptionlist/datepicker/' . $datepicker1 . '/' . $datepicker2 );
		$config ['total_rows'] = $this->page ['count'];
		$config ['per_page'] = $this->per_page;
		$config ['uri_segment'] = 5;
		$config ['num_links'] = 9;
		$this->pagination->initialize ( $config );
		$this->page ['pageArticle'] = $this->pagination->create_links ();
		
		$this->load->helper ( 'url' );
		$offset = $offset * $config ['per_page'];
		/* * 获取每页数据* */
		$result = $this->subscriptionlist_model->datepicker ( $this->country, $datepicker1, $datepicker2, $offset, $config ['per_page'] );
		$this->page ['list'] = '';
		foreach ( $result as $value ) {
			$this->page ['list'] .= '<tr>';
			$this->page ['list'] .= '<td>' . $value ['_id'] . '</td>';
			$this->page ['list'] .= '<td class="text-center">' . date ( "Y-m-d H:i:s", $value ['create_time'] ) . '</td>';
			$this->page ['list'] .= '<td ><button class="delete btn btn-default" id="' . $value ['_id'] . '"value="' . $value ['_id'] . '"><i class="fa fa-trash-o fa-lg"></i></button></td>';
			$this->page ['list'] .= '</tr>';
		}
		$this->page ['where'] = '';
		$this->load->view ( 'subscriptionlist', $this->page );
	}
	
	
	
	
	
	
	
	
	public function getExcel() {
		$key = $this->input->get ( 'key' );
		$startTime = $this->input->get ( 'startTime' );
		$endTime = $this->input->get ( 'endTime' );
		
		if (! $key) {
			$startTime = date ( "Y-m-d", strtotime ( "-9 day" ) );
			$endTime = date ( "Y-m-d" );
		} elseif ($key == "qwert") {
			if (! $startTime || ! $endTime) {
				echo "日期不能为空";
				exit ();
			}
		} else {
			echo "密码错误";
			exit ();
		}
		
		$whereData = array (
				'create_time' => array (
						'$gte' => strtotime ( $startTime . '00:00:00' ),
						'$lte' => strtotime ( $endTime . '23:59:59' ) 
				) 
		);
		
		$this->load->model ( 'country_model' );
		$countryList = $this->country_model->getCountryCodeSet ();
		
		$data = array ();
		foreach ( $countryList as $key => $country ) {
			$subscriptionlist = iterator_to_array ( $this->subscriptionlist_model->listData ( $country, $whereData, array (
					'_id' 
			), 0, 'ALL' ) );
			foreach ( $subscriptionlist as $k => $list ) {
				$data [$country] [$k] = $list ['_id'];
			}
		}
		
		if (count ( $data )) {
			$filename = '用户订阅列表' . $startTime . '/' . $endTime;
			$this->exportexcel ( $data, '', $filename );
			exit ();
		} else {
			echo "你选择的日期没有数据";
			exit ();
		}
	} 

	
	
	
	
	
	
	
	
	/**
	 * 导出数据为excel表格
	 *
	 * @param $data 一个二维数组,结构如同从数据库查出来的数组        	
	 * @param $title excel的第一行标题,一个数组,如果为空则没有标题        	
	 * @param $filename 下载的文件名
	 *        	@examlpe
	 *        	$stu = M ('User');
	 *        	$arr = $stu -> select();
	 *        	exportexcel($arr,array('id','账户','密码','昵称'),'文件名!');
	 */
	public function exportexcel($data = array(), $title = array(), $filename = 'report') {
		header ( "Content-type:application/octet-stream" );
		header ( "Accept-Ranges:bytes" );
		header ( "Content-type:application/vnd.ms-excel" );
		header ( "Content-Disposition:attachment;filename=" . $filename . ".xls" );
		header ( "Pragma: no-cache" );
		header ( "Expires: 0" );
		
		// 导出xls 开始
		if (! empty ( $title )) {
			foreach ( $title as $k => $v ) {
				$title [$k] = iconv ( "UTF-8", "GB2312", $v );
			}
			$title = implode ( "\t", $title );
			echo "$title\n";
		}
		if (! empty ( $data )) {
			foreach ( $data as $key => $val ) {
				echo "\n\n\n".$key."\n";
				foreach ( $val as $ck => $cv ) {
					$data [$key] [$ck] = iconv ( "UTF-8", "GB2312", $cv );
					echo $data [$key] [$ck]."\n";
				}
			}
		}
	}
}
