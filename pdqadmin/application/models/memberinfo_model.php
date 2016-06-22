<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Memberinfo_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function inquire($template_country, $id) { //查询数据
        $state = $this->db->get_where($template_country . '_member_info', array('member_id' => $id), 1)->result_array();


        return $state;
    }

    public function insert($template_country, $data) { //添加数据
        $state = $this->db->insert($template_country . '_member_info', $data);

        return $state;
    }

    public function update($id, $data, $template_country) { //更新数据
        $this->db->where('member_id', $id);
        $state = $this->db->update($template_country . '_member_info', $data);
        return $state;
    }

}
