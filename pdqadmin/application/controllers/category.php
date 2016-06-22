<?php

/**
 * @文件： category
 * @时间： 2015-6-30 15:51:41
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：产品分类
 */
class Category extends Pc_Controller {

    function __construct() {
        parent::__construct();
        parent::_active('category');
    }

    function index() {
        $this->page['head'] = $this->load->view('head', $this->_category, true);
        $this->page['foot'] = $this->load->view('foot', $this->_category, true);
        $this->load->model('category_model');
        $whereData = array();
        $docs = $this->category_model->listData($whereData);
        $this->page['docs'] = $docs;
        $this->load->view('productTypeList', $this->page);
    }

    function loadAddPage() {
        $this->page['head'] = $this->load->view('head', $this->_category, true);
        $this->page['foot'] = $this->load->view('foot', $this->_category, true);
        $this->load->view('productTypeAdd', $this->page);
    }

    //添加产品分类
    function insert() {
        if(empty($this->input->post('title'))){
            exit('please enter product type');
        }
        $doc = array(
            '_id' => time(),
            'title' => $this->input->post('title')
        );
        $this->load->model('category_model');
        $result = $this->category_model->insert($doc);
        if ($result['ok']) {
            redirect('category');
        }
    }

    function loadEditPage($category_id) {
        $this->page['head'] = $this->load->view('head', $this->_category, true);
        $this->page['foot'] = $this->load->view('foot', $this->_category, true);
        $this->load->model('category_model');
        $this->page['categoryInfo'] = $this->category_model->getInfoByID($category_id);
        $this->load->view('productTypeEdit', $this->page);
    }

    function update() {
        if(empty($this->input->post('title'))){
            exit('please enter product type');
        }
        $doc = array(
            'title' => $this->input->post('title')
        );
        $category_id = $this->input->post('category_id');
        $this->load->model('category_model');
        $result = $this->category_model->update($category_id, $doc);
        if ($result['ok']) {
            redirect('category');
        }
    }
    function remove() {
        $category_id = $this->input->post('category_id');
        if(empty($category_id)){
            exit(json_encode(array('status'=>0,'info'=>'分类ID错误')));
        }
        $this->load->model('category_model');
        $result = $this->category_model->remove($category_id);
        if ($result['ok']) {
            exit(json_encode(array('status'=>200,'info'=>'')));
        }
    }

}
