<?php

class Product_model extends CI_Model {

    private $CI;
    private $pro;
    private $pro_append;
    private $pro_details;
    private $product;
    private $product_append;
    private $product_details;
    private $_product = '_product';
    private $_product_append = '_product_append';
    private $_product_details = '_product_details';
    private $_methods = array('where', 'order', 'limit');
    private $_options = array(
      'order' => '_id,desc',
      'limit' => '0,10',
    );
    private $ints = array('track', 'number', 'init', 'total', 'cost', 'gross', 'price', 'stock', 'original', 'bundle', 'bundletype', 'status', 'weight', 'children', 'sort', 'diy', 'freebies');
    private $_pro_model = array(
      '_id' => '',
      'oid' => '', // 从shopify导入时的ID，临时使用，未来可以删除
      'type' => '', // category
      'sku' => '', // 这还用说吗
      'title' => '', // 标题
      'track' => 0, // 是否追踪库存
      'sold' => array(// 销量
        'number' => 0, // 真实销量
        'init' => 0, // 初始，假销量
        'total' => 0, // 总销量
      ),
      'image' => '', // 产品首图
      'cost' => 0, // RMB成本价
      'gross' => 0, // 毛利率
      'price' => 0, // 实际售价
      'original' => 0, // 原售价
      'bundle' => 0, // 捆绑售价
      'weight' => 0, // 重量
      'seo_url' => '', // SEO_URL
      'tag' => array(
        'Tag1' => '', // Tag1
        'Tag2' => array(), // Tag2
        'Tag3' => array()       // Tag3
      ),
      'bundletype' => 0, // 绑定模式
      'children' => 0, // 是否有子属性
      'creator' => '', // 创建者
      'create_time' => '', // 创建时间
      'update_time' => '', // 更新时间
      'status' => 1, // 状态
      'freebies' => 0, //特殊产品，卖0块钱，但是要收用户$6.99的运费
      'diy' => 0//是否是定制产品
    );
    private $_pro_append_model = array(
      '_id' => '',
      'plural' => array(), // 无属性重复销售
      'description' => '', // 描述
      'specification' => '', //规格
      'topreview' => '',
      'shopping_feed' => '',
      'pics' => array(), // 图片集
      'seo' => array(// SEO信息
        'title' => '', // SEO标题
        'description' => '', // SEO描述
        'keyword' => '', //关键字
      ),
      'bundleid' => '', // 绑定ID
      'relation' => '', // 绑定关系
      'GF_enable' => 0,
      'GF_color' => '',
      'GF_size' => '',
      'GF_gender' => '',
      'GF_agegroup' => '',
      'relativeproduct' => array()
    );
    private $_pro_details_model = array(
      '_id' => '',
      'variants' => array(), // 子属性
      'details' => array()        // 子属性详情
    );

    // 链式操作
    public function __call($method, $args) {
        if (in_array(strtolower($method), $this->_methods, true)) {
            $this->_options[$method] = $args[0];
            return $this;
        }
    }

    public function __construct() {
        $this->CI = & get_instance();
    }

    // 国家选择
    public function table($country) {
        $this->product = $country . $this->_product;
        $this->product_append = $country . $this->_product_append;
        $this->product_details = $country . $this->_product_details;
        $this->pro = $this->CI->mongo->selectCollection($this->product);
        $this->pro_append = $this->CI->mongo->selectCollection($this->product_append);
        $this->pro_details = $this->CI->mongo->selectCollection($this->product_details);
    }

    // 计算数量
    public function count($country, $condition = array(), $table = 'pro') {
        $this->table($country);
        if (!in_array($table, array('pro', 'pro_append', 'pro_details'))) {
            $table = 'pro';
        }
        return $this->$table->find($condition)->count();
    }

