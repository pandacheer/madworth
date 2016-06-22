<?php

/*
 * 前台产品模型
 */

class Product_model extends CI_Model {

    protected $CI;
    protected $pro;
    protected $pro_append;
    protected $pro_details;
    protected $product;
    protected $product_append;
    protected $product_details;
    protected $_product = '_product';
    protected $_product_append = '_product_append';
    protected $_product_details = '_product_details';
    protected $_methods = array('where', 'order', 'limit');
    protected $_options = array(
        'order' => '_id,desc',
        'limit' => '0,10',
    );

    public function __construct() {
        $this->CI = & get_instance();
    }

    public function __call($method, $args) {
        if (in_array(strtolower($method), $this->_methods, true)) {
            $this->_options[$method] = $args[0];
            return $this;
        }
    }

    public function table($country) {
        $this->product = $country . $this->_product;
        $this->product_append = $country . $this->_product_append;
        $this->product_details = $country . $this->_product_details;
        $this->pro = $this->CI->mongo->selectCollection($this->product);
        $this->pro_append = $this->CI->mongo->selectCollection($this->product_append);
        $this->pro_details = $this->CI->mongo->selectCollection($this->product_details);
    }

    public function count() {
        return $this->pro->count();
    }

    public function find($_ids) {
        foreach ($_ids as $vo) {
            $arr[] = $this->findOne($vo);
        }
        return $arr;
    }

    public function findOne($_id) {
        $_id = new MongoId($_id);
        $condition = array('_id' => $_id);
        $table_1 = $this->pro->findOne($condition);
        $table_2 = $this->pro_append->findOne($condition);
        $table_3 = $this->pro_details->findOne($condition);
        return $table_1 + $table_2 + $table_3;
    }

    public function findSeo($country, $status = 1, $seourl) {
        $this->table($country);

        if ($status) {
            $conditions = array('seo_url' =>new MongoRegex('/^' . $seourl . '$/i'), 'status' =>array('$in'=>array(1,3)));
        } else {
            $conditions = array('seo_url' => new MongoRegex('/^' . $seourl . '$/i'));
        }

        $table_1 = $this->pro->findOne($conditions);
        $table_2 = $this->pro_append->findOne(array('_id' => $table_1['_id']));
        $table_3 = $this->pro_details->findOne(array('_id' => $table_1['_id']));
        return $table_1 + $table_2 + $table_3;
    }

    public function select($country) {
        $this->table($country);
        $condition = $this->_condition();
        $pro = $this->_o2a($this->pro->find()->sort($condition['sort'])->limit($condition['limit'])->skip($condition['skip']));
        $pro_append = $this->_o2a($this->pro_append->find()->sort($condition['sort'])->limit($condition['limit'])->skip($condition['skip']));
        $pro_details = $this->_o2a($this->pro_details->find()->sort($condition['sort'])->limit($condition['limit'])->skip($condition['skip']));
        return $this->_merge($pro, $pro_append, $pro_details);
    }

    public function selectAll($country) {
        $this->table($country);
        $condition = array('_id' => -1);
        
        $pro = $this->_o2a($this->pro->find(array('status'=>1))->sort($condition));
        $pro_append = $this->_o2a($this->pro_append->find()->sort($condition));
        $pro_details = $this->_o2a($this->pro_details->find()->sort($condition));

        return $this->_merge($pro, $pro_append, $pro_details);
    }

    public function skuPrice($country, $_id, $sku) {
        $this->table($country);
        if (!is_object($_id)) {
            $_id = new MongoId($_id);
        }
        $condition = array('_id' => $_id);
        if (substr_count($sku, "/")) {
            $data = $this->pro_details->findOne($condition);
            if ($data == NULL) {
                return false;
            } else {
                $return = false;
                foreach ($data['details'] as $vo) {
                    if (strtolower($vo['sku']) == strtolower($sku)) {
                    	unset ($vo['cost']);
                        $return = true;
                        return $vo;
                    }
                }
                if (!$return) {
                    return $return;
                }
            }
        } else {
            $table_1 = $this->pro->find($condition);
            foreach ($table_1 as $vo) {
                $arr = $vo;
            }
            return $arr;
        }
    }

    //查找自身绑定
    public function findSelfBundle($_id) {
        $_id = new MongoId($_id);
        $condition = array('_id' => $_id);
        $table_2 = $this->pro_append->findOne($condition, array('plural' => true, '_id' => false));
//        return $table_2['plural'];
//        echo '<pre>';
//        foreach ($table_2 as $vo) {
//            
//            var_dump($vo);
//            //$arr = $arr + $vo;
//        }
//        exit;
        return $table_2;
    }

