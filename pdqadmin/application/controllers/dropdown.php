<?php

/**
 * @文件： dropdown
 * @时间： 2015-6-30 17:25:28
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：下拉列表
 */
class Dropdown extends Pc_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('dropdown_model');
    }

    function tag() {
        $country = $this->input->post('country');
        $tag3Arr = $this->dropdown_model->tag(array(), $country);
        $resultHtml = '';
        foreach ($tag3Arr as $tag) {
            $resultHtml.="<option value='{$tag['_id']}'>{$tag['title']}</option>";
        }
        exit($resultHtml);
    }

    function category() {
        $whereData = array();
        $country_code = 'US';
        $categoryArr = $this->dropdown_model->category($whereData, $country_code);
        $resultHtml = '';
        foreach ($categoryArr as $category) {
            $resultHtml.="<option value='{$category['_id']}'>{$category['title']}</option>";
        }
        exit($resultHtml);
    }

    function countDown() {
        return $this->dropdown_model->countDown();
    }
}
