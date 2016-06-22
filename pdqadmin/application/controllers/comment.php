<?php
/**
   *  @说明   评论控制器
   *  @作者   zhujian
   *  @qq   407284071
*/

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class comment extends Pc_Controller {
	
	
	public function __construct() {
		parent::__construct ();
		parent::_active ( 'comment' );
		$this->load->model ( 'comment_model' );
		$this->user = $this->session->userdata ( 'user_account' );
		$this->country = $this->session->userdata ( 'my_country' );
	}
	
	function index() {
		$per_page = 10; //每页记录数
		
		if ($this->input->post()) {
			$pagenum = 1;
			$keyword = $this->input->post('txtKeyWords') ? $this->input->post('txtKeyWords') : 'ALL';
		}else if ($this->uri->segment(3)) {
			$pagenum = ($this->uri->segment(4) === FALSE ) ? 1 : $this->uri->segment(4);
			$keyword = urldecode($this->uri->segment(3) ? $this->uri->segment(3) : 'ALL');
		} else {
			$pagenum = ($this->uri->segment(4) === FALSE ) ? 1 : $this->uri->segment(4);
			$keyword = urldecode($this->uri->segment(3) ? $this->uri->segment(3) : 'ALL');
		}
		
		if ($keyword != '' and $keyword != 'ALL') {
			$whereData = array(
				'$or' => array (
							array (
									'product_sku' => new MongoRegex ( "/$keyword/i" ) 
							),
							array (
									"product_name" => new MongoRegex ( "/$keyword/i" ) 
							),
							array (
								    "product_id" => $keyword
						    )
					),
			    'country_code' => $this->country	
			);
		} else {
			$whereData = array(
				'country_code' => $this->country
			);
		}
		
		//搜索条件赋值给前端
        $this->page['where'] = $keyword;
		
		$total_rows =$this->comment_model->count($whereData);
		$this->page ['comments'] = $this->comment_model->getComment ($whereData,($pagenum - 1) * $per_page, $per_page);
		
        
		
		//分页开始
		$this->load->library('pagination');
		$config['base_url'] = base_url() . 'comment/index/' . $keyword;
		$config['total_rows'] = $total_rows; //总记录数
		$config['per_page'] = $per_page; //每页记录数
		$config['num_links'] = 9; //当前页码边上放几个链接
		$config['uri_segment'] = 4; //页码在第几个uri上
		$this->pagination->initialize($config);
		$this->page['pages'] = $this->pagination->create_links();
		//分页结束
		
		$this->page ['head'] = $this->load->view('head', $this->_category, true);
		$this->page ['foot'] = $this->load->view('foot', $this->_category, true);
		$this->load->view ( 'Commentlist', $this->page );
	}
	
	
	//修改评论状态
	function updateStatus(){
		$comment_id = $this->input->post ( 'comment_id' );
		$status = $this->input->post ( 'status' );
		
		$result = $this->comment_model->updateStatus ( $comment_id,$status,$this->user);
		if ($result) {
			exit ( json_encode ( array ('success' => true) ) );
		}else{
			exit ( json_encode ( array ('success' => False) ) );
		}
	}
	
	
	//删除评论
	function delete(){
		$comment_id = $this->input->post ( 'comment_id' );
		$result = $this->comment_model->delete ( $comment_id );
		if ($result) {
			exit ( json_encode ( array ('success' => true) ) );
		}else{
			exit ( json_encode ( array ('success' => False) ) );
		}
	}
	
	
	//导入评论
	function importComment(){
		@set_time_limit(0);
		$comments=array(
				'好东西啊','好东西啊2','好东西啊3','好东西啊4','好东西啊5','好东西啊6','好东西啊7','好东西啊8','好东西啊9','好东西啊10','好东西啊11','好东西啊12','好东西啊13','好东西啊14'
		);
		
		
		$goodComments=array(
			'绝杀啊','绝杀啊2','绝杀啊3','绝杀啊4','绝杀啊5','绝杀啊6','绝杀啊7','绝杀啊8','绝杀啊9','绝杀啊10','绝杀啊11','绝杀啊12'
		);
		
		
		$goodProductId=array(
			'55f0f085403254245100002a'
		);
		

		$this->load->model('Product_model');
		//获取产品
		$products=iterator_to_array($this->Product_model->findhidden($this->country,1,array('_id','sku','title')));
		$productsAmount=count($products);
		
		
		ob_end_clean();     //在循环输出前，要关闭输出缓冲区
		echo str_pad('',1024);
		
		//次数
		$num=0;
		
		//程序运行时间
		$starttime = explode(' ',microtime());

		//循环产品添加评论
		foreach ($products as $key=>$product){
			$num++;
			//获取随机的评论 判断时候是否是精华产品
			if (in_array($key, $goodProductId)) {
				$randComment=@array_rand($goodComments,mt_rand(5,10));
				$randStatus=1;
			}else{
				$randComment=@array_rand($comments,mt_rand(2,3));	
				$randStatus=2;
			}
			
						
			//获取产品对应的Collection
			$this->load->model('collection_model');
			$collectionList = $this->collection_model->getInfoByProID($this->country,$key,'_id');
			$collectionIDs =  array_keys( iterator_to_array($collectionList));
			
			
			if(!count($randComment)){
				echo  "<span style='color:red;'><br/>评论已经用完  导致报错<br/>评论已经用完  导致报错<br/>评论已经用完  导致报错<br/>重要的事情说3遍<span/><br/>";
				exit();
			} 
			
			foreach ($randComment as $comment){
				//组装评论数据				
				$data = array(
						'country_code' => $this->country,
						'details_id' => 0,
						'order_number' => 0,
						'collection_id'=>$collectionIDs,
						'product_id' => $key,
						'product_sku' => $product['sku'],
						'product_name' => htmlspecialchars($product['title']),
						'product_star' => $this->randStar(),
						'product_comment' =>$randStatus==1 ? $goodComments[$comment] : $comments[$comment],
						'commentator' => $this->randName(),
						'create_time' => $this->randomDate('2015-11-25','2015-11-27'),
						'status' => 2
				);
				
				
				$resultComment =$this->comment_model->insertComment($data);
				if($resultComment){
					if($randStatus==1){
						unset($goodComments[$comment]);
					}else{
						unset($comments[$comment]);
					}
					
					echo "产品id : $key  生成评论成功.........<br/>";
				}else{
					echo "<span style='color:red;'>产品id : $key 生成评论失败.........<span/><br/>";
					exit();
				}	
			}
			
			echo "<span>产品id : $key  生成评论结束 <span/><br/><br/><span style='color:green'>一起共 $productsAmount 个产品  已经上传 $num 个产品</span><br/>";
			
			//程序运行时间
			$endtime = explode(' ',microtime());
			$thistime = $endtime[0]+$endtime[1]-($starttime[0]+$starttime[1]);
			$thistime = round($thistime,3);
			echo "本网页执行耗时：".$thistime." 秒。";
			
			
			echo "<hr/>";
			
			
			
			flush();    //刷新输出缓冲   
			sleep(1);
		}

	}
	
	
	
	/**
	 * 生成某个范围内的随机时间
	 * @param <type> $begintime  起始时间 格式为 Y-m-d H:i:s 
 	 * @param <type> $endtime    结束时间 格式为 Y-m-d H:i:s   
	 */
	function randomDate($begintime, $endtime="") {
		$begin = strtotime($begintime);
		$end = $endtime == "" ? mktime() : strtotime($endtime);
		$timestamp = mt_rand($begin, $end);
		return $timestamp;
		//return date("Y-m-d H:i:s", $timestamp);
	}
	
	
	/**
	 * 随机生成用户姓名
	 * @param number $number  生成姓名个数  默认为1
	 */
	function randName($number=1){
		$names=array(
				'zhujian','zhujian2','zhujian3','zhujian4','zhujian5','zhujian6','zhujian7','zhujian8','zhujian9','zhujian10','zhujian11','zhujian12','zhujian13',
		);
		return $names[array_rand($names,1)];
	}
	
	
	/**
	 *  随机获取评论成绩  就是星星啦    生成五星的几率为70  
	 */
	function randStar(){
		$product_star = 0; 
		if(mt_rand(1,100) <= 70) {
    		$product_star = 5;
		} else { 
    		$product_star = 4; 
    	}
    	
    	return $product_star;
	}
	
	
}



?>