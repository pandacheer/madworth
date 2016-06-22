<?php

/**
 * @文件： category_model
 * @时间： 2015-7-2 15:30:39
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：
 */
class Category_model extends CI_Model {
    private $collection;

    function __construct() {
        parent::__construct();
        $this->collection = $this->mongo->Category;
    }

    //查找分类
    function listData($whereData = array(), $fields = array()) {
        return $this->collection->find($whereData, $fields);
    }

    //插入分类
    function insert($doc) {
        return $this->collection->insert($doc);
    }

    function update($category_id, $doc) {
        return $this->collection->update(array('_id' => new MongoInt32($category_id)),$doc);
    }
   
    function productType($category_name) {
        $count = $this->collection->find(array('title'=>$category_name))->count();
        if($count > 0){
            $typeid = $this->collection->findOne(array('title'=>$category_name),array('_id'=>1));
            return new MongoInt32($typeid['_id']);
        }else{
            $time = time();
            $array = array(
                '_id' => new MongoInt32($time),
                'title' => $category_name
            );
            $this->collection->insert($array);
            return $time;
        }
    }
    
    //获取分类信息
    function getInfoByID($category_id) {
        return $this->collection->findOne(array('_id' => new MongoInt32($category_id)));
    }
    
    //获取分类ID
    function getInfoByName($category_name){
        return $this->collection->findOne(array('title' => $category_name),array('_id'=>1));
    }
    
    //删除分类ID
    function remove($category_id) {
        return $this->collection->remove(array('_id' => new MongoInt32($category_id)));
    }
}