    // 插入
    public function insert($country = 'US', $array) {
        $this->table($country);
        $time = time();
        $this->_pro_model['bundletype'] = 0;
        $this->_pro_model['create_time'] = $time;
        $this->_pro_model['update_time'] = $time;
        $strip_tags_desc = htmlspecialchars(substr(strip_tags(htmlspecialchars_decode(str_replace(array("\r\n", "\r", "\n"), ' ', $array['description']))), 0, 160));
        $array['title'] = isset($array['title']) ? htmlspecialchars($array['title'], ENT_COMPAT) : '';
        $array['description'] = isset($array['description']) ? htmlspecialchars($array['description'], ENT_COMPAT) : '';
        $array['specification'] = isset($array['specification']) ? htmlspecialchars($array['specification'], ENT_COMPAT) : '';
        $array['topreview'] = isset($array['topreview']) ? htmlspecialchars($array['topreview'], ENT_COMPAT) : '';
        $array['seo']['title'] = isset($array['seo']['title']) ? htmlspecialchars($array['seo']['title'], ENT_COMPAT) : '';
        $array['seo']['description'] = isset($array['seo']['description']) && !empty($array['seo']['description']) ? htmlspecialchars(substr(strip_tags(htmlspecialchars_decode(str_replace(array("\r\n", "\r", "\n"), ' ', $array['seo']['description']))), 0, 160)) : $strip_tags_desc;
        $array['seo']['keyword'] = isset($array['seo']['keyword']) ? htmlspecialchars($array['seo']['keyword'], ENT_COMPAT) : '';
        $array['shopping_feed'] = htmlspecialchars($array['shopping_feed'], ENT_COMPAT);
        $this->_pro_model = $this->_cover($this->_pro_model, $array);
        $this->_pro_append_model = $this->_cover($this->_pro_append_model, $array);
        $this->_pro_details_model = $this->_cover($this->_pro_details_model, $array);
        $pro_model = $this->pro->insert($this->_pro_model);
        $pro_append_model = $this->pro_append->insert($this->_pro_append_model);
        $pro_details_model = $this->pro_details->insert($this->_pro_details_model);
        //$insert_data = array('table_name'=>$this->product,'command'=>1,'data'=>json_encode($this->_pro_model));
        //$this->db->insert($this->product,$insert_data);
        //$insert_data = array('table_name'=>$this->product_append,'command'=>1,'data'=>json_encode($this->_pro_append_model));
        //$this->db->insert($this->product_append,$insert_data);
        //$insert_data = array('table_name'=>$this->product_details,'command'=>1,'data'=>json_encode($this->_pro_details_model));
        //$this->db->insert($this->product_details,$insert_data);
        if ($pro_model['ok'] == 1 && $pro_append_model['ok'] == 1 && $pro_details_model['ok'] == 1) {
            return true;
        }
        else {
            return false;
        }
    }

    // 隐藏
    public function hidden($country, $_id) {
        $this->table($country);
        $_id = new MongoId($_id);
        $condition = array('_id' => $_id);
        $update_time = time();
        $result = $this->pro->update($condition, array('$set' => array('update_time' => $update_time, 'status' => 2)));
        if ($result['ok'] == 1) {
            return true;
        }
        else {
            return false;
        }
    }

    // 恢复隐藏
    public function recover($country, $_id) {
        $this->table($country);
        $_id = new MongoId($_id);
        $condition = array('_id' => $_id);
        $update_time = time();
        $result = $this->pro->update($condition, array('$set' => array('update_time' => $update_time, 'status' => 1)));
        if ($result['ok'] == 1) {
            return true;
        }
        else {
            return false;
        }
    }

    // 删除
    public function delete($country, $_id) {
        $this->table($country);
        $condition = array('_id' => new MongoId($_id));
        $this->pro->remove($condition);
        $this->pro_append->remove($condition);
        $this->pro_details->remove($condition);
        //删除产品对应删除collection中该产品
        $collection_mongo = $this->CI->mongo->selectCollection($country . '_collection');
        $removeWhere = array('allow' => new MongoId($_id));
        $removeParam = array(
          '$pull' => array(
            'allow' => new MongoId($_id)
          )
        );
        $collection_mongo->update($removeWhere, $removeParam, array('multiple' => true));
        return true;
    }

    // 更新
    public function update($country, $array) {
        $this->table($country);
        if (!is_object($array['_id'])) {
            $array['_id'] = new MongoId($array['_id']);
        }
        $array['update_time'] = time();
        $condition = array('_id' => $array['_id']);
        $pro = $this->pro->findOne($condition);
        $pro_append = $this->pro_append->findOne($condition);
        $pro_details = $this->pro_details->findOne($condition);
        $this->_pro_model = $this->_cover($pro, $array, true);
        $this->_pro_append_model = $this->_cover($pro_append, $array, true);
        $this->_pro_details_model = $this->_cover($pro_details, $array, true);
        $pro_model = $this->pro->update($condition, $this->_pro_model);
        $pro_append_model = $this->pro_append->update($condition, $this->_pro_append_model, array('upsert' => true));
        $pro_details_model = $this->pro_details->update($condition, $this->_pro_details_model, array('upsert' => true));
        //$insert_data = array('table_name'=>$this->product,'command'=>2,'data'=>json_encode($this->_pro_model),'condition'=>  json_encode($condition));
        //$this->db->insert('mongodb_queue',$insert_data);
        //$insert_data = array('table_name'=>$this->product_append,'command'=>3,'data'=>json_encode($this->_pro_append_model),'condition'=>  json_encode($condition));
        //$this->db->insert('mongodb_queue',$insert_data);
        //$insert_data = array('table_name'=>$this->product_details,'command'=>3,'data'=>json_encode($this->_pro_details_model),'condition'=>  json_encode($condition));
        //$this->db->insert('mongodb_queue',$insert_data);
        if ($pro_model['ok'] == 1 && $pro_append_model['ok'] == 1 && $pro_details_model['ok'] == 1) {
            return true;
        }
        else {
            return false;
        }
    }