    /* public function orderPics($array) {
      foreach ($array as $key => $vo) {
      $_id = new MongoId($vo['_id']);
      $data[] = $this->pro->findOne(array('_id' => $_id), array('image' => 1));
      }
      return $data;
      } */

    //根据产品id获取绑定的商品和喜欢的商品
    public function specialProduct($country, $array) {
        if(!is_array($array))return array();
        $product = $this->CI->mongo->selectCollection($country . '_product');
        $result = $product->find(array('_id' => array('$in' => $array), 'status' => 1))->sort(array("sold.total" => -1))->limit(5);
        return $this->_o2a($result);
    }
    
    public function relativeProduct($country, $array) {
        if(!is_array($array))return array();
        $product = $this->CI->mongo->selectCollection($country . '_product');
        $result = $product->find(array('sku' => array('$in' => $array), 'status' => 1))->sort(array("sold.total" => -1));
        return $this->_o2a($result);
    }

    /*
      orders
      订单部分
     */

    public function orderWeight($array) {
        $sum = 0;
        foreach ($array as $key => $vo) {
            $_id = new MongoId($vo);
            $data = $this->pro_append->findOne(array('_id' => $_id), array('weight' => 1));
            $sum += $data['weight'] * $vo['num'];
        }
        return $sum;
    }

    public function orderPrice($array) {
        foreach ($array as $key => $vo) {
            $_id = new MongoId($vo['product_id']);
            $data = $this->_o2a($this->pro_details->find(array('_id' => $_id)));
            if (!isset($data[$vo['product_id']]['details'])) {
                $return = '400';
            } else {
                // SKU不存在
                $array[$key]['status'] = '404';
                foreach ($data[$vo['product_id']]['details'] as $vi) {
                    if ($vi['sku'] == $vo['product_sku']) {
                        if ($vi['status'] != '1') {
                            $array[$key]['status'] = '440';
                            break;
                        }
                        if ($vi['price'] == $vo['payment_price']) {
                            // 返回正确！
                            $array[$key]['status'] = '200';
                            $array[$key]['payment_amount'] = $vo['payment_price'] * $vo['product_quantity'];
                            break;
                        } else {
                            // SKU存在价格不匹配
                            $array[$key]['status'] = '410';
                            break;
                        }
                    }
                }
            }
        }
    }

    public function orderPics($country, $_id) {
        $this->table($country);
        if (!is_object($_id)) {
            $_id = new MongoId($_id);
        }
        $condition = array('_id' => $_id);
        $data = $this->pro->findOne($condition, array('image' => 1, 'seo_url' => 1,'freebies'=>1));
        return $data;
    }

    //通过产品id查询产品的创建者
    public function productCreator($country, $_id) {
        $this->table($country);
        if (!is_object($_id)) {
            $_id = new MongoId($_id);
        }
        $condition = array('_id' => $_id);
        $data = $this->pro->findOne($condition, array('creator' => 1));
        return $data;
    }
    
    
    
    
    
    //通过产品id查询产品的type名称
    public function getTypeById($country, $id) {
    	$categoryMongo = $this->mongo->{'Category'};
    	$condition = array('_id' => $id);
    	$data = $categoryMongo->findOne($condition, array('title' => 1));
    	return $data;
    }
    

    /* cart购物车部分 */

    //添加购物车验证
    public function exist($country, $product_id, $sku, $type) {
        $this->table($country);
        $id = is_object($product_id) ? $product_id : new MongoId($product_id);
        $pro = $this->pro->findOne(array('_id' => $id), array('_id' => 1, 'sku' => 1, 'children' => 1,'status'=>1));
		
        if (isset($pro['_id'])) {
        	if($pro['status']==3){
        		return 4;
        	}
            switch ($type) {
                case 1:
                    if ($pro['children']) {
                        $sku = str_replace(')','\)',str_replace('(', '\(', $sku));
                        $pro_details = $this->pro_details->findOne(array('details' => array ('$elemMatch' => array('sku' => new MongoRegex("/^$sku$/i")))), array('details.$.status'=>1));

                        if (count($pro_details['details'])) {
                           if($pro_details['details'][0]['status']!=1){
                           	return 4;
                           }
                        }else{
                        	return false;
                        }
                    } else {
                        if (strtolower($sku) != strtolower($pro['sku'])) {
                            return false;
                        }
                    }
                    break;

                case 2:
                    $products_sku = explode(",", $sku);
                    if (strtolower($products_sku[0]) == strtolower($pro['sku'])) {
                        $plural = $this->pro_append->findOne(array('_id' => $id, 'plural.number' => (int) $products_sku[1]), array('_id' => 1));
                        if (!$plural) {
                            return false;
                        }
                    } else {
                        return false;
                    }
                    break;
                case 3:
                    $products_sku = explode(",", $sku);      
                    foreach ($products_sku as $product_sku) {
                        $product_sku = str_replace(')','\)',str_replace('(', '\(', $product_sku));
                        $pro_details = $this->pro_details->findOne(array('_id' => $id, 'details.sku' => new MongoRegex("/^$product_sku$/i")), array('_id' => 1));
                        if ($pro_details) {
                            continue;
                        } else {
                            return false;
                        }
                    }
                    break;

                default:
                    return false;
                    break;
            }
            return true;
        } else {
            return false;
        }
    }

