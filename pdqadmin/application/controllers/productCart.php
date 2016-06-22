<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class productCart extends Pc_Controller {

    private $country;
    private $user;

    public function __construct() {
        parent::__construct();
        parent::_active('product');
        $this->country = $this->session->userdata('my_country');
        $this->user = $this->session->userdata('user_account');
    }

    //添加购物车
    public function add() {
        $data = $this->input->post();
        if (empty($data)) {
            header('Content-Type:application/json; charset=utf-8');
            exit(json_encode(array('status' => 0, 'listnumber' => '请至少勾选一个产品')));
        }
        $this->load->model('productcart_model');
        if (is_array($data['_id'])) {
            foreach ($data['_id'] as $vo) {
                $this->productcart_model->add($this->user, $vo);
            }
        }
        else {
            $this->load->model('collection_model');
            $doc = $this->collection_model->getInfoById($this->country, $data['_id']);
            $products = $doc['allow'];
            if ($products) {
                foreach ($products as $vo) {
                    $this->productcart_model->add($this->user, (string) $vo);
                }
            }
        }
        $return['status'] = 200;
        $return['listnumber'] = $this->productcart_model->cartCount($this->user);
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($return));
    }

    //显示购物车
    public function index() {
        $this->page['head'] = $this->load->view('head', $this->_category, true);
        $this->load->model('product_model');
        $this->load->model('productcart_model');
        $this->load->model('language_model');
        $this->load->model('country_model');
        $this->load->model('dropdown_model');
        $this->load->model('category_model');
        $data = $this->productcart_model->getProduct($this->user);
        if ($data) {
            $this->page['list'] = $this->product_model->find($this->country, $data);
            if (is_array($this->page['list']) && !empty($this->page['list'])) {
                foreach ($this->page['list'] as $k => $v) {
                    if (!is_array($v))
                        continue;
                    $types = $this->category_model->getInfoByID($v['type']);
                    $this->page['list'][$k]['type'] = $types['title'];
                }
            }
        } else {
            $this->page['list'] = array();
        }
        $this->page['language'] = $this->language_model->listData();
        foreach ($this->page['language'] as $key => $language_code) {
            $country[$key] = $this->country_model->getCountryByLangCode($key);
        }
        $this->page['country'] = $country;
        $this->page['collection'] = $this->dropdown_model->collection($this->country, array('title' => 1));
        $this->page['countdown'] = $this->dropdown_model->countDown();
        $this->page['sku_list'] = $this->product_model->distinct($this->country, 'sku');
        $this->page['foot'] = $this->load->view('foot', $this->_category, true);
        $this->load->view('productlistedit', $this->page);
    }

    public function del($_id) {
        $this->load->model('productcart_model');
        $result = $this->productcart_model->delCart($this->user, $_id);
        header('location:/productCart');
    }

    //清除所有购物车
    public function delAll() {
        $this->load->model('productcart_model');
        $result = $this->productcart_model->del_allCart($this->user);
        header('location:/productCart');
    }

    public function upCollection() {
        $data = $this->input->post();
        $this->load->model('collection_model');
        $this->load->model('productcart_model');
        $i = 0;
        $country = array();
        foreach ($data as $key => $vo) {
            if (strstr($key, 'lang-')) {
                foreach ($data[$key] as $vi) {
                    $country[$i] = $vi;
                    $i++;
                }
            }
        }
        if (empty($country)) {
            redirect('Showerror/index/请勾选国家');
        }
        $products = $this->productcart_model->getProduct($this->user);
        if (empty($products)) {
            redirect('Showerror/index/没有需要编辑的产品');
        }
        foreach ($products as $key => $vo) {
            if (!is_object($vo)) {
                $products[$key] = new MongoId($vo);
            }
        }
        $seo_url = str_replace(array('&', '#', '%', '"', '?', '/', '\'', '\\', ' '), array('', '', '', '', '', '', '', '', '-'), $data['newcollection']);
        $seo_url = trim($seo_url, '-');
        $seo_url = preg_replace("/\-+/", "-", $seo_url);
        switch ($data['optionsRadios']) {
            case 'option1' :
                $doc = array(
                  'title' => $data['newcollection'],
                  'description' => '',
                  'model' => 1,
                  'relation' => 'AND',
                  'conditions' => array(),
                  'status' => 2,
                  'seo_url' => $seo_url,
                  'seo_title' => $data['newcollection'],
                  'seo_description' => '',
                  'sort' => 'create_time,-1',
                  'allow' => $products,
                  'disallow' => array(),
                  'creator' => $this->user,
                  'create_time' => time()
                );
                $collectionId = $this->collection_model->insert($country, $doc);
                header('location:/collection/loadEditPage/' . $collectionId);
                break;
            case 'option2' :
                $return = $this->collection_model->addProduct($products, $country, $data['existcollection']);
                header('location:/productCart');
                break;
        }
    }

    public function upPrice() {
        $data = $this->input->post();
        $this->load->model('product_model');
        $this->load->model('productcart_model');
        $this->load->model('tag_model');
        // 获取条件
        switch ($data['control']) {
            case 1:
                if (strpos($data['price'], '%') > 0) {
                    $condition = '+' . $data['price'];
                }
                else {
                    $condition = '+' . $data['price'] * 100;
                }
                break;
            case 2:
                if (strpos($data['price'], '%') > 0) {
                    $condition = '-' . $data['price'];
                }
                else {
                    $condition = '-' . $data['price'] * 100;
                }
                break;
            case 3:
                if (strpos($data['price'], '%') > 0) {
                    $condition = 'To' . $data['price'];
                }
                else {
                    $condition = 'To' . $data['price'] * 100;
                }
                break;
        }
        // 获取国家
        $i = 0;
        $country = array();
        foreach ($data as $key => $vo) {
            if (strstr($key, 'lang-')) {
                foreach ($data[$key] as $vi) {
                    $country[$i] = $vi;
                    $i++;
                }
            }
        }
        if (empty($country)) {
            redirect('Showerror/index/请勾选国家');
        }
        // 获取产品
        $products = $this->productcart_model->getProduct($this->user);
        if (empty($products)) {
            redirect('Showerror/index/没有需要编辑的产品');
        }
        foreach ($country as $vo) {
            foreach ($products as $vi) {
                $tag = $this->product_model->findTag($vo, $vi);
                $return = $this->product_model->upPrice($vo, $vi, $condition);
                foreach ($tag as $v1) {
                    $tag1 = array('Tag1' => $v1['tag']['Tag1']);
                    break;
                }
                $newTag = array('Tag1' => $return['tag']['Tag1']);
                $this->tag_model->upTag($vo, $tag1, $newTag);
            }
        }
        header('location:/productCart');
    }

    public function upCountdown() {
        $data = $this->input->post();
        $this->load->model('productcart_model');
        $this->load->model('countdown_model');
        $i = 0;
        $country = array();
        foreach ($data as $key => $vo) {
            if (strstr($key, 'lang-')) {
                foreach ($data[$key] as $vi) {
                    $country[$i] = $vi;
                    $i++;
                }
            }
        }
        if (empty($country)) {
            redirect('Showerror/index/请勾选国家');
        }
        $products = $this->productcart_model->getProduct($this->user);
        if (empty($products)) {
            redirect('Showerror/index/没有需要编辑的产品');
        }
        foreach ($country as $vo) {
            $this->countdown_model->addOneProduct($products, $vo, $data['countdown']);
        }
        header('location:/productCart');
    }

    public function upTag() {
        $data = $this->input->post();
        $this->load->model('product_model');
        $this->load->model('productcart_model');
        $this->load->model('tag_model');
        if (!empty($data['tag']['Tag2'])) {
            $tag['Tag2'] = explode(',', $data['tag']['Tag2']);
        }
        else {
            $tag['Tag2'] = array();
        }
        if (!empty($data['tag']['Tag3'])) {
            $tag['Tag3'] = explode(',', $data['tag']['Tag3']);
        }
        else {
            $tag['Tag3'] = array();
        }
        $i = 0;
        $country = array();
        foreach ($data as $key => $vo) {
            if (strstr($key, 'lang-')) {
                foreach ($data[$key] as $vi) {
                    $country[$i] = $vi;
                    $i++;
                }
            }
        }
        if (empty($country)) {
            redirect('Showerror/index/请勾选国家');
        }
        $products = $this->productcart_model->getProduct($this->user);
        if (empty($products)) {
            redirect('Showerror/index/没有需要编辑的产品');
        }
        foreach ($country as $vc) {
            foreach ($products as $vp) {
                // 取产品数据
                $pro = $this->product_model->findOne($vc, $vp);
                if (!$pro) {
                    continue;
                }
                // 更新TAG表
                $newTag['Tag2'] = array_unique($tag['Tag2']);
                $newTag['Tag3'] = array_unique($tag['Tag3']);
                $this->tag_model->upTag($vc, $pro['tag'], $newTag);
                $newTag['Tag1'] = $pro['tag']['Tag1'];
                ksort($newTag);
                $pro['tag'] = $newTag;
                $this->product_model->update($vc, $pro);
            }
        }
        header('location:/productCart');
    }

    public function upStatus() {
        $data = $this->input->post();
        $this->load->model('product_model');
        $this->load->model('productcart_model');
        if (!in_array($data['status'], array(1, 2, 3))) {
            $data['status'] = 1;
        }
        $i = 0;
        $country = array();
        foreach ($data as $key => $vo) {
            if (strstr($key, 'lang-')) {
                foreach ($data[$key] as $vi) {
                    $country[$i] = $vi;
                    $i++;
                }
            }
        }
        if (empty($country)) {
            redirect('Showerror/index/请勾选国家');
        }
        $products = $this->productcart_model->getProduct($this->user);
        if (empty($products)) {
            redirect('Showerror/index/没有需要编辑的产品');
        }
        $time = time();
        foreach ($country as $vc) {
            foreach ($products as $vp) {
                // 取产品数据
                $pro = $this->product_model->findOne($vc, $vp);
                if (!$pro) {
                    continue;
                }
                $condition = array('_id' => $pro['_id']);
                $updateParam = array('$set' => array('status' => (int) $data['status'], 'update_time' => $time));
                $this->product_model->updateMainPro($vc, $condition, $updateParam);
            }
        }
        header('location:/productCart');
    }

    public function upRelativePro() {
        $data = $this->input->post();
        if (!empty($data['relativeproduct'])) {
            $data['relativeproduct'] = explode(',', $data['relativeproduct']);
        }
        else {
            $data['relativeproduct'] = array();
        }
        $this->load->model('product_model');
        $this->load->model('productcart_model');
        $i = 0;
        $country = array();
        foreach ($data as $key => $vo) {
            if (strstr($key, 'lang-')) {
                foreach ($data[$key] as $vi) {
                    $country[$i] = $vi;
                    $i++;
                }
            }
        }
        if (empty($country)) {
            redirect('Showerror/index/请勾选国家');
        }
        $products = $this->productcart_model->getProduct($this->user);
        if (empty($products)) {
            redirect('Showerror/index/没有需要编辑的产品');
        }
        $time = time();
        foreach ($country as $vc) {
            foreach ($products as $vp) {
                // 取产品数据
                $pro = $this->product_model->findOne($vc, $vp);
                if (!$pro) {
                    continue;
                }
                $condition = array('_id' => $pro['_id']);
                $updateParam = array('$set' => array('update_time' => $time));
                $updateParam1 = array('$set' => array('relativeproduct' => $data['relativeproduct']));
                $this->product_model->updateMainPro($vc, $condition, $updateParam);
                $this->product_model->updateAppendPro($vc, $condition, $updateParam1);
            }
        }
        header('location:/productCart');
    }

}

?>