    // 查询
    public function find($country, $_ids) {
        $this->table($country);
        foreach ($_ids as $vo) {
            $arr[] = $this->findOne($country, $vo);
        }
        return $arr;
    }

    // 上一条（返回ID）
    public function last($country, $_id) {
        $this->table($country);
        $data = $this->pro->find(array('_id' => array('$lt' => $_id)))->sort(array('_id' => -1))->limit(1);
        foreach ($data as $vo) {
            return (string) $vo['_id'];
        }
    }

    // 下一条（返回ID）
    public function next($country, $_id) {
        $this->table($country);
        $data = $this->pro->find(array('_id' => array('$gt' => $_id)))->sort(array('_id' => 1))->limit(1);
        foreach ($data as $vo) {
            return (string) $vo['_id'];
        }
    }

    // 查询一条
    public function findOne($country, $_id) {
        if (empty($_id))
            return array();
        $this->table($country);
        if (!is_object($_id)) {
            if (strlen($_id) != 24) {
                return array();
            }
            $_id = new MongoId($_id);
        }
        $condition = array('_id' => $_id);
        $table_1 = $this->pro->findOne($condition);
        $table_2 = $this->pro_append->findOne($condition);
        $table_3 = $this->pro_details->findOne($condition);
        $arr = $table_1 + $table_2 + $table_3;
        if (isset($arr['details'])) {
            foreach ($arr['details'] as $key => $vo) {
                if (isset($vo['sku'])) {
                    $arr['details'][$key]['option'] = substr(strstr($vo['sku'], '/'), 1);
                }
            }
        }
        return $arr;
    }

    // 按子SKU查询
    public function findSku($country, $condition) {
        $this->table($country);
        $where = array(
          'details' => array(
            '$elemMatch' => array(
              'sku' => $condition
            )
          )
        );
        $data = $this->pro_details->findOne($where);
        if (isset($data['_id'])) {
            return true;
        }
        else {
            return false;
        }
    }

    // 按条件查询
    public function select($country, $condition = array(), $table = 'pro') {
        $this->table($country);
        $array = array();
        $chain = $this->_chain();
        if (!in_array($table, array('pro', 'pro_append', 'pro_details'))) {
            $table = 'pro';
        }
        $diff = array_values(array_diff(array('pro', 'pro_append', 'pro_details'), array($table)));
        $pro = $this->$table->find($condition)->sort($chain['sort'])->limit($chain['limit'])->skip($chain['skip']);
        if (count($pro) > 0) {
            foreach ($pro as $vo) {
                $pro_append = $this->$diff[0]->findOne(array('_id' => $vo['_id']));
                $pro_details = $this->$diff[1]->findOne(array('_id' => $vo['_id']));
                if (!$pro_append)
                    $pro_append = array();
                if (!$pro_details)
                    $pro_details = array();
                $array[] = $vo + $pro_append + $pro_details;
            }
            return $array;
        } else {
            return false;
        }
    }

