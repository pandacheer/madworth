<?php

/**
 * @文件： comment
 * @时间： 2015-10-30 11:34:44
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：
 */
class comment extends MY_Controller {

    protected $myMemberID, $myMemberEmail;

    function __construct() {
        parent::__construct();
        $this->load->model('comment_model');
        $this->myMemberID = $this->session->userdata('member_id');
        $this->myMemberEmail = $this->session->userdata('member_email');
    }

    function insert() {
        $this->load->helper('language');
        $this->lang->load('sys_comment');

        $this->load->model('orderdetails_model');
        $orderDetailsInfo = $this->orderdetails_model->getInfoByID($this->page['country'], $this->input->post('details_id'), 'member_id,order_number,product_id,product_attr,product_name,comments_star');
        if (!$orderDetailsInfo) {
            exit(json_encode(array('success' => false, 'resultMessage' => lang('comment_noProduct'))));
        }
        if ($orderDetailsInfo['member_id'] != $this->myMemberID || $orderDetailsInfo['order_number'] != $this->input->post('order_number') || $orderDetailsInfo['product_id'] != $this->input->post('product_id')) {
            exit(json_encode(array('success' => false, 'resultMessage' => lang('comment_noProduct'))));
        } elseif ($orderDetailsInfo['comments_star'] > 0) {
            exit(json_encode(array('success' => false, 'resultMessage' => lang('comment_had'))));
        }
        $this->load->model('collection_model');
        $collectionList = $this->collection_model->getInfoByProID($this->page['country'], $orderDetailsInfo['product_id'], '_id');
        $collectionIDs =  array_keys( iterator_to_array($collectionList));

        
        $data = array(
            'country_code' => $this->page['country'],
            'details_id' => (int) $this->input->post('details_id'),
            'order_number' => $orderDetailsInfo['order_number'],
            'collection_id'=>$collectionIDs,
            'product_id' => $orderDetailsInfo['product_id'],
            'product_sku' => $orderDetailsInfo['product_attr'],
            'product_name' => $orderDetailsInfo['product_name'],
            'product_star' => (int) $this->input->post('star'),
            'product_comment' => $this->input->post('product_comment'),
            'commentator' => $this->session->userdata('member_name'),
        	'create_time' => time(),
            'status' => 1
        );
        
         
        if ($this->comment_model->insert($data)) {
            exit(json_encode(array('success' => TRUE,'comments_star'=>$data['product_star'])));
        } else {
            exit(json_encode(array('success' => false, 'resultMessage' => lang('comment_dbError'))));
        }
    }

}
