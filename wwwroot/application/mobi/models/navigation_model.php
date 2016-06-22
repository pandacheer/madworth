<?php

class navigation_model extends CI_Model {

    protected $CI;
    protected $db;

    public function __construct() {
        $this->CI = & get_instance();
        $this->db = $this->CI->mongo->selectCollection('Navigation');
    }

    public function getnav($country_code) {
        return $state = $this->db->findOne(array('_id' => $country_code));
    }

    public function update($json) {
        $time = time();
        $json['_id'] = $time;
        $result = $this->db->insert($json);
        if ($result['ok'] == 1) {
            $rs = $this->db->remove(array('_id' => array('$ne' => $time)));
            if ($rs['ok'] == 1) {
                return true;
            } else {
                return false;
            }
        }
    }

}