    public function upPrice($country, $_id, $condition) {
        $this->table($country);
        $data = $this->findOne($country, $_id);
        if (substr($condition, 0, 2) == 'To' && strpos($condition, '%') === false) {
            $changeToprice = intval(substr($condition, 2));
            $condition = $changeToprice - $data['price'];
            if ($condition > 0)
                $condition = "+" . $condition;
            else
                $condition = "-" . abs($condition);
        }
        $percent = false;
        $To = false;
        if (strpos($condition, '%') > 0) {
            $percent = true;
            if (substr($condition, 0, 2) == 'To') {
                $To = true;
                $condition = rtrim(substr($condition, 2), '%');
                $str = $condition * $data['price'] / 100;
                $data['price'] = eval("return $str;");
                $str = $condition * $data['original'] / 100;
                $data['original'] = eval("return $str;");
                $str = $condition * $data['bundle'] / 100;
                $data['bundle'] = eval("return $str;");
            }
            else {
                $condition = rtrim($condition, '%');
                if (substr($condition, 0, 1) == '+') {
                    $str = $data['price'] . '+' . abs($condition) * $data['price'] / 100;
                    $data['price'] = eval("return $str;");
                    $str = $data['original'] . '+' . abs($condition) * $data['original'] / 100;
                    $data['original'] = eval("return $str;");
                    $str = $data['bundle'] . '+' . abs($condition) * $data['bundle'] / 100;
                    $data['bundle'] = eval("return $str;");
                }
                else {
                    $str = $data['price'] . '-' . abs($condition) * $data['price'] / 100;
                    $data['price'] = eval("return $str;");
                    $str = $data['original'] . '-' . abs($condition) * $data['original'] / 100;
                    $data['original'] = eval("return $str;");
                    $str = $data['bundle'] . '-' . abs($condition) * $data['bundle'] / 100;
                    $data['bundle'] = eval("return $str;");
                }
            }
        }
        else {
            $str = $data['price'] . $condition;
            $data['price'] = eval("return $str;");
            $str = $data['original'] . $condition;
            $data['original'] = eval("return $str;");
            $str = $data['bundle'] . $condition;
            $data['bundle'] = eval("return $str;");
        }
        $tmp = array('price' => array($data['price']), 'bundle' => array($data['bundle']), 'original' => array($data['original']));
        if (!empty($data['variants'] && !empty($data['details']))) {
            foreach ($data['details'] as $key => $vo) {
                if ($percent) {
                    if ($To) {
                        $str = $condition * $vo['price'] / 100;
                        $data['details'][$key]['price'] = eval("return $str;");
                        $str = $condition * $vo['original'] / 100;
                        $data['details'][$key]['original'] = eval("return $str;");
                        $str = $condition * $vo['bundle'] / 100;
                        $data['details'][$key]['bundle'] = eval("return $str;");
                    }
                    else {
                        if (substr($condition, 0, 1) == '+') {
                            $str = $vo['price'] . '+' . abs($condition) * $vo['price'] / 100;
                            $data['details'][$key]['price'] = eval("return $str;");
                            $str = $vo['original'] . '+' . abs($condition) * $vo['original'] / 100;
                            $data['details'][$key]['original'] = eval("return $str;");
                            $str = $vo['bundle'] . '+' . abs($condition) * $vo['bundle'] / 100;
                            $data['details'][$key]['bundle'] = eval("return $str;");
                        }
                        else {
                            $str = $vo['price'] . '-' . abs($condition) * $vo['price'] / 100;
                            $data['details'][$key]['price'] = eval("return $str;");
                            $str = $vo['original'] . '-' . abs($condition) * $vo['original'] / 100;
                            $data['details'][$key]['original'] = eval("return $str;");
                            $str = $vo['bundle'] . '-' . abs($condition) * $vo['bundle'] / 100;
                            $data['details'][$key]['bundle'] = eval("return $str;");
                        }
                    }
                }
                else {
                    $str = $vo['price'] . $condition;
                    $data['details'][$key]['price'] = eval("return $str;");
                    $str = $vo['bundle'] . $condition;
                    $data['details'][$key]['bundle'] = eval("return $str;");
                    $str = $vo['original'] . $condition;
                    $data['details'][$key]['original'] = eval("return $str;");
                }
                $tmp['price'][] = $data['details'][$key]['price'];
                $tmp['bundle'][] = $data['details'][$key]['bundle'];
                $tmp['original'][] = $data['details'][$key]['original'];
            }
        }
        $tag = array(
          '0' => '$0 - $9.99',
          '1' => '$10 - $19.99',
          '2' => '$20 - $29.99',
          '3' => '$30 - $39.99',
          '4' => '$40 - $69.99',
          '5' => '$70 - $99.99',
          '6' => '$100 - $199.99',
          '7' => '$200+',
        );
        $price = min($tmp['price']);
        $data['price'] = $price;
        $data['original'] = min($tmp['original']);
        $data['bundle'] = min($tmp['bundle']);
        if ($price >= 0 && $price <= 999) {
            $data['tag']['Tag1'] = $tag[0];
        }
        else if ($price >= 1000 && $price <= 1999) {
            $data['tag']['Tag1'] = $tag[1];
        }
        else if ($price >= 2000 && $price <= 2999) {
            $data['tag']['Tag1'] = $tag[2];
        }
        else if ($price >= 3000 && $price <= 3999) {
            $data['tag']['Tag1'] = $tag[3];
        }
        else if ($price >= 4000 && $price <= 6999) {
            $data['tag']['Tag1'] = $tag[4];
        }
        else if ($price >= 7000 && $price <= 9999) {
            $data['tag']['Tag1'] = $tag[5];
        }
        else if ($price >= 10000 && $price <= 19999) {
            $data['tag']['Tag1'] = $tag[6];
        }
        else {
            $data['tag']['Tag1'] = $tag[7];
        }
        $this->update($country, $data);
        return $data;
    }

    /* 产品导入临时使用 */

    public function ex_select($country, $condition = array()) {
        $this->pro = $this->CI->mongo->selectCollection($country . '_product_2');
        $array = array();
        $chain = $this->_chain();
        $pro = $this->pro->find($condition)->sort($chain['sort'])->limit($chain['limit'])->skip($chain['skip']);
        if (count($pro) > 0) {
            foreach ($pro as $vo) {
                // $pro_append = $this->pro_append->findOne(array('_id'=>$vo['_id']));
                // $pro_details = $this->pro_details->findOne(array('_id'=>$vo['_id']));
                // $array[] = $vo + $pro_append + $pro_details;
                return $vo;
            }
        }
        else {
            return false;
        }
    }

    /* 产品导入临时使用 */

