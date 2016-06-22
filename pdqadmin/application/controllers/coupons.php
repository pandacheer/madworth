<?php

/**
 * @文件： Coupons
 * @时间： 2015-6-16 11:53:31
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：优惠券
 */
class Coupons extends Pc_Controller {

    var $pageCountry, $userAccount, $userID;

    function __construct() {
        parent::__construct();
        parent::_active('coupons');
        $this->pageCountry = $this->session->userdata('my_country');
        $this->userAccount = $this->session->userdata('user_account');
        $this->userID = $this->session->userdata('user_id');
    }

    function index($status = 0) {
        $per_page = 10; //每页记录数
        $this->page['head'] = $this->load->view('head', $this->_category, true);
        $this->page['foot'] = $this->load->view('foot', $this->_category, true);
        $this->load->model('coupons_model');
        if ($status == 1) {
            $whereData = array('status' => 1);
        } elseif ($status == 2) {
            $whereData = array('status' => 2);
        } else {
            $status = 0;
            $whereData = array('status <' => 3);
        }
        $pagenum = ($this->uri->segment(4) === FALSE ) ? 1 : $this->uri->segment(4);
        $keyword = ($this->uri->segment(5) === FALSE ) ? '' : $this->uri->segment(5);
        if (!empty($keyword)) {
            $whereData['coupons_id like'] = '%' . $keyword . '%';
        }
        $this->page['status'] = $status;
        $fields = 'coupons_id,private,used,type,amount,condition,min,max,frequency,start,end,status,note';
        $this->page['coupons'] = $this->coupons_model->listData($this->pageCountry, $whereData, 'create_time', 'desc', ($pagenum - 1) * $per_page, $per_page, $fields);

        $total_rows = $this->coupons_model->count($this->pageCountry, $whereData);
        //分页开始
        $this->load->library('pagination');
        $config['base_url'] = base_url() . 'coupons/index/' . $status;
        $config['total_rows'] = $total_rows; //总记录数
        $config['per_page'] = $per_page; //每页记录数
        $config['num_links'] = 9; //当前页码边上放几个链接
        $config['uri_segment'] = 4; //页码在第几个uri上
        $this->pagination->initialize($config);
        $this->page['pages'] = $this->pagination->create_links();
        //分页结束
        //搜索条件赋值给前台
        $this->page['where'] = $keyword;
        $this->load->view('CouponsList', $this->page);
    }

    function loadAddPage() {
        $this->page['head'] = $this->load->view('head', $this->_category, true);
        $this->page['foot'] = $this->load->view('foot', $this->_category, true);
        $this->load->view('CouponsAdd', $this->page);
    }

    function insert() {
        if (empty($this->input->post('coupons_id'))) {
            redirect('Showerror/index/please generate Coupon code');
        }
        if (!preg_match("/^[0-9a-zA-z]{6,15}+$/i", $this->input->post('coupons_id'))) {
            return FALSE;
        }
        if (!in_array($this->input->post('type'), array(1, 2, 3))) {
            redirect('Showerror/index/Coupon type Error');
        }
        if (!in_array($this->input->post('private'), array(1, 2))) {
            redirect('Showerror/index/User Group Error');
        }
        if (!in_array($this->input->post('condition'), array(1, 2, 3))) {
            redirect('Showerror/index/Coupon condition Error');
        }
        if ($this->input->post('type') == 1) {
            $amount = $this->input->post('amount') * 100;
        } elseif ($this->input->post('type') == 2) {
            $amount = $this->input->post('amount');
        } else {
            $amount = 0;
        }
        $coupons = array(
            'coupons_id' => strtoupper($this->input->post('coupons_id')),
            'private' => $this->input->post('private'),
            'used' => 0,
            'type' => $this->input->post('type'),
            'amount' => $amount,
            'condition' => $this->input->post('condition'),
            'min' => $this->input->post('min') * 100,
            'max' => $this->input->post('max') * 100,
            'frequency' => $this->input->post('frequencyLimit') ? 0 : (int) $this->input->post('frequency'),
            'start' => strtotime($this->input->post('start')),
            'end' => $this->input->post('neverExpires') ? 2145888000 : strtotime($this->input->post('end')),
            'create_time' => time(),
            'update_time' => time(),
            'creator' => $this->userAccount,
            'note' => $this->input->post('note'),
            'display' => $this->input->post('display'),
            'status' => 1
        );
        if (!$this->input->post('frequencyLimit') && $this->input->post('frequency') < 1) {
            redirect('Showerror/index/frequency required gt 0');
        }
        if ($coupons['start'] === false || $coupons['start'] == -1) {
            redirect('Showerror/index/Coupon begins format error');
        }
        if (!$this->input->post('neverExpires') && ($coupons['end'] === false || $coupons['end'] == -1)) {
            redirect('Showerror/index/Coupon expires end of day format error');
        }
        $this->load->model('coupons_model');
        if ($this->coupons_model->insert($this->pageCountry, $coupons)) {
            redirect('coupons');
        } else {
            redirect('Showerror/index/Error');
        }
    }

