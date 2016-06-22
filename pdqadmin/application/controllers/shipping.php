<?php

/**
 * @文件： shipping
 * @时间： 2015-7-11 15:21:38
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：
 */
class Shipping extends Pc_Controller {

    var $pageCountry, $userAccount, $userID;

    function __construct() {
        parent::__construct();
        parent::_active('shipping');
        $this->pageCountry = $this->session->userdata('my_country');
        $this->userAccount = $this->session->userdata('user_account');
        $this->userID = $this->session->userdata('user_id');
    }

    function index() {
        $this->page['head'] = $this->load->view('head', $this->_category, true);
        $this->page['foot'] = $this->load->view('foot', $this->_category, true);
        $this->load->model('country_model');
        $this->page['countryArr'] = $this->country_model->getCountryList('name');
        $collection = $this->mongo->shipping;
        $this->page['shippingArr'] = $collection->find();
        $this->load->view('Shipping', $this->page);
    }

    function save() {
        if(empty($this->input->post('name'))){
            exit(json_encode(array('success' => false, 'error' => 'please enter Shipping rate name')));
        }
        if(!in_array($this->input->post('type'),array(1,2))){
            exit(json_encode(array('success' => false, 'error' => 'Criteria Error')));
        }
        if($this->input->post('showType')==''||!in_array($this->input->post('showType'),array(0,1,2))){
            exit(json_encode(array('success' => false, 'error' => 'Displayed on the Product Page Error')));
        }
        if(!preg_match('/^[0-9]+(.[0-9]{1,2})?$/', $this->input->post('min'))){
            exit(json_encode(array('success' => false, 'error' => 'Price rang Error')));
        }
        if(!preg_match('/^[0-9]+(.[0-9]{1,2})?$/', $this->input->post('max'))){
            exit(json_encode(array('success' => false, 'error' => 'Price rang Error')));
        }
        if($this->input->post('max')<$this->input->post('min')){
            exit(json_encode(array('success' => false, 'error' => 'Price rang Error')));
        }
        if(empty($this->input->post('estimated_time'))){
        	exit(json_encode(array('success' => false, 'error' => 'please enter Estimated time')));
        }
        

        $collection = $this->mongo->shipping;
        if ($this->input->post('id')) {
            $doc = array(
                '$set' => array(
                    'model.$.name' => $this->input->post('name'),
                    'model.$.type' => (int)$this->input->post('type'),
                    'model.$.min' => (int)($this->input->post('min') * 100),
                    'model.$.max' => (int)($this->input->post('max') * 100),
                    'model.$.price' => (int)($this->input->post('price') * 100),
                    'model.$.title' => $this->input->post('title'),
                	'model.$.estimated_time' => (int)$this->input->post('estimated_time'),
                    'model.$.showType' => (int)$this->input->post('showType')
                )
            );
            $whereData = array(
                '_id' => $this->input->post('country_code'),
                'model.id' => (int) $this->input->post('id')
            );
            $result = $collection->update($whereData, $doc);
        } else {
            $doc = array(
                '$push' => array(
                    'model' => array(
                        'id' => time(),
                        'name' => $this->input->post('name'),
                        'type' => (int)$this->input->post('type'),
                        'min' => (int)$this->input->post('min') * 100,
                        'max' => (int)$this->input->post('max') * 100,
                        'price' => (int)$this->input->post('price') * 100,
                        'title' => $this->input->post('title'),
                    	'estimated_time' => (int)$this->input->post('estimated_time'),
                        'showType' => (int)$this->input->post('showType')
                    ))
            );
            $whereData = array(
                '_id' => $this->input->post('country_code')
            );

            $result = $collection->update($whereData, $doc, array('upsert' => TRUE));
        }
        if ($result['ok']) {
            exit(json_encode(array('success' => true)));
        } else {
            exit(json_encode(array('success' => false, 'error' => 'Shipping操作失败！')));
        }
    }

    function delete() {
        $country_code = $this->input->post('country_code');
        $id = $this->input->post('id');
        $collection = $this->mongo->shipping;

        $removeWhere = array('_id' => $country_code);
        $removeParam = array(
            '$pull' => array(
                'model' => array('id' => (int) $id)
            )
        );
        $result = $collection->update($removeWhere, $removeParam);

        if ($result['ok']) {
            exit(json_encode(array('success' => true)));
        } else {
            exit(json_encode(array('success' => false, 'error' => 'Shipping删除失败！')));
        }
    }

}