    public function deff($country, $_id) {
        $new = $this->CI->mongo->selectCollection($country . '_product');
        $condition = array(
          '_id' => $_id
        );
        $result = $new->findOne($condition);
        if ($result) {
            return true;
        }
        else {
            return false;
        }
    }

    /* 产品导入临时使用 */

    // 按条件查询（产品导入用到，后期可以删除）
    public function findPro($country, $condition) {
        $this->table($country);
        $table_1 = $this->pro->findOne($condition);
        if (isset($table_1)) {
            $where = array(
              '_id' => $table_1['_id']
            );
            $table_2 = $this->pro_append->findOne($where);
            $table_3 = $this->pro_details->findOne($where);
            return $table_1 + $table_2 + $table_3;
        }
        else {
            return false;
        }
    }

    /*
      upimg
     */

    public function removepic($country, $_id, $key) {
        $this->table($country);
        $where = array(
          '_id' => new MongoId($_id),
        );
        $append = $this->pro_append->findOne($where);
        $pics = $append['pics'];
        $link = $pics[$key]['img'];
        unset($pics[$key]);
        $result = $this->pro_append->update($where, array(
          '$set' => array(
            'pics' => $pics
          )
            )
        );
        $i = 0;
        foreach ($pics as $vo) {
            $t[] = $vo['sort'];
            $tt[$vo['sort']] = $vo['img'];
        }
        $result1 = $this->whimage($country, $_id, $tt[min($t)]);
        if ($result['ok'] == 1 && $result1) {
            return $link;
        }
        else {
            return false;
        }
    }

    public function changesort($country, $_id, $sort) {
        $this->table($country);
        if (!is_object($_id)) {
            $where = array(
              '_id' => new MongoId($_id),
            );
        }
        else {
            $where = array(
              '_id' => $_id,
            );
        }
        $append = $this->pro_append->findOne($where);
        $pics = array();
        $i = 0;
        $t = array();
        foreach ($append['pics'] as $vo) {
            $pics[$i]['img'] = $vo['img'];
            $pics[$i]['sort'] = (int) $sort['sort'][$i];
            $t[] = (int) $sort['sort'][$i];
            $tt[(int) $sort['sort'][$i]] = $vo['img'];
            $i++;
        }
        $result = $this->pro_append->update($where, array(
          '$set' => array(
            'pics' => $pics
          )
            )
        );
        $result1 = $this->whimage($country, $_id, $tt[min($t)]);
        if ($result['ok'] == 1 && $result1) {
            return true;
        }
        else {
            return false;
        }
    }

    /*
      orders
      订单部分
     */

    public function orderPics($country, $_id) {
        $this->table($country);
        if (!is_object($_id)) {
            $_id = new MongoId($_id);
        }
        $condition = array('_id' => $_id);
        $data = $this->pro->findOne($condition, array('image' => 1));
        return $data;
    }

    /*
      private funs
     */

    // 链级操作
    private function _chain() {
        $order = explode(',', $this->_options['order']);
        if ($order[1] == 'asc') {
            $condition['sort'] = array($order[0] => 1);
        }
        else {
            $condition['sort'] = array($order[0] => -1);
        }
        $limit = explode(',', $this->_options['limit']);
        $condition['limit'] = $limit[1];
        $condition['skip'] = $limit[0];
        return $condition;
    }

    // 对象转数组
    private function _o2a($object) {
        $array = array();
        foreach ($object as $key => $vo) {
            $array[$key] = $vo;
        }
        return $array;
    }

    // 合并数组
    private function _merge($pro, $pro_append, $pro_details) {
        $array = array();
        foreach ($pro as $key => $vo) {
            $array[] = $vo + $pro_append[$key] + $pro_details[$key];
        }
        return $array;
    }

    // 组合数据
    private function _cover($data, $array, $update = false) {
        foreach ($data as $key => $vo) {
            if (isset($array[$key])) {
                if ($update) {
                    $data[$key] = $array[$key];
                }
                else {
                    if ($key == 'plural') {
                        if (!empty($array[$key])) {
                            foreach ($array[$key] as $keys => $value) {
                                $di = array_intersect_key($value, array('number' => '', 'price' => ''));
                                $data[$key][$keys] = $di;
                            }
                            unset($data[$key]['number'], $data[$key]['price']);
                        }
                    }
                    else {
                        if (is_array($vo) && is_array($array[$key])) {
                            $diff = array_diff_key($vo, $array[$key]);
                            $intersect = array_intersect_key($array[$key], $vo);
                        }
                        elseif (is_array($vo) && !is_array($array[$key])) {
                            continue;
                        }
                        elseif (!is_array($vo) && is_array($array[$key])) {
                            continue;
                        }
                        else {
                            $diff = array();
                        }
                        if (empty($diff)) {
                            $data[$key] = $array[$key];
                        }
                        elseif (!empty($intersect)) {
                            $data[$key] = array_merge($intersect, $diff);
                        }
                    }
                }
            }
        }
        return $this->_turn($data);
    }

