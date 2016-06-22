<?php

class Page_model extends CI_Model {

    protected $CI;
    protected $page;
    protected $_pages = 'pages'; // 表名

    public function __construct() {
        $this->CI = & get_instance();
        $this->page = $this->CI->mongo->selectCollection($this->_pages);
    }

    public function findOne($country, $_id) {
        if (!is_int($_id)) {
            $_id = New MongoInt32($_id);
        }
        return $this->page->findOne(
                        array(
                            '_id' => $_id,
                            'country' => $country
                        )
        );
    }

    public function findSeo($country, $seourl) {
        return $this->page->findOne(array(
                    'url' => $seourl,
                    'isShow' => '1',
                    'country' => $country
                        ), array(
                    'pages_title' => 1,
                    'pages_content' => 1,
                    'seo_title' => 1,
                    'description' => 1
                ));
    }

    public function _findSeo($country, $seourl) {
        return $this->page->findOne(array(
                    'url' => $seourl,
                    'country' => $country
                        ), array(
                    'pages_content' => 1
                ));
    }

}
