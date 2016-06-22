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

    function __construct() {
        parent::__construct();
    }

    function getCategory($country) {
        $this->cimongo->select('category');
        $docs = $this->cimongo->get($country.'_product');
        var_dump($docs);
//        $this->cimongo->where(array('package_list.goods_id' => 12));
    }

}
