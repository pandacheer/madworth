<?php

/**
 * @文件： category_model
 * @时间： 2015-6-13 16:36:03
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：获取产品分类
 */
class category_model extends CI_Model {
    private $CI;
    
    public function __construct() {
        parent::__construct();
        $this->CI = & get_instance();
    }

    // 这个不知道是干嘛的，并没有删，你自己看着办 - 邹虎
    function getCategory($country) {
        $this->cimongo->select('category');
        $docs = $this->cimongo->get($country.'_product');
        var_dump($docs);
//        $this->cimongo->where(array('package_list.goods_id' => 12));
    }
    
    // 这个是我Feeds要用的
    public function getCateById($id){
        $Category = $this->CI->mongo->selectCollection('Category');
        return $Category->findOne(array('_id' => $id));
    }
}