    // 数据转换
    private function _turn($data) {
        foreach ($data as $key => $vo) {
            if (is_array($vo)) {
                $data[$key] = $this->_turn($vo);
            }
            else {
                if (in_array((string) $key, $this->ints)) {
                    if (19.99 * 100 == $vo) {
                        $data[$key] = 1999;
                    }
                    else {
                        $data[$key] = (int) $vo;
                    }
                }
            }
        };
        return $data;
    }

    //查询隐藏产品
    public function findhidden($country, $status = 2, $field = array('_id' => 1, 'sku' => 1)) {
        $this->table($country);
        if (!in_array((int) $status, array(1, 2, 3))) {
            $status = 2;
        }
        $where = array(
          'status' => $status
        );
        $arr = $this->pro->find($where, $field);
        return $arr;
    }

    //查询产品pics
    public function findPics($country, $_id) {
        $this->table($country);
        $where = array(
          '_id' => new MongoId($_id)
        );
        $arr = $this->pro_append->find($where, array('pics' => 1, '_id' => 0));
        return $arr;
    }

    //维护产品字段image
    function whimage($country, $_id, $img) {
        $this->table($country);
        $where = array(
          '_id' => new MongoId($_id)
        );
        $result = $this->pro->update($where, array('$set' => array('image' => $img)));
        if ($result['ok'] == 1) {
            return true;
        }
        return false;
    }

    //查询含有制定关键字选项的产品
    function specproduct($country = 'US', $key = 'default title') {
        $this->table($country);
        $where = array(
          '$or' => array(array('variants.value' => new MongoRegex('/' . $key . '/i')),
            array('variants.value_map' => new MongoRegex('/' . $key . '/i')))
        );
        $pro_id = $this->pro_details->find($where, array('_id' => 1));
        $id = array();
        if ($pro_id) {
            foreach ($pro_id as $key => $value) {
                $id[] = $value['_id'];
            }
        }
        $seq = intval(count($id) / 200);
        $mod = count($id) % 200;
        $pro_list = array();
        for ($j = 0; $j < $seq; $j++) {
            $start = $j * 200;
            $array = array_slice($id, $start, 200);
            $a = $this->pro->find(array('_id' => array('$in' => $array)), array('title' => 1, 'seo_url' => 1));
            foreach ($a as $v1) {
                $pro_list[] = $v1;
            }
        }
        if ($mod > 0) {
            $start = $seq * 200;
            $array = array_slice($id, $start, 200);
            $a = $this->pro->find(array('_id' => array('$in' => $array)), array('title' => 1, 'seo_url' => 1));

            foreach ($a as $v1) {
                $pro_list[] = $v1;
            }
        }
        return $pro_list;
    }

    function removespecproductattr($country = 'US', $key = 'default title') {
        $this->table($country);
        $where = array(
          '$or' => array(array('variants.value' => new MongoRegex('/' . $key . '/i')),
            array('variants.value_map' => new MongoRegex('/' . $key . '/i')))
        );
        $pro_id = $this->pro_details->find($where, array('variants' => 1));
        if ($pro_id) {
            foreach ($pro_id as $keys => $value) {
                if (!empty($value['variants'])) {
                    foreach ($value['variants'] as $k => $v) {
                        if (stripos($v['value'], $key) !== false || stripos($v['value_map'], $key) !== false) {
                            unset($value['variants'][$k]);
                        }
                    }
                    if (!empty($value['variants'])) {
                        $value['variants'] = array_values($value['variants']);
                        $this->pro_details->update(array('_id' => $value['_id']), array('$set' => array('variants' => $value['variants'])));
                    }
                    else {
                        $this->pro_details->update(array('_id' => $value['_id']), array('$set' => array('variants' => array())));
                    }
                }
            }
        }
        return true;
    }

    function nopicproduct($country = 'US') {
        $this->table($country);
        $where = array('image' => '');
        $pro_list = $this->pro->find($where, array('sku' => 1, 'type' => 1));
        if ($pro_list) {
            $type = $this->CI->mongo->selectCollection('Category');
            foreach ($pro_list as $k => $v) {
                $a = $type->findOne(array('_id' => $v['type']), array('_id' => 0));
                $v['type'] = $a['title'];
                $pro_lists[] = $v;
            }
        }
        return $pro_lists;
    }

    function noshowpicproduct($country = 'US') {
        $this->table($country);
        //$where = array('image' => array('$ne'=>''));
        $pro_list = $this->pro_append->find(array(), array('pics.img' => 1));
        if ($pro_list) {
            foreach ($pro_list as $k => $v) {
                $sku = $this->pro->find(array('_id' => $v['_id']), array('sku' => 1, '_id' => 0));
                if ($sku) {
                    $v['sku'] = iterator_to_array($sku)[0]['sku'];
                }
                $pro_lists[] = $v;
            }
        }
        return $pro_lists;
    }

