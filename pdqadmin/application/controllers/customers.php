<?php

/**
 * @文件： customers
 * @时间： 2015-7-1 10:57:56
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：
 */
class Customers extends Pc_Controller {
	var $pageCountry, $userAccount, $userID;
	function __construct() {
		parent::__construct ();
		$this->pageCountry = $this->session->userdata ( 'my_country' );
		$this->userAccount = $this->session->userdata ( 'user_account' );
		$this->userID = $this->session->userdata ( 'user_id' );
		$this->page ['sysSendStatus'] = [ 
				'0' => 'Unfulfilled',
				'1' => 'Fulfilled',
				'2' => 'Partially Fulfilled',
				'3' => 'Dispatched' 
		];
		$this->page ['sysPayStatus'] = [ 
				'0' => 'Unpaid',
				'1' => 'Paid',
				'2' => 'Refund',
				'3' => 'Partially Refund' 
		];
		$this->page ['sysDocStatus'] = [ 
				'1' => 'Processing',
				'2' => 'Archived',
				'3' => 'Canceled' 
		];
	}
	function index() {
		parent::_active ( 'customers' );
		$per_page = 10; // 每页记录数
		$this->page ['head'] = $this->load->view ( 'head', $this->_category, true );
		$this->page ['foot'] = $this->load->view ( 'foot', $this->_category, true );
		if ($this->input->post ()) {
			$pagenum = 1;
			$keyword = $this->input->post ( 'txtKeyWords' ) ? $this->input->post ( 'txtKeyWords' ) : 'ALL';
		} else {
			$pagenum = ($this->uri->segment ( 4 ) === FALSE) ? 1 : $this->uri->segment ( 4 );
			$keyword = urldecode ( $this->uri->segment ( 3 ) ? $this->uri->segment ( 3 ) : 'ALL' );
		}
		if ($keyword != '' and $keyword != 'ALL') {
			$whereData ['member_email like'] = "%$keyword%";
		} else {
			$whereData = [ ];
		}
		$this->page ['txtKeyWords'] = $keyword;
		$this->load->model ( 'memberanalysis_model' );
		$sort = 'member_id';
		$order = 'desc';
		$offset = ($pagenum - 1) * $per_page;
		$this->page ['members'] = $this->memberanalysis_model->listData ( $this->pageCountry, $whereData, $sort, $order, $offset, $per_page );
		$total_rows = $this->memberanalysis_model->count ( $this->pageCountry, $whereData );
		// 分页开始
		$this->load->library ( 'pagination' );
		
		$config ['base_url'] = base_url () . 'customers/index/' . $keyword;
		$config ['total_rows'] = $total_rows; // 总记录数
		$config ['per_page'] = $per_page; // 每页记录数
		$config ['num_links'] = 9; // 当前页码边上放几个链接
		$config ['uri_segment'] = 4; // 页码在第几个uri上
		$this->pagination->initialize ( $config );
		$this->page ['pages'] = $this->pagination->create_links ();
		// 分页结束
		// 搜索条件赋值给前端
		$this->page ['where'] = $keyword;
		$this->load->view ( 'CustomersList.php', $this->page );
	}
	function getInfo($member_id, $order_spent = 0) {
		parent::_active ( 'customers' );
		$this->page ['head'] = $this->load->view ( 'head', $this->_category, true );
		$this->page ['foot'] = $this->load->view ( 'foot', $this->_category, true );
		
		$per_page = 10; // 每页记录数
		
		if ($this->input->post ()) {
			$pagenum = 1;
		} else {
			$pagenum = ($this->uri->segment ( 5 ) === FALSE) ? 1 : $this->uri->segment ( 5 );
		}
		
		$this->load->model ( 'order_model' );
		$whereData = [ 
				'member_id' => $member_id 
		];
		$total_rows = $this->order_model->getListByMemberIdCount ( $this->pageCountry, $whereData );
		$this->page ['orderList'] = $this->order_model->getListByMemberId ( $this->pageCountry, $whereData, 'create_time', 'desc', ($pagenum - 1) * $per_page, $per_page, 'order_id,order_number,payment_amount,create_time,send_status,pay_status,doc_status' );
		
		$this->load->model ( 'member_model' );
		$this->page ['member'] = $this->member_model->getInfoById ( $this->pageCountry, $member_id, $fields = 'member_name,member_email,create_time,login_inc,login_time' );
		$this->page ['member'] ['order_spent'] = $order_spent;
		
		$this->load->model ( 'memberreceive_model' );
		$this->page ['memberReceives'] = $this->memberreceive_model->getListByMemberId ( $this->pageCountry, $member_id );
		
		// 分页开始
		$this->load->library ( 'pagination' );
		$config ['base_url'] = base_url () . 'customers/getInfo/' . $member_id . '/' . $order_spent . '/';
		$config ['total_rows'] = $total_rows; // 总记录数
		$config ['per_page'] = $per_page; // 每页记录数
		$config ['num_links'] = 9; // 当前页码边上放几个链接
		$config ['uri_segment'] = 5; // 页码在第几个uri上
		$this->pagination->initialize ( $config );
		$this->page ['pages'] = $this->pagination->create_links ();
		
		$this->load->view ( 'CustomersInfo.php', $this->page );
	}
	
