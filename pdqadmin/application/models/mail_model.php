<?php
class mail_model extends CI_Model{
    protected $table = 'crontabmail';
    protected $model = array(
        'status' => 0,
        'from' => '',
        'sender' => '',
        'to' => '',
        'title' => '',
        'content' => ''
    );
    
    public function __construct(){
        parent::__construct();
    }
    
    public function add($data){
        $this->db->insert($this->table,$this->_merge($data));
    }
    
    
    public function getOne(){
        $data = $this->db->where('status !=','1')->get($this->table,1,0);
        return $data->result_array();
    }
    
    public function error($id){
        $data = array(
            'status' => 1
        );
        return $this->db->where('id',$id)->update($this->table,$data);
    }
    
    public function remove($id){
        return $this->db->where('id',$id)->delete($this->table);
    }
    
    private function _merge($data){
        $array = array();
        foreach($this->model as $key => $vo){
            if(isset($data[$key])){
                $array[$key] = $data[$key];
            }
        }
        $array['time'] = time();
        return $array;
    }
}