    function notypeproduct($country = 'US') {
        $this->table($country);
        //$where = array('create_time'=>array('$gt'=>1450691999));
        $where = array();
        $pro_list = $this->pro->find($where, array('sku' => 1, 'type' => 1));
        $pro_lists = $_datas = array();
        if ($pro_list) {
            $type = $this->CI->mongo->selectCollection('Category');
            foreach ($pro_list as $k => $v) {
                $a = $type->find(array('_id' => (int) $v['type']))->count();
                if (!$a) {
                    $collection = $this->CI->mongo->{$country . '_collection'};
                    $where = array(
                      'allow' => $v['_id']
                    );
                    $data = $collection->find($where, array('_id' => 0, 'title' => 1));
                    $_data = iterator_to_array($data);

                    if (!empty($_data)) {
                        foreach ($_data as $k1 => $v1) {
                            $v['collection'][] = $v1['title'];
                        }
                    }
                    $pro_lists[] = $v;
                }
            }
        }
        return $pro_lists;
    }

    function getproductSimpleField($country = 'US', $field = 'sku,price,type') {
        $this->table($country);
        $_field = array();
        if (!empty($field)) {
            $field = is_string($field) ? explode(',', $field) : $field;
            foreach ($field as $key => $value) {
                $_field[$value] = 1;
            }
        }
        $pro_list = $this->pro->find(array(), $_field);
        if ($pro_list) {
            if (in_array('type', $field)) {
                $type = $this->CI->mongo->selectCollection('Category');
            }
            foreach ($pro_list as $k => $v) {
                if (in_array('type', $field)) {
                    $a = $type->findOne(array('_id' => $v['type']), array('_id' => 0));
                    $v['type'] = $a['title'];
                }
                if (in_array('price', $field)) {
                    $v['price'] = floatval($v['price'] / 100);
                }
                if (in_array('status', $field)) {
                    $v['status'] = $v['status'] == 1 ? 'in stock' : ($v['status'] == 2 ? 'hidden' : 'out of stock');
                }
                $pro_lists[] = $v;
            }
        }
        unset($pro_list);
        return $pro_lists;
    }

    //查询产品tag
    public function findTag($country, $_id) {
        $this->table($country);
        $where = array(
          '_id' => new MongoId($_id)
        );
        $arr = $this->pro->find($where, array('tag' => true, '_id' => false));
        return $arr;
    }

    function hasExists($country, $_id = '', $sku = '', $seo_url = '') {
        $this->table($country);
        if (empty($sku) && empty($seo_url)) {
            return false;
        }
        else {
            $where = array('$or' => array(array('sku' => $sku), array('seo_url' => $seo_url)));
            if (!empty($_id)) {
                $where['_id'] = array('$ne' => new MongoId($_id));
            }
            return $this->pro->find($where)->count() ? $this->pro->find($where, array('_id' => 1)) : false;
        }
    }

    function selectSku($country = 'US') {
        $this->table($country);
        $rs = $this->pro_details->find(array(), array('details.sku' => 1, 'details.status' => 1));
        $tmp = array();
        if ($rs) {
            foreach ($rs as $k => $v) {
                $_sku = $this->pro->find(array('_id' => $v['_id'], 'status' => array('$ne' => 2)), array('sku' => 1, '_id' => 0));
                $_skus = iterator_to_array($_sku);
                if (empty($_skus)) {
                    continue;
                }
                if ($v['details']) {
                    foreach ($v['details'] as $v1) {
                        if ($v1['status'] != 2) {
                            $tmp[] = $v1['sku'];
                        }
                    }
                }
                else {
                    if ($_sku) {
                        foreach ($_sku as $v2) {
                            $tmp[] = $v2['sku'];
                        }
                    }
                }
            }
        }
        return $tmp;
    }

    function selectHiddenSku($country = 'US') {
        $this->table($country);
        $rs = $this->pro_details->find(array('details.status' => array('$eq' => 2)), array('_id' => 1));
        $tmp = array();
        if ($rs) {
            foreach ($rs as $k => $v) {
                $s = $this->pro->find(array('_id' => $v['_id']), array('sku' => 1, '_id' => 0));
                $_s = iterator_to_array($s);
                $tmp[] = $_s[0]['sku'];
            }
        }
        return $tmp;
    }