	/*
	 * function remove() {
	 * $memberEmail = $this->uri->segment(3);
	 * $this->db->where('member_email', $memberEmail);
	 * $this->db->delete('US_member');
	 * }
	 *
	 * function update222() {
	 * $memberEmail = $this->uri->segment(3);
	 * $this->db->where('member_email', $memberEmail);
	 * if ($this->db->update('US_member', array("status" => 1))) {
	 * echo "update status=1";
	 * } else {
	 * echo "update status=1 error";
	 * }
	 * }
	 */
	
	
	
	
	// 用户列表显示
	function memberList() {
		parent::_active ( 'member' );
		$per_page = 10; // 每页记录数
		$this->page ['head'] = $this->load->view ( 'head', $this->_category, true );
		$this->page ['foot'] = $this->load->view ( 'foot', $this->_category, true );
		if ($this->input->post ()) {
			$pagenum = 1;
			$keyword = $this->input->post ( 'txtKeyWords' ) ? $this->input->post ( 'txtKeyWords' ) : 'ALL';
			$keywordStartTime = $this->input->post ( 'startTime' ) ? $this->input->post ( 'startTime' ) : 'ALL';
			$keywordEndTime = $this->input->post ( 'endTime' ) ? $this->input->post ( 'endTime' ) : 'ALL';
		} else {
			$pagenum = ($this->uri->segment ( 6 ) === FALSE) ? 1 : $this->uri->segment ( 6 );
			$keyword = urldecode ( $this->uri->segment ( 3 ) ? $this->uri->segment ( 3 ) : 'ALL' );
			$keywordStartTime = $this->uri->segment ( 4 ) ? $this->uri->segment ( 4 ) : 'ALL';
			$keywordEndTime = $this->uri->segment ( 5 ) ? $this->uri->segment ( 5 ) : 'ALL';
		}
		
		if ($keyword != "ALL" && $keywordStartTime == "ALL" && $keywordEndTime == "ALL") {
			$whereData ['member_email like'] = "%$keyword%";
		} elseif ($keyword == "ALL" && $keywordStartTime != "ALL" && $keywordEndTime != "ALL") {
			$whereData ['create_time >='] = strtotime ( $keywordStartTime . '00:00:00' );
			$whereData ['create_time <='] = strtotime ( $keywordEndTime . '23:59:59' );
		} elseif ($keyword != "ALL" && $keywordStartTime != "ALL" && $keywordEndTime != "ALL") {
			$whereData ['member_email like'] = "%$keyword%";
			$whereData ['create_time >='] = strtotime ( $keywordStartTime . '00:00:00' );
			$whereData ['create_time <='] = strtotime ( $keywordEndTime . '23:59:59' );
		} else {
			$whereData = [ ];
		}
		
		$this->page ['txtKeyWords'] = $keyword;
		$this->page ['startTime'] = $keywordStartTime;
		$this->page ['endTime'] = $keywordEndTime;
		
		$this->load->model ( 'member_model' );
		$sort = 'member_id';
		$order = 'desc';
		$offset = ($pagenum - 1) * $per_page;
		$this->page ['members'] = $this->member_model->listData ( $this->pageCountry, $whereData, $sort, $order, $offset, $per_page );
		$total_rows = $this->member_model->count ( $this->pageCountry, $whereData );
		
		$this->page ['total_rows'] = $total_rows;
		// 分页开始
		$this->load->library ( 'pagination' );
		
		$config ['base_url'] = base_url () . 'customers/memberList/' . $keyword . '/' . $keywordStartTime . '/' . $keywordEndTime;
		$config ['total_rows'] = $total_rows; // 总记录数
		$config ['per_page'] = $per_page; // 每页记录数
		$config ['num_links'] = 9; // 当前页码边上放几个链接
		$config ['uri_segment'] = 6; // 页码在第几个uri上
		$this->pagination->initialize ( $config );
		$this->page ['pages'] = $this->pagination->create_links ();
		// 分页结束
		// 搜索条件赋值给前端
		$this->page ['where'] = $keyword;
		$this->load->view ( 'memberList.php', $this->page );
	}
	
	
	
	
	
	
	public function getExcel() {
		$key = $this->input->get ( 'key' );
		$startTime = $this->input->get ( 'startTime' );
		$endTime = $this->input->get ( 'endTime' );
		
		if (!$key) {
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
		
		$whereData ['create_time >='] = strtotime ( $startTime . '00:00:00' );
		$whereData ['create_time <='] = strtotime ( $endTime . '23:59:59' );
		$sort = 'member_id';
		$order = 'desc';
		
		$this->load->model ( 'member_model' );
		$this->load->model ( 'country_model' );
		$countryList = $this->country_model->getCountryCodeSet ();
		
		$data = array ();
		foreach ( $countryList as $key => $country ) {
			$members = $this->member_model->listData ( $country, $whereData, $sort, $order, 0, 0, $fields = 'member_email' );
			foreach ( $members as $k => $member ) {
				$data [$country] [$k] = $member ['member_email'];
			}
		}
		
		if (count ( $data )) {
			$filename = '用户列表' . $startTime . '/' . $endTime;
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
