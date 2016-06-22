<?php  
/**
 *  order_model
 *  zouhu,zhujian
 *  产品购物车模型
 */
class productcart_model extends CI_Model{
	function __construct() {
		parent::__construct();
	}
	
	public function add($name, $data){
		$Key = 'productCart_'.$name;
		$this->redis->setAdd($Key, $data);
		$this->redis->timeOut($Key, 86400);
	}
	
	public function getProduct($name){
		$Key='productCart_'.$name;
		return $this->redis->setMembers($Key);
	}
	
	public function cartCount($name){
		$Key='productCart_'.$name;
		return $this->redis->setSize($Key);
	}
	
	//删除指定产品
	public function delCart($name,$value){
		$Key='productCart_'.$name;
		return $this->redis->setMove($Key,$value);
	}
	
	public function del_allCart($name){
		$Key='productCart_'.$name;
		return $this->redis->delete($Key);
	}
}
?>