    function findOneSku($country = 'US', $_id = '', $rsku = '', $douhao = false) {
        if (!$_id)
            return false;
        $this->table($country);
        $_objectid = new MongoId($_id);
        $_sku = $this->pro->find(array('_id' => $_objectid), array('sku' => 1, '_id' => 0));
        if ($_sku) {
            foreach ($_sku as $v) {
                $sku = $v['sku'];
                break;
            }
        }
        $_dsku = $this->pro_details->find(array('_id' => $_objectid));
        $right_sku = $detail_sku = $tmpright_sku = $diff1 = $diff2 = array();
        if ($_dsku) {
            foreach ($_dsku as $v1) {
                if ($douhao) {
                    if ($v1['variants']) {
                        foreach ($v1['variants'] as $v2) {
                            if ((isset($v2['value']) && (rtrim($v2['value'], ',') != $v2['value'])) || (isset($v2['value_map']) && (rtrim($v2['value_map'], ',') != $v2['value_map']))) {
                                return $_id . ',' . $sku;
                            }
                        }
                    }
                }
                else {
                    if ($v1['variants']) {
                        foreach ($v1['variants'] as $v2) {
                            if (empty($v2['value'])) {
                                continue;
                            }
                            $v_value = explode(',', strtolower($v2['value']));
                            $tmpright_sku[] = $v_value;
                        }
                    }
                    if ($v1['details'] && !$rsku) {
                        foreach ($v1['details'] as $v4) {
                            $detail_sku[] = isset($v4['sku']) ? strtolower($v4['sku']) : '';
                        }
                    }
                }
            }
            $this->load->helper('funs');
            $right_sku = combine($tmpright_sku, strtolower($sku) . '/');
        }
        if ($rsku) {
            return $right_sku;
        }
        $diff1 = array_diff($right_sku, $detail_sku);
        $diff2 = array_diff($detail_sku, $right_sku);
        if (!empty($diff1) || !empty($diff2)) {
            return $_id . ',' . $sku;
        }
        return false;
    }

    function getprobytype($country = 'US', $type_id = 0) {
        if (!$type_id)
            return false;
        $this->table($country);
        $count = $this->pro->find(array('type' => $type_id))->count();
        return $count > 0 ? true : false;
    }

    function finddetailsku($country = 'US', $_id = '') {
        if (!is_object($_id)) {
            $_id = new MongoId($_id);
        }
        $this->table($country);
        $_details = $this->pro_details->find(array('_id' => $_id), array('details' => true));
        return $_details;
    }

    function distinct($country = 'US', $field = '', $table = 'pro') {
        if (empty($field)) {
            return array();
        }
        $this->table($country);
        if (!in_array($table, array('pro', 'pro_append', 'pro_details'))) {
            $table = 'pro';
        }
        $result = $this->$table->distinct($field, array('status' => array('$in' => array(1, 3))));
        return $result;
    }

    function updatechildsku($country = 'US', $condition = array(), $updateParm = array()) {
        if (empty($updateParm))
            return false;
        $this->table($country);
        $pro_details = $this->pro_details->update($condition, $updateParm, array('multiple' => true));
        if ($pro_details['ok'] == 1) {
            return true;
        }
        return false;
    }

    function updateMainPro($country = 'US', $condition = array(), $updateParm = array()) {
        if (empty($updateParm))
            return false;
        $this->table($country);
        $pro = $this->pro->update($condition, $updateParm, array('multiple' => true));
        if ($pro['ok'] == 1) {
            return true;
        }
        return false;
    }

    function updateAppendPro($country = 'US', $condition = array(), $updateParm = array()) {
        if (empty($updateParm))
            return false;
        $this->table($country);
        $pro = $this->pro_append->update($condition, $updateParm, array('multiple' => true));
        if ($pro['ok'] == 1) {
            return true;
        }
        return false;
    }

    function product_image_queue($folder = '', $url = '', $sku = '') {
        if (empty($folder) || empty($url))
            return;
        $time = time();
        if (is_array($url) && !empty($url)) {
            foreach ($url as $v) {
                if (strpos($v, '?') !== false) {
                    $v = substr($v, 0, strpos($v, '?'));
                }
                $datas = array(
                  'folder' => $folder,
                  'url' => $v,
                );
                if ($sku) {
                    $datas['sku'] = $sku;
                }
                else {
                    $datas['sku'] = '';
                }
                $data[] = array(
                  'method' => 'downImg',
                  'data' => json_encode($datas),
                  'create_time' => $time,
                  'level' => 5,
                  'status' => 1,
                  'help' => 0
                );
            }
            $this->db->insert_batch('SYS_queue', $data);
            $this->redis->deinc('SYS_queue', 1, count($data));
        }
    }

    function a($country = 'US', $id, $s, $e) {
        if (!$id || !$s || !$e)
            return 0;
        $where = array(
          'country' => $country,
          'product' => $id,
          'date' => array(
            '$gte' => $s,
            '$lte' => $e
          )
        );
        $collection = $this->mongo->{'SYS_Total_product'};
        $result = $collection->find($where, array('sold' => 1, '_id' => 0));
        $res = iterator_to_array($result);
        $sold = 0;
        if ($res) {
            foreach ($res as $k => $v) {
                $v['sold'] = isset($v['sold']) ? $v['sold'] : 0;
                $sold += $v['sold'];
            }
        }
        return $sold;
    }

}
