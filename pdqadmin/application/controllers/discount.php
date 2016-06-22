<?php

/**
 * @文件： Discount
 * @时间： 2016-2-16 11:53:31
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：分类折扣
 */
class Discount extends Pc_Controller {

    var $pageCountry, $userAccount, $userID;

    function __construct() {
        parent::__construct();
        parent::_active('discount');
        $this->pageCountry = $this->session->userdata('my_country');
        $this->userAccount = $this->session->userdata('user_account');
        $this->userID = $this->session->userdata('user_id');
    }

    function index($status = 0) {
        $per_page = 10; //每页记录数
        $this->page['head'] = $this->load->view('head', $this->_category, true);
        $this->page['foot'] = $this->load->view('foot', $this->_category, true);
        $this->load->model('discount_model');
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
            $whereData['collection_id like'] = '%' . $keyword . '%';
        }
        $this->page['status'] = $status;
        $fields = 'collection_id,title,type,status';
        $this->page['discounts'] = $this->discount_model->listData($this->pageCountry, $whereData, 'collection_id', 'desc', ($pagenum - 1) * $per_page, $per_page, $fields);
        $collection = $this->mongo->{$this->pageCountry . '_collection'};

        foreach ($this->page['discounts'] as $key => $discounts) {
            $whereData2 = array('_id' => $discounts['collection_id']);
            $tmp = $collection->findOne($whereData2, ['title' => true]);
            $this->page['discounts'][$key]['collection_title'] = $tmp['title'];
        }
        $total_rows = $this->discount_model->count($this->pageCountry, $whereData);
        //分页开始
        $this->load->library('pagination');
        $config['base_url'] = base_url() . 'discount/index/' . $status;
        $config['total_rows'] = $total_rows; //总记录数
        $config['per_page'] = $per_page; //每页记录数
        $config['num_links'] = 9; //当前页码边上放几个链接
        $config['uri_segment'] = 4; //页码在第几个uri上
        $this->pagination->initialize($config);
        $this->page['pages'] = $this->pagination->create_links();
        //分页结束
        //搜索条件赋值给前台
        $this->page['where'] = $keyword;
        $this->load->view('DiscountList', $this->page);
    }

    function loadAddPage() {
        $this->page['head'] = $this->load->view('head', $this->_category, true);
        $this->page['foot'] = $this->load->view('foot', $this->_category, true);
        $collection = $this->mongo->{$this->pageCountry . '_collection'};
        $whereData = array('status' => new MongoInt32(2));
        $this->page['collections'] = $collection->find($whereData, ['title' => true]);
        $this->load->view('DiscountAdd', $this->page);
    }

    function insert() {
        $this->load->model('discount_model');
        if ($this->discount_model->getInfoById($this->pageCountry, $this->input->post('collection_id'))) {
            redirect('Showerror/index/please choose other collection');
        }
        if (empty($this->input->post('title'))) {
            redirect('Showerror/index/please input title');
        }
        if (!preg_match('/^[0-9]{1,3}:[0-9]{1,3}(,[0-9]{1,3}:[0-9]{1,3}){0,10}$/i', $this->input->post('detail'))) {
            redirect('Showerror/index/detail format error');
        } 
        $discount = array(
            'collection_id' => $this->input->post('collection_id'),
            'title' => $this->input->post('title'),
            'type' => $this->input->post('type'),
            'condition' => $this->input->post('condition'),
            'detail' => '{' . $this->input->post('detail') . '}',
            'start' => time(),
            'end' => time() + 86400,
            'creator' => $this->userAccount,
            'status' => 1
        );
        $this->load->model('discount_model');
        if ($this->discount_model->insert($this->pageCountry, $discount)) {
            redirect('discount');
        } else {
            redirect('Showerror/index/Error');
        }
    }

    function loadEditPage($collection_id) {
        $this->page['head'] = $this->load->view('head', $this->_category, true);
        $this->page['foot'] = $this->load->view('foot', $this->_category, true);
        $collection = $this->mongo->{$this->pageCountry . '_collection'};
        $whereData = array('_id' => $collection_id);
        $this->page['collection'] = $collection->findOne($whereData, ['title' => true]);
        $this->load->model('discount_model');
        $this->page['discount'] = $this->discount_model->getInfoById($this->pageCountry, $collection_id);
        if (is_array($this->page['discount'])) {
            $this->load->view('DiscountEdit', $this->page);
        } else {
            redirect('Showerror/index/Error');
        }
    }

    function update() {
        if (empty($this->input->post('title'))) {
            redirect('Showerror/index/please input title');
        }
        if (!preg_match('/^[0-9]{1,3}:[0-9]{1,3}(,[0-9]{1,3}:[0-9]{1,3}){0,10}$/i', $this->input->post('detail'))) {
            redirect('Showerror/index/detail format error');
        }   
        
        $collection_id = $this->input->post('collection_id');
        $discount = array(
            'title' => $this->input->post('title'),
            'type' => $this->input->post('type'),
            'condition' => $this->input->post('condition'),
            'detail' => '{' . $this->input->post('detail') . '}',
            'start' => time(),
            'end' => time() + 86400,
            'creator' => $this->userAccount,
            'status' => 1
        );
        $this->load->model('discount_model');
        if ($this->discount_model->update($this->pageCountry, $collection_id, $discount)) {
            redirect('discount');
        } else {
            redirect('Showerror/index/Error');
        }
    }

    function del() {
        if ($this->input->is_ajax_request()) {
            $collection_id = $this->input->post('collection_id');
            $this->load->model('discount_model');
            if ($this->discount_model->updateStatus($this->pageCountry, $collection_id, 3)) {
                exit(json_encode(array('success' => true)));
            } else {
                exit(json_encode(array('success' => FALSE, 'error' => 'Discount 删除失败！')));
            }
        } else {
            exit(json_encode(array('success' => FALSE, 'error' => 'Discount 删除失败！')));
        }
    }

    function changeStatus() {
        if ($this->input->is_ajax_request()) {
            $collection_id = $this->input->post('collection_id');
            $status = $this->input->post('status');
            $newStatus = $status == 1 ? 2 : 1;
            $this->load->model('discount_model');
            if ($this->discount_model->updateStatus($this->pageCountry, $collection_id, $newStatus)) {
                exit(json_encode(array('success' => true, 'status' => $newStatus)));
            } else {
                exit(json_encode(array('success' => FALSE, 'error' => 'Discount 状态更新失败！')));
            }
        } else {
            exit(json_encode(array('success' => FALSE, 'error' => 'Discount 状态更新失败！')));
        }
    }

}
