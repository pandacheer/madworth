<?php
class slideshow_model extends CI_Model {
    private $CI;
    private $slide;
    // 表名
    private $_slide = '_slide';

    public function __construct(){
        $this->CI = & get_instance();
    }
    
    public function table($country){
        $this->slide = $this->CI->mongo->selectCollection($country.$this->_slide);
    }
    
    public function select($country){
        $this->table($country);
        return $this->slide->find();
    }
    
    public function findOne($country,$_id){
        $this->table($country);
        return $this->slide->find(array('collection' => $_id));
    }
    
    public function findPic($country,$_id){
        $this->table($country);
        return $this->slide->findOne(array('_id' => new MongoId($_id)));
    }
    
    public function insert($country,$array) {
        $this->table($country);
        $rs = $this->slide->insert($array);
        if($rs['ok']==1){
            return true;
        }else{
            return false;
        }
    }
    
    public function remove($country,$_id){
        $_id = is_object($_id) ? $_id : new MongoId($_id);
        $this->table($country);
        $rs = $this->slide->remove(array('_id'=>$_id));
        if($rs['ok']==1){
            return true;
        }else{
            return false;
        }
    }
    
    
    
    
    public function updateSort($country,$_id,$sort){
    	$_id = is_object($_id) ? $_id : new MongoId($_id);
    	$this->table($country);
    	$rs =$this->slide->update(array("_id" => $_id), array('$set' => array("sort" => (int)$sort)));
    	if($rs['ok']==1){
            return true;
        }else{
            return false;
        }
    }
    
    public function updateLink($country,$_id,$link){
    	$_id = is_object($_id) ? $_id : new MongoId($_id);
    	$this->table($country);
    	$rs =$this->slide->update(array("_id" => $_id), array('$set' => array("link" =>$link)));
    	if($rs['ok']==1){
            return true;
        }else{
            return false;
        }
    }
    
    
}