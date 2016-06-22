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
class Tag extends Pc_Controller {

    function __construct() {
        parent::__construct();
    }

    function getCombox() {
        $this->load->model('dropdown_model');
        $tags = $this->dropdown_model->tag();

        foreach ($tags as $tag) {
            echo $tag['tag']['Tag3'] . '<br>';
        }
    }
}