    function loadEditPage($coupons_id) {
        $this->page['head'] = $this->load->view('head', $this->_category, true);
        $this->page['foot'] = $this->load->view('foot', $this->_category, true);
        $this->load->model('coupons_model');
        $this->page['coupons'] = $this->coupons_model->getInfoById($this->pageCountry, $coupons_id);
        $this->page['coupons']['coupons_id'] = $coupons_id;

        if (is_array($this->page['coupons'])) {
            if ($this->page['coupons']['type'] == 1) {
                $this->page['coupons']['amount'] = $this->page['coupons']['amount'] / 100;
            } elseif ($this->input->post('type') == 3) {
                $this->page['coupons']['amount'] = '';
            }

            $this->load->view('CouponsEdit', $this->page);
        } else {
            redirect('Showerror/index/Error');
        }
    }

    function update() {
        redirect('Showerror/index/Not allow modify');
    }

    function del() {
        if ($this->input->is_ajax_request()) {
            $coupons_id = $this->input->post('coupons_id');
            $this->load->model('coupons_model');
            if ($this->coupons_model->updateStatus($this->pageCountry, $coupons_id, 3)) {
                exit(json_encode(array('success' => true)));
            } else {
                exit(json_encode(array('success' => FALSE, 'error' => 'Coupons 删除失败！')));
            }
        } else {
            exit(json_encode(array('success' => FALSE, 'error' => 'Coupons 删除失败！')));
        }
    }

    function changeStatus() {
        if ($this->input->is_ajax_request()) {
            $coupons_id = $this->input->post('coupons_id');
            $status = $this->input->post('status');
            $newStatus = $status == 1 ? 2 : 1;
            $this->load->model('coupons_model');
            if ($this->coupons_model->updateStatus($this->pageCountry, $coupons_id, $newStatus)) {
                exit(json_encode(array('success' => true, 'status' => $newStatus)));
            } else {
                exit(json_encode(array('success' => FALSE, 'error' => 'Coupons 状态更新失败！')));
            }
        } else {
            exit(json_encode(array('success' => FALSE, 'error' => 'Coupons 状态更新失败！')));
        }
    }

    function appendUser() {
        $coupons_id = $this->input->post('coupons_id');
        $this->load->model('couponsmember_model');
        $member_emailArrIn = $this->couponsmember_model->getMembersByCouponsID($this->pageCountry, $coupons_id);
        $existEmail = $this->input->post('existEmail');
        if (isset($existEmail) && $existEmail == 1) {
            exit(join(',', $member_emailArrIn));
        }
        $this->load->model('coupons_model');
        $couponInfo = $this->coupons_model->getInfoById($this->pageCountry, $coupons_id);
        if (!is_array($couponInfo)) {
            exit(json_encode(array('success' => FALSE, 'error' => '优惠券不存在！！')));
        }
        if ($couponInfo['private'] == 2) {
            exit(json_encode(array('success' => FALSE, 'error' => '公有优惠券不可操作！！')));
        }
        $this->load->helper('checkEmail');
        $member_email = str_replace("\r\n", ',', $this->input->post('member_email'));
        $member_emailArrAdd = array_unique(array_filter(explode(',', $member_email), 'checkEmail'));
        $count = count($member_emailArrAdd);
        if (!$count) {
            exit(json_encode(array('success' => FALSE, 'error' => '邮箱列表格式不正确！！')));
        }
        $member_emailArr = array_diff($member_emailArrAdd, $member_emailArrIn);
        if (!count($member_emailArr)) {
            exit(json_encode(array('success' => FALSE, 'error' => '邮箱列表都分发过！！')));
        }
        if ($this->couponsmember_model->appendUser($this->pageCountry, $coupons_id, $member_emailArr, $couponInfo)) {
            exit(json_encode(array('success' => true, 'count' => $count)));
        } else {
            exit(json_encode(array('success' => FALSE, 'error' => '优惠券分发失败！')));
        }
    }

    function getCouponsCode() {
//        $coupons_id = strtoupper(dechex((microtime(TRUE) * 100000) + date('ymh')));
//        $coupons_id = strtoupper(dechex((microtime(TRUE) * 100000) ));
        $coupons_id = strtoupper(dechex((microtime(TRUE))));
//        $coupons_id = strtoupper(dechex((microtime(TRUE))));
//        $coupons_id= date('Y-m-d',strtotime(date('Y-m-d'))+2592000);
        echo json_encode(array('success' => TRUE, 'coupons_id' => $coupons_id));
    }

}
