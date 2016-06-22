<?php

/**
 * @文件： countdown
 * @时间： 2015-6-29 16:23:02
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：
 */
class Countdown extends Pc_Controller {

    function __construct() {
        parent::__construct();
        parent::_active('countdown');
    }

    function index() {
        $per_page = 10; //每页记录数
        $this->page['head'] = $this->load->view('head', $this->_category, true);
        $this->page['foot'] = $this->load->view('foot', $this->_category, true);
        if ($this->input->post()) {
            $pagenum = 1;
            $keyword = $this->input->post('txtKeyWords') ? $this->input->post('txtKeyWords') : 'ALL';
        } else {
            $pagenum = ($this->uri->segment(4) === FALSE ) ? 1 : $this->uri->segment(4);
            $keyword = urldecode($this->uri->segment(3) ? $this->uri->segment(3) : 'ALL');
        }
        if ($keyword != '' and $keyword != 'ALL') {
            $whereData['name like'] = "%$keyword%";
        } else {
            $whereData = [];
        }

        $fields = 'id,name,start,end,auto_recount,price,rate,decimal,creator,create_time,update_time,status';

        $this->load->model('countdown_model');
        $this->page['countdownList'] = $this->countdown_model->listData($whereData, 'update_time', 'desc', ($pagenum - 1) * $per_page, $per_page, $fields);
        $total_rows = $this->countdown_model->count($whereData);
        //分页开始
        $this->load->library('pagination');

        $config['base_url'] = base_url() . 'countdown/index/' . $keyword;
        $config['total_rows'] = $total_rows; //总记录数
        $config['per_page'] = $per_page; //每页记录数
        $config['num_links'] = 9; //当前页码边上放几个链接
        $config['uri_segment'] = 4; //页码在第几个uri上
        $this->pagination->initialize($config);
        $this->page['pages'] = $this->pagination->create_links();
        //分页结束
        //搜索条件赋值给前台
        $this->page['where'] = $keyword;
        $this->load->view('CountdownList', $this->page);
    }

    function loadAddPage() {
        $this->page['head'] = $this->load->view('head', $this->_category, true);
        $this->page['foot'] = $this->load->view('foot', $this->_category, true);
        $this->load->view('CountdownAdd', $this->page);
    }

    function insert() {
        if(empty($this->input->post('name'))){
            exit('please enter name');
        }
        $start = strtotime($this->input->post('start') . ' ' . $this->input->post('startTime'));
        $end = strtotime($this->input->post('end') . ' ' . $this->input->post('endTime'));
        if($start === false || $start == -1){
            redirect('Showerror/index/开始时间格式不正确');
        }
        if($end === false || $end == -1){
            redirect('Showerror/index/结束时间格式不正确');
        }
        if($end<$start){
            redirect('Showerror/index/结束时间不能早于开始时间');
        }
        if(!in_array($this->input->post('type'),array(1,2))){
            redirect('Showerror/index/Discount type error');
        }
        $doc = array(
            'name' => $this->input->post('name'),
            'start' => $start,
            'end' => $end,
        	'auto_recount' => $this->input->post('auto_recount') ? 2 : 1,
            'price' => $this->input->post('type') == 2 ? $this->input->post('credits')*100 : 0,
            'rate' => $this->input->post('type') == 1 ? $this->input->post('credits') : 0,
            'decimal' => $this->input->post('saveDecimal') ? $this->input->post('decimal') : -1,
            'creator' => $this->session->userdata('user_name'),
            'create_time' => time(),
            'update_time' => time(),
            'status' => 1
        );
        $doc['cycle'] = $doc['end'] - $doc['start'];
        $this->load->model('countdown_model');
        if ($this->countdown_model->insert($doc)) {
            redirect('/countdown');
        } else {
            redirect('Showerror/index/Error');
        }
    }

    function loadEditPage($countdown_id) {
        $this->page['head'] = $this->load->view('head', $this->_category, true);
        $this->page['foot'] = $this->load->view('foot', $this->_category, true);
        $this->load->model('countdown_model');
        $this->page['countdownInfo'] = $this->countdown_model->getInfoById($countdown_id);
        $this->load->view('CountdownEdit', $this->page);
    }

    function update() {
        $countdown_id = $this->input->post('countdown_id');
        if(empty($countdown_id)){
            exit('countdown_id error');
        }
        if(empty($this->input->post('name'))){
            exit('please enter name');
        }
        $start = strtotime($this->input->post('start') . ' ' . $this->input->post('startTime'));
        $end = strtotime($this->input->post('end') . ' ' . $this->input->post('endTime'));
        if($start === false || $start == -1){
            redirect('Showerror/index/开始时间格式不正确');
        }
        if($end === false || $end == -1){
            redirect('Showerror/index/结束时间格式不正确');
        }
        if($end<$start){
            redirect('Showerror/index/结束时间不能早于开始时间');
        }
        if(!in_array($this->input->post('type'),array(1,2))){
            redirect('Showerror/index/Discount type error');
        }
        $this->load->model('countdown_model');
        $countdown_info = $this->countdown_model->getInfoById($countdown_id);
        if($countdown_info['status'] != 1){
            redirect('Showerror/index/已经开始的倒计时不能编辑');
        }
        $doc = array(
            'name' => $this->input->post('name'),
            'start' => $start,
            'end' => $end,
            'auto_recount' => $this->input->post('auto_recount') ? 2 : 1,
            'price' => $this->input->post('type') == 2 ? $this->input->post('credits')*100 : 0,
            'rate' => $this->input->post('type') == 1 ? $this->input->post('credits') : 0,
            'decimal' => $this->input->post('saveDecimal') ? $this->input->post('decimal') : -1,
            'creator' => $this->session->userdata('user_name'),
            'create_time' => time(),
            'update_time' => time(),
            'status' => 1
        );
        $doc['cycle'] = $doc['end'] - $doc['start'];

        if ($this->countdown_model->update($countdown_id, $doc)) {
//            $this->loadEditPage($countdown_id);
            redirect('/countdown/loadEditPage/'.$countdown_id);
        } else {
           redirect('Showerror/index/Error');
        }
    }

    function delete() {
        $countdown_id = $this->input->post('countdown_id');
        if(empty($countdown_id)){
            exit('countdown_id error');
        }
        $this->load->model('countdown_model');
        $countdown_info = $this->countdown_model->getInfoById($countdown_id);
        if($countdown_info['status'] != 1){
            exit(json_encode(array('success' => FALSE, 'error' => '已经开始的倒计时不能删除！')));
        }
        if ($this->countdown_model->delete(array('id' => $countdown_id, 'status' => 1))) {
            exit(json_encode(array('success' => TRUE)));
        } else {
            exit(json_encode(array('success' => FALSE, 'error' => '倒计时删除失败！')));
        }
    }

    function changeStatus() {
        $countdown_id = (int) $this->input->post('countdown_id');
        if(empty($countdown_id)){
            exit('countdown_id error');
        }
        $status = $this->input->post('status') == 'true' ? 2 : 1;
        $this->load->model('countdown_model');
        if ($this->countdown_model->changeStatus(array('id' => $countdown_id), array('status' => $status))) {
            exit(json_encode(array('success' => TRUE)));
        } else {
            exit(json_encode(array('success' => FALSE, 'error' => '倒计时状态更改失败！')));
        }
    }

}