    //获取购物车里面的信息并且组装
    public function cartPro($country, $data) {
        $this->table($country);
        if ($data) {
            foreach ($data as $key => $vo) {
                $vo['product_skudiy'] = $vo['product_sku'];
                if($vo['img_url']){
                    $vo['product_sku'] = substr($vo['product_sku'],0,strrpos($vo['product_sku'],'/'));
                }
                $id = is_object($vo ['product_id']) ? $vo ['product_id'] : new MongoId($vo ['product_id']);
                $plural = $this->findSelfBundle($id);
                $pro = $this->pro->findOne(array('_id' => $id), array('title' => 1,'children' => 1, 'seo_url' => 1, 'sku' => 1, 'image' => 1, 'price' => 1, 'bundle' => 1, 'weight' => 1,'status'=>1,'freebies'=>1,'diy'=>1));

                // 判断属于那种模式
                if (count($plural ['plural'])) {
                    if ($pro['children']) {
                        if ($vo ['bundle_type'] == 3) {
                            $result = $this->_getPro3($id, $vo, $pro, $plural ['plural'][0]['price']);
                        } else {
                            return false;
                        }
                    } else {
                        if ($vo ['bundle_type'] == 2) {
                            $result = $this->_getPro2($id, $vo, $pro, $plural ['plural']);
                        } else {
                            return false;
                        }
                    }
                } else {
                    if ($vo ['bundle_type'] == 1) {
                        $result = $this->_getPro1($id, $vo, $pro);
                    } else {
                        return false;
                    }
                }

                if ($result) {
                    $data [$key] = $result;
                } else {
                    return false;
                }
            }
            return $data;
        }
        return false;
    }

    // 常规模式获取产品信息
    public function _getPro1($id, $vo, $pro) {
        if (strstr($vo ['product_sku'], '/')) {
    
                $p_sku = str_replace(')','\)',str_replace('(', '\(', $vo ['product_sku']));

            $pro_details = $this->pro_details->findOne(array('_id' => $id, 'details.sku' =>  new MongoRegex("/^$p_sku$/i")), array('details.$' => 1,'variants'=>1));

            
            if ($pro_details) {
                $data ['product_dsku'] = $pro_details ['details'] [0] ['sku'];
                $sku= substr(strstr($pro_details ['details'] [0] ['sku'], '/'), 1);

                
                //组装映射的SKU  用户前台显示 start
      			 if(count($pro_details ['variants'])>0){
                	$variantsAttr = explode('/',$sku);
                	$data ['product_attr']='';
                	foreach ($pro_details ['variants'] as $variants){
                		$variantsValue = explode(',',$variants ['value']);
                		$variantsValueMap = explode(',',$variants ['value_map']);
                		$contrastAttr="";
                		$result=0;
                		foreach ($variantsAttr as $key=>$attr){
                			if($contrastAttr){
                				$contrastAttr.='/'.$attr;
                			}else{
                				$contrastAttr=$attr;
                			}
                			foreach ($variantsValue as $k=>$value){
                				if(strtolower($contrastAttr)==strtolower($value)){
                					if($data ['product_attr']){
                						$data ['product_attr'].='/'.$variantsValueMap[$k];
                					}else{
                						$data ['product_attr']=$variantsValueMap[$k];
                					}
                					$result=1;
                				}
                			}
                			unset($variantsAttr[$key]);
                			if($result){
                				break;
                			}
                		}
                	}
      			 }
      			//end
      			 
                $data ['product_price'] = $pro_details ['details'] [0] ['price'];
                $data ['product_DetailsStatus']=$pro_details ['details'] [0] ['status'];
                //$data ['product_bundle_price'] = $pro_details ['details'] [0] ['bundle'];
            } else {
                return false;
            }
        } else {
            if (strtolower($pro ['sku']) == strtolower($vo ['product_sku'])) {
                $data ['product_dsku'] = $pro ['sku'];
                $data ['product_attr'] = '';
                $data ['product_price'] = $pro ['price'];
                $data ['product_DetailsStatus']=1;
                //$data ['product_bundle_price'] = $pro ['bundle'];
            } else {
                return false;
            }
        }

        $data ['product_id'] = $vo ['product_id'];
        $data ['product_title'] = $pro ['title'];
        $data ['product_qty'] = $vo ['product_qty'];
        $data ['bundle_type'] = $vo ['bundle_type'];
        $data ['product_image'] = $pro ['image'];
        $data ['product_weight'] = $pro ['weight'];
        $data ['plural_price'] = 0;
//        $data ['weight_amount'] = $vo ['product_qty'] * $data ['product_weight'];
        $data ['bundle_type'] = 1;
        $data ['seo_url'] = $pro ['seo_url'];
        $data ['freebies'] = $pro ['freebies'];
        $data ['status'] = $pro ['status'];
        $data ['img_url'] = isset($vo ['img_url'])?$vo ['img_url']:'';
        $data ['product_skudiy'] = $vo ['product_skudiy'];
        $data ['diy'] = $pro ['diy'];
        
        return $data;
    }

