<?php
class slideshow_model extends CI_Model {
    private $CI;
    private $slide;
    // 表名
    private $_slide = '_slide';

    public function __construct() {
        $this->CI = & get_instance();
    }
    
    private function table($country) {
        $this->slide = $this->CI->mongo->selectCollection($country.$this->_slide);
    }
    
    public function select($country){
        $this->table($country);
         return $this->slide->find()->sort(array("sort"=>-1,"_id" => -1));
    }
}