    // 绑定模式获取产品信息  绑数量
    public function _getPro2($id, $vo, $pro, $plural) {
        $products_sku = explode(",", $vo['product_sku']);
        if (strtolower($pro ['sku']) == strtolower($products_sku[0])) {
            $data ['product_dsku'] = $vo ['product_sku'];
            $data ['product_attr'] = '×' . $products_sku[1];
            $plural_price = 0;
            foreach ($plural as $value) {
                if ($value['number'] == $products_sku[1]) {
                    $plural_price = $value['price'];
                    break;
                }
            }


            $data ['product_price'] = $pro ['price'] * $products_sku[1];

            $data ['product_id'] = $vo ['product_id'];
            $data ['product_title'] = $pro ['title'];
            $data ['product_qty'] = $vo ['product_qty'];
            $data ['bundle_type'] = $vo ['bundle_type'];
            $data ['product_image'] = $pro ['image'];
            $data ['product_weight'] = $pro ['weight'] * $products_sku[1];
            $data ['plural_price'] = $plural_price;
//            $data ['weight_amount'] = $vo ['product_qty'] * $data ['product_weight'];
            $data ['bundle_type'] = 2;
            $data ['seo_url'] = $pro ['seo_url'];
            $data ['freebies'] = $pro ['freebies'];
            $data ['img_url'] = isset($vo ['img_url'])?$vo ['img_url']:'';
            $data ['product_skudiy'] = $vo ['product_skudiy'];
            $data ['diy'] = $pro ['diy'];

            return $data;
        } else {
            return false;
        }
    }

    // 绑定模式获取产品信息  绑自己
    public function _getPro3($id, $vo, $pro, $plural_price) {
        $products_sku = explode(",", $vo['product_sku']);
        $data = array();
        $price = 0;
        $product_attr = '';
        foreach ($products_sku as $product_sku) {
            $product_sku = str_replace(')','\)',str_replace('(', '\(', $product_sku));
            $pro_details = $this->pro_details->findOne(array('_id' => $id, 'details.sku' => new MongoRegex("/^$product_sku$/i")), array('details.$' => 1));
            if ($pro_details) {
                $price += $pro_details ['details'] [0] ['price'];
                $product_attr.= substr($product_sku, strrpos($product_sku, '/') + 1) . ',';
            } else {
                return false;
            }
        }

        $data ['product_dsku'] = $vo ['product_sku'];
        $data ['product_attr'] = $product_attr;
        $data ['product_price'] = $price;
        //$data ['product_bundle_price'] = 0;
        $data ['product_id'] = $vo ['product_id'];
        $data ['product_title'] = $pro ['title'];
        $data ['product_qty'] = $vo ['product_qty'];
        $data ['bundle_type'] = $vo ['bundle_type'];
        $data ['product_image'] = $pro ['image'];
        $data ['product_weight'] = $pro ['weight'] * count($products_sku);
        $data ['plural_price'] = $plural_price;
//        $data ['weight_amount'] = $vo ['product_qty'] * $data ['product_weight'];
        $data ['bundle_type'] = 3;
        $data ['seo_url'] = $pro ['seo_url'];
        $data ['freebies'] = $pro ['freebies'];
        $data ['img_url'] = isset($vo ['img_url'])?$vo ['img_url']:'';
        $data ['product_skudiy'] = $vo ['product_skudiy'];
        $data ['diy'] = $pro ['diy'];
        return $data;
    }

    /*
      private funs
     */

    private function _condition() {
        $order = explode(',', $this->_options['order']);
        if ($order[1] == 'asc') {
            $condition['sort'] = array($order[0] => 1);
        } else {
            $condition['sort'] = array($order[0] => -1);
        }
        $limit = explode(',', $this->_options['limit']);
        $condition['limit'] = $limit[1];
        $condition['skip'] = $limit[0];
        return $condition;
    }

    private function _o2a($object) {
        $array = array();
        foreach ($object as $key => $vo) {
            $array[$key] = $vo;
        }
        return $array;
    }

    private function _merge($pro, $pro_append, $pro_details) {
        $array = array();
        foreach ($pro as $key => $vo) {
            if(isset($pro_append[$key])&&isset($pro_details[$key])){
                $array[] = $vo + $pro_append[$key] + $pro_details[$key];
            }elseif(isset($pro_append[$key])&&!isset($pro_details[$key])){
                $array[] = $vo + $pro_append[$key];
            }elseif(!isset($pro_append[$key])&&isset($pro_details[$key])){
                $array[] = $vo + $pro_details[$key];
            }else{
                $array[] = $vo;
            }
        }
        return $array;
    }

    //根据产品id获取绑定的商品和喜欢的商品
    public function _specialProduct($country, $limit = 20) {
        $product = $this->CI->mongo->selectCollection($country . '_product');
        $result = $product->find(array('status'=>1))->sort(array("sold.total" => -1))->limit($limit);
        return $this->_o2a($result);
    }

    //获取指定产品的推荐产品
    function getRcommend($country = 'US', $pro_list = array()) {
        if (empty($pro_list))
            return false;
        if (!is_array($pro_list))
            $pro_list = explode(',', $pro_list);
        $this->load->model('collection_model');
        $productId = $this->collection_model->getListFromAllCollectionByProductId($country, $pro_list);
        $specialProduct = $this->specialProduct($country, $productId);
        if (!empty($specialProduct)) {
            foreach ($specialProduct as $key => $value) {
                $specialProduct[$key]['collection'] = $this->collection_model->getCollectionUrl($country, (string) $value['_id']);
            }
        }
        return $specialProduct;
    }

    //获取整站的推荐产品
    function getRcommendByAll($country = 'US', $limit = 20) {
        $this->load->model('collection_model');
        $specialProduct = $this->_specialProduct($country, $limit);
        if (!empty($specialProduct)) {
            foreach ($specialProduct as $key => $value) {
                $specialProduct[$key]['collection'] = $this->collection_model->getCollectionUrl($country, (string) $value['_id']);
            }
        }
        return $specialProduct;
    }

    function erp_sku($country = 'US') {
        $this->table($country);
        $condition = array('shopping_feed' => array('$ne' => ''));
        $pro_append = $this->pro_append->find($condition, array('shopping_feed' => 1, '_id' => 1));
        $tmp = array();
        if ($pro_append) {
            foreach ($pro_append as $k => $v) {
                $t = $this->pro->findOne(array('_id' => $v['_id']), array('sku' => 1, '_id' => 0));
                if($t){
                    $pro = $this->_o2a($t);
                    $tmp[$pro['sku']] = $v['shopping_feed'];
                }
            }
        }
        return $tmp;
    }

    //统计当天产品CheckOut次数
    function checkOutTotal($countryCode, $product_id, $haveTotal) {
        if (array_search($product_id, $haveTotal) === false) {
            $dateTimePRC = new DateTime('@' . (time() + 28800), new DateTimeZone("PRC"));
            $redisKey = 'T:' .$dateTimePRC->format("Ymd") . ':' . $countryCode . ':' . $product_id;
            $this->redis->hashInc($redisKey, 'checkOut', 1);
            $this->redis->timeOut($redisKey, 259200);
            $haveTotal[] = $product_id;
        }
        return $haveTotal;
    }
    
    function isDiy($sku=''){
        if(!$sku)return 0;
        $condition = array('sku' => $sku);
        $diyarr = $this->pro->findOne($condition,array('diy'=>1,'_id'=>0));
        return isset($diyarr['diy'])&&$diyarr['diy']==1?1:0;
    }

}
