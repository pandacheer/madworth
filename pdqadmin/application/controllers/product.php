<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class product extends Pc_Controller{

    private $country;
    private $user;
    private $per = 10; // 分页数量

    public function __construct() {
        parent::__construct();
        parent::_active('product');
        $this->country = $this->session->userdata('my_country');
        $this->user = $this->session->userdata('user_account');
    }

    public function index() {
        $this->load->model('product_model');
        $this->load->model('productcart_model');
        $this->load->model('category_model');
        $this->load->model('dropdown_model');
        $this->load->model('collection_model');
        $this->load->library('pagination');
        $data = $this->input->get();
        $condition = array();
        if (isset($data['productType']) && $data['productType'] != NULL) {
            $type = $this->category_model->getInfoByName($data['productType']);
            $condition['type'] = (int) $type['_id'];
        }

        if (isset($data['tag']) && $data['tag'] != NULL) {
            $condition['tag.Tag3'] = $data['tag'];
        }
        if (isset($data['collection']) && $data['collection'] != NULL) {
            $data['collection'] = str_replace("+", " ", $data['collection']);
            $col = $this->collection_model->listData($this->country, array('title' => $data['collection']), array('allow' => 1));
            $cids = [];
            foreach ($col as $vo) {
                $cids = $vo['allow'];
            }
            $condition['_id'] = array(
                '$in' => $cids
            );
            // $array = array_intersect($array,$cids);
        }
        if (isset($data['price']) && $data['price'] != NULL) {
            $arr = explode('-', $data['price']);
            $condition['price'] = array('$gt' => $arr[0] * 100, '$lt' => $arr[1] * 100);
        }
        if (isset($data['search']) && $data['search'] != NULL) {
            $condition['$or'] = array(
                array('title' => new MongoRegex('/' . htmlspecialchars($data['search'],ENT_COMPAT) . '/i')),
                array('sku' => new MongoRegex('/' . htmlspecialchars($data['search']) . '/i'))
            );
        }
        if (isset($data['creator']) && $data['creator'] != NULL) {
            $condition['creator'] = $data['creator'];
        }
        if (isset($data['sortBy']) && $data['sortBy'] != NULL) {
            switch ($data['sortBy']) {
                case 1:$order = '_id,desc';
                    break;
                case 2:$order = '_id,asc';
                    break;
                case 3:$order = 'sold.number,desc';
                    break;
                case 4:$order = 'price,desc';
                    break;
                case 5:$order = 'price,asc';
                    break;
            }
        } else {
            $order = '_id,desc';
        }
        if (isset($data['page'])) {
            $page = (int) $data['page'];
        } else {
            $page = 1;
        }

        $this->page['count'] = $this->product_model->count($this->country, $condition);
        $this->page['pnum'] = ceil($this->page['count'] / $this->per);
        $list = $this->product_model->order($order)->limit(($page - 1) * $this->per . ',' . $this->per)->select($this->country, $condition);
        /*
          如果该条件有数据
         */
        if ($list) {
            foreach ($list as $key => $vo) {
                $type = $this->category_model->getInfoByID($vo['type']);
                $list[$key]['typetitle'] = $type['title'];
            }
            $array = $pids = array_column($list, '_id');
            foreach ($array as $vo) {
                $key = array_search($vo, $pids);
                $this->page['list'][] = $list[$key];
            }
            // $this->page['list'] = $list;
            if ($page < 1) {
                $page = 1;
            } else if ($page > $this->page['pnum']) {
                $page = $this->page['pnum'];
            }
            if ($page == 1) {
                $this->page['page'] = '<li class="disabled"><a>First</a></li>';
                $this->page['page'] .= '<li class="disabled"><a>Prev</a></li>';
            } else {
                $this->page['page'] = '<li><a href="javascript:void(0)" class="pager-url-trigger" data-value="1">First</a></li>';
                $this->page['page'] .= '<li><a href="javascript:void(0)" class="pager-url-trigger" data-value="'.($page-1).'">Prev</a></li>';
            }
            // 一共输出10条
            if ($this->page['pnum'] > 10) {
                if ($page > 6 && $page < $this->page['pnum'] - 3) {
                    // 偏移
                    $j = $page - 5;
                } else if ($page > 6 && $page >= $this->page['pnum'] - 3) {
                    $j = $this->page['pnum'] - 9;
                } else {
                    // 从1开始
                    $j = 1;
                }
                for ($i = $j; $i <= $j + 9; $i++) {
                    if ($i == $page) {
                        $this->page['page'] .= '<li class="active"><a>' . $i . '</a></li>';
                    } else {
                        $this->page['page'] .= '<li><a href="javascript:void(0)" class="pager-url-trigger" data-value="' . $i . '">' . $i . '</a></li>';
                    }
                }
            } else {
                for ($i = 1; $i <= $this->page['pnum']; $i++) {
                    if ($i == $page) {
                        $this->page['page'] .= '<li class="active"><a>' . $i . '</a></li>';
                    } else {
                        $this->page['page'] .= '<li><a href="javascript:void(0)" class="pager-url-trigger" data-value="' . $i . '">' . $i . '</a></li>';
                    }
                }
            }
            if ($page == $this->page['pnum']) {
                $this->page['page'] .= '<li class="disabled"><a>Next</a></li>';
                $this->page['page'] .= '<li class="disabled"><a>Last</a></li>';
            } else {
                $this->page['page'] .= '<li><a href="javascript:void(0)" class="pager-url-trigger" data-value="' . ($page+1) . '">Next</a></li>';
                $this->page['page'] .= '<li><a href="javascript:void(0)" class="pager-url-trigger" data-value="' . ($this->page['pnum']) . '">Last</a></li>';
            }
        }
        /*
          组装页面
         */
        $this->page['listnumber'] = $this->productcart_model->cartCount($this->user);
        $this->page['head'] = $this->load->view('head', $this->_category, true);
        $this->page['type'] = $this->dropdown_model->category();
        $this->page['tag'] = $this->dropdown_model->tag();
        $this->page['collection'] = $this->dropdown_model->collection($this->country);
        $this->page['creator'] = $this->dropdown_model->user();
        $this->page['foot'] = $this->load->view('foot', $this->_category, true);
        $this->load->view('productlist', $this->page);
    }

    public function search() {
        $data = $this->input->post();
        $this->page['head'] = $this->load->view('head', $this->_category, true);
        $this->load->model('productcart_model');
        $this->page['listnumber'] = $this->productcart_model->cartCount($this->user);
        $this->load->model('product_model');
        $this->page['list'] = $this->product_model->search($this->country, $data['keyword']);
        $this->page['foot'] = $this->load->view('foot', $this->_category, true);
        $this->load->view('productlist', $this->page);
    }

    public function add() {
        $this->load->model('dropdown_model');
        $this->load->model('product_model');
        $this->page['head'] = $this->load->view('head', $this->_category, true);
        $this->page['user'] = $this->user;
        $this->page['type'] = $this->dropdown_model->category();
        $this->page['collection'] = $this->dropdown_model->collection($this->country);
        $this->page['countdown'] = $this->dropdown_model->countDown();
        $this->page['sku_list'] = $this->product_model->distinct($this->country,'sku');
        $this->page['foot'] = $this->load->view('foot', $this->_category, true);
        $this->load->view('productadd', $this->page);
    }

    public function insert() {
        // 获取数据
        $data = $this->input->post();
        // 价格区间
        $tag = array(
            '0' => '$0 - $9.99',
            '1' => '$10 - $19.99',
            '2' => '$20 - $29.99',
            '3' => '$30 - $39.99',
            '4' => '$40 - $69.99',
            '5' => '$70 - $99.99',
            '6' => '$100 - $199.99',
            '7' => '$200+',
        );
        $data['_id'] = new MongoId();
        // 产品主图
        // 初始化上传数据
        if (empty($data['title'])) {
            redirect('Showerror/index/please enter title');
        }
        if ($data['sku'] == NULL) {
            redirect('Showerror/index/please enter sku');
        }
        if (empty($data['cost'])) {
            redirect('Showerror/index/please cost price');
        }
        if (empty($data['type'])) {
            redirect('Showerror/index/please product type');
        }
        $this->load->model('product_model');
        $hasExists = $this->product_model->hasExists($this->country, '', $data['sku'], $data['seo_url']);
        if ($hasExists) {
            redirect('Showerror/index/sku or seo_url has exists');
        }
        
        // 检查图片并上传
        $data['pics'] = array();
        $error = array();
        if(!empty($_FILES['pic'])||!empty($_FILES['thumb'])){
            $url = $_SERVER['DOCUMENT_ROOT'] . '/../uploads/product/' . $data['sku'];
            if (!file_exists($url)) {
                mkdir($url, 0777,true);
            }
            $config['allowed_types'] = 'jpg|jpeg';
            $config['overwrite'] = TRUE;
            $config['upload_path'] = $url;
            $config['max_width'] = '1200';
            $config['max_height'] = '1200';
            $this->load->library('upload');
        }
        if (!empty($_FILES['pic'])) {
            $a = $_FILES['pic'];
            foreach ($a['error'] as $k1 => $v1) {
                if ($v1 == 0) {
                    $_FILES['pic'] = array('error' => $v1, 'name' => $a['name'][$k1], 'size' => $a['size'][$k1], 'tmp_name' => $a['tmp_name'][$k1], 'type' => $a['type'][$k1]);
                    $config['file_name'] = md5($a['name'][$k1] . time());
                    $this->upload->initialize($config);
                    if ($this->upload->do_upload('pic')) {
                        $updata = $this->upload->data();
                        $data['pics'][$k1]['img'] = '/product/' . $data['sku'] . '/' . $updata['file_name'];
                        $data['pics'][$k1]['sort'] = $k1 + 1;
                        if ($k1 == 0) {
                            $data['image'] = $data['pics'][0]['img'];
                        }
                    } else {
                        $error[] = $this->upload->display_errors();
                    }
                }
            }
        }
        // 产品略缩图
        if (isset($_FILES['thumb'])) {
            $config['max_width'] = '460';
            $config['max_height'] = '300';
            if ($_FILES['thumb']['error'] == 0) {
                $config['file_name'] = $data['sku'];
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('thumb')) {
                    $error = array('error' => $this->upload->display_errors());
                    redirect('Showerror/index/' . $error['error']);
                }
            }
        }
        // 生成默认映射名称
        if ($data['variants'][0]['option'] != NULL) {
            $data['children'] = 1;
            foreach ($data['variants'] as $key => $vo) {
                $vCount = count(explode(',',$data['variants'][$key]['value']));
                if(/*strpos($vo['value'],'"')!==false||  */substr_count($vo['value'],',')!=$vCount-1){
                    redirect('Showerror/index/属性value不能包含特殊符号');
                }
                $data['variants'][$key]['option_map'] = $vo['option'];
                $data['variants'][$key]['value_map'] = $vo['value'];
                if ($vCount != count(explode(',',$data['variants'][$key]['value_map']))) {
                    redirect('Showerror/index/options value do not match');
                }
            }
        }
        if (empty($data['seo']['title'])) {
            $data['seo']['title'] = $data['title'];
        }
        if (empty($data['seo_url'])) {
            $data['seo_url'] = $data['title'];
        }
        if (!empty($data['relativeproduct'])) {
            $data['relativeproduct'] = explode(',', $data['relativeproduct']);
        }else{
            $data['relativeproduct'] = array();
        }
        // seourl过滤，过滤规则由朱健提供
        $data['seo_url'] = str_replace(array('&','#','%','"','?','/','\'','\\',' '), array('','','','','','','','','-'), $data['seo_url']);
        $data['seo_url'] = trim($data['seo_url'],'-');
        $data['seo_url'] = preg_replace ("/\-+/", "-", $data['seo_url']);
        $data['creator'] = $this->user;
        if(isset($data['description'])&&!empty($data['description'])){
            $data['description'] = $this->replachtmlimage($data['description']);
        }
        if(isset($data['specification'])&&!empty($data['specification'])){
            $data['specification'] = $this->replachtmlimage($data['specification']);
        }
        if(isset($data['topreview'])&&!empty($data['topreview'])){
            $data['topreview'] = $this->replachtmlimage($data['topreview']);
        }
        // 载入模型
        $this->load->model('category_model');
        $this->load->model('shipformula_model');
        $this->load->model('country_model');
        $this->load->model('collection_model');
        $this->load->model('countdown_model');
        $this->load->model('tag_model');
        $countries = $this->country_model->getCountryCodeSet();
        $rate = $this->country_model->getCountryList('au_rate');
        if ($data['type'] != NULL) {
            $type = $this->category_model->productType($data['type']);
            $data['type'] = $type;
        }
        if ($data['sold']['init'] != NULL) {
            $data['sold']['total'] = $data['sold']['init'];
        }
        if(isset($data['freebies'])){
            $data['freebies'] = 1;
        }else{
            $data['freebies'] = 0;
        }
        if(isset($data['GF_enable'])&&$data['GF_enable']>0){
            $data['GF_enable'] = 1;
            if(empty($data['GF_color'])){
                redirect('Showerror/index/google feed color not empty');
            }
            if(empty($data['GF_size'])){
                redirect('Showerror/index/google feed size not empty');
            }
            if(!in_array($data['GF_gender'],array('male','female','unisex'))){
                redirect('Showerror/index/google feed gender error');
            }
            if(!in_array($data['GF_agegroup'],array('newborn','infant','toddler','kids','adult'))){
                redirect('Showerror/index/google feed agegroup error');
            }
        }else{
            $data['GF_enable'] = 0;
        }
        if (isset($data['diy']) && $data['diy'] == 1) {
            $data['diy'] = 1;
        } else {
            $data['diy'] = 0;
        }
        $i = 0;
        //$this->db->trans_start();
        foreach ($countries as $country) {
            $new = $data;
            // 如果有子属性
            if (isset($new['details'])&&!empty($new['details'])) {
                $i = 0;
                $new['cost']*=100;
                // 40%毛利率 = / 0.6
                    $gross = round(1 - $new['gross'] / 100, 1);
                    if ($gross <= 0 || $gross >= 1) {
                        $gross = 0.6;
                        $new['gross'] = 40;
                    }
                    
                // 获取子属性
                foreach ($new['details'] as $key => $vo) {
                    $cost = $vo['price'];
                    $weight = (isset($vo['weight'])&&$vo['weight']>0)?$vo['weight']:$new['weight'];
                    $ship = $this->shipformula_model->calculateShipping($country, $weight);
                    $price = ceil(($cost + $ship * 100) / $gross / $this->RMBtoAU * $rate[$country]);
                    // 虚假售价 220%
                    $original = ceil($price * 2.2);
                    // 默认捆绑售价 85%
                    $bundle = ceil($price * 0.85);
                    $new['details'][$key]['cost'] = $cost;
                    $new['details'][$key]['price'] = $price;
                    $new['details'][$key]['original'] = $original;
                    $new['details'][$key]['bundle'] = $bundle;
                }
                $details = $new['details'];
                array_multisort($details, SORT_ASC);
                $new['price'] = $details[0]['price'];
                $new['original'] = $details[0]['original'];
                $new['bundle'] = $details[0]['bundle'];
                $price = $details[0]['price'];
                // 如果没有子属性
            } else {
                $new['cost'] *= 100;
                $cost = $new['cost'];
                $weight = $new['weight'];
                $ship = $this->shipformula_model->calculateShipping($country, $weight);
                // 40%毛利率 = / 0.6
                $gross = round(1 - $new['gross'] / 100, 1);
                if ($gross <= 0 || $gross >= 1) {
                    $gross = 0.6;
                    $new['gross'] = 40;
                }
                $new['price'] = ceil(($cost + $ship * 100) / $gross / $this->RMBtoAU * $rate[$country]);
                $price = $new['price'];
                // 虚假售价 220%
                $new['original'] = ceil($new['price'] * 2.2);
                // 默认捆绑售价 85%
                $new['bundle'] = ceil($new['price'] * 0.85);
            }
            // 用最低价格计算tag1标签
            if ($price >= 0 && $price <= 999) {
                $new['tag']['Tag1'] = $tag[0];
            } else if ($price >= 1000 && $price <= 1999) {
                $new['tag']['Tag1'] = $tag[1];
            } else if ($price >= 2000 && $price <= 2999) {
                $new['tag']['Tag1'] = $tag[2];
            } else if ($price >= 3000 && $price <= 3999) {
                $new['tag']['Tag1'] = $tag[3];
            } else if ($price >= 4000 && $price <= 6999) {
                $new['tag']['Tag1'] = $tag[4];
            } else if ($price >= 7000 && $price <= 9999) {
                $new['tag']['Tag1'] = $tag[5];
            } else if ($price >= 10000 && $price <= 19999) {
                $new['tag']['Tag1'] = $tag[6];
            } else {
                $new['tag']['Tag1'] = $tag[7];
            }
            // 废弃，自动计算Tag1标签，不再支持手动输入
            // $new['tag']['Tag1'] = explode(',',$new['tag']['Tag1']);
            $new['tag']['Tag2'] = explode(',', $new['tag']['Tag2']);
            $new['tag']['Tag2'] = array_unique($new['tag']['Tag2']);
            $new['tag']['Tag3'] = explode(',', $new['tag']['Tag3']);
            $new['tag']['Tag3'] = array_unique($new['tag']['Tag3']);
            $this->tag_model->addTag($country, $new['tag']);
            $this->collection_model->addOneProduct($new['_id'], $country, $new['collection']);
            $this->countdown_model->addOneProduct($new['_id'], $country, $new['countdown']);
            $this->product_model->insert($country, $new);
        }
        //$this->db->trans_complete();
        header('location:/product');
    }

    public function edit($_id) {
        $this->load->model('product_model');
        $this->load->model('category_model');
        $this->load->model('collection_model');
        $this->load->model('dropdown_model');
        $this->load->model('countdown_model');
        $this->page['head'] = $this->load->view('head', $this->_category, true);
        $this->page['pro'] = $this->product_model->findOne($this->country, $_id);
        $data = $this->category_model->getInfoByID($this->page['pro']['type']);
        if (is_array($data) && isset($data['title'])) {
            $this->page['pro']['type'] = $data['title'];
        }
        $data = $this->collection_model->getListByProductId($this->country, $_id);
        $arr = array();
        if ($data) {
            foreach ($data as $vo) {
                $arr[] = $vo['_id'];
            }
        }
        $this->page['col'] = $arr;
        $this->page['type'] = $this->dropdown_model->category();
        $this->page['collection'] = $this->dropdown_model->collection($this->country);
        $this->page['countdown'] = $this->dropdown_model->countDown();
        $this->page['cur_countdown'] = $this->countdown_model->getInfoByProductId($this->country, $_id);
        $this->page['foot'] = $this->load->view('foot', $this->_category, true);
        $this->page['last'] = $this->product_model->last($this->country, $this->page['pro']['_id']);
        $this->page['next'] = $this->product_model->next($this->country, $this->page['pro']['_id']);
        $d = $this->collection_model->getCollectionByProductId($this->country, $_id);
        $this->page['d'] = '';
        if (!empty($d)) {
            foreach ($d as $key => $value) {
                $this->page['d'] = $value['seo_url'];
                break;
            }
        }
        if (is_array($this->page['pro']['tag']['Tag1'])) {
            $this->page['pro']['tag']['Tag1'] = isset($this->page['pro']['tag']['Tag1'][0]) ? $this->page['pro']['tag']['Tag1'][0] : '';
        }
        $this->load->model('country_model');
        $countryInfo = $this->country_model->getInfoByCode($this->country, array('domain'));
        $this->load->model('language_model');
        $this->page['language'] = $this->language_model->listData();
        foreach ($this->page['language'] as $key => $language_code){
            $c = $this->country_model->getCountryByLangCode($key);
            $c = array_diff($c,array($this->country));
            $country[$key] = $c;
        }
        $this->page['country'] = $country;
        $this->page['sku_list'] = $this->product_model->distinct($this->country,'sku');
        $this->page['domain'] = $countryInfo['domain'];
        $this->page['self_id'] = $_id;
        $this->load->view('productedit', $this->page);
    }

    public function update() {
        $data = $this->input->post();
        if (empty($data['_id'])) {
            redirect('Showerror/index/id error');
        }
        $_id = $data['_id'];
        $updatecountry = array($this->country);
        foreach ($data as $key => $value) {
            if (substr_count($key, 'lang') > 0) {
                $updatecountry = array_merge_recursive($updatecountry,$data[$key]);
            }
        }
        $updatecountry = array_unique($updatecountry);
        // 价格区间
        $tag = array(
            '0' => '$0 - $9.99',
            '1' => '$10 - $19.99',
            '2' => '$20 - $29.99',
            '3' => '$30 - $39.99',
            '4' => '$40 - $69.99',
            '5' => '$70 - $99.99',
            '6' => '$100 - $199.99',
            '7' => '$200+',
        );
        if (empty($data['seo']['title'])) {
            $data['seo']['title'] = $data['title'];
        }
        if (empty($data['seo_url'])) {
            $data['seo_url'] = $data['title'];
        }
        if (!empty($data['relativeproduct'])) {
            $data['relativeproduct'] = explode(',', $data['relativeproduct']);
        }else{
            $data['relativeproduct'] = array();
        }
        $this->load->model('product_model');
        $this->load->model('category_model');
        $this->load->model('collection_model');
        $this->load->model('countdown_model');
        $this->load->model('tag_model');
        // Title及Description转义
        $data['description'] = $this->replachtmlimage($data['description']);
        $data['specification'] = $this->replachtmlimage($data['specification']);
        $data['topreview'] = $this->replachtmlimage($data['topreview']);
        $strip_tags_desc = htmlspecialchars(substr(strip_tags(htmlspecialchars_decode(str_replace(array("\r\n", "\r", "\n"),' ',$data['description']))),0,160));
        $data['title'] = htmlspecialchars($data['title'], ENT_COMPAT);
        $data['description'] = htmlspecialchars($data['description'], ENT_COMPAT);
        $data['specification'] = htmlspecialchars($data['specification'], ENT_COMPAT);
        $data['topreview'] = htmlspecialchars($data['topreview'],ENT_COMPAT);
        $data['shopping_feed'] = htmlspecialchars($data['shopping_feed'],ENT_COMPAT);
        $data['seo']['title'] = isset($data['seo']['title']) ? htmlspecialchars($data['seo']['title']) : '';
        $data['seo']['description'] = (isset($data['seo']['description'])&&!empty($data['seo']['description'])) ? htmlspecialchars(substr(strip_tags(htmlspecialchars_decode(str_replace(array("\r\n", "\r", "\n"),' ',$data['seo']['description']))),0,160)) : $strip_tags_desc;
        $data['seo']['keyword'] = isset($data['seo']['keyword']) ? htmlspecialchars($data['seo']['keyword']) : '';
        if (empty($data['title'])) {
            redirect('Showerror/index/please enter title');
        }
        if ($data['sku'] == NULL) {
            redirect('Showerror/index/please enter sku');
        }
        if (empty($data['cost'])) {
            redirect('Showerror/index/please cost price');
        }
        $data['seo_url'] = str_replace(array('&','#','%','"','?','/','\'','\\',' '), array('','','','','','','','','-'), $data['seo_url']);
        $data['seo_url'] = trim($data['seo_url'],'-');
        $data['seo_url'] = preg_replace ("/\-+/", "-", $data['seo_url']);
        if(isset($data['creator']))unset($data['creator']);
        
        if(!empty($updatecountry)){
            foreach($updatecountry as $kc=>$vc){
                $pro = $this->product_model->findPro($vc,array('sku'=>$data['sku']));
                if(!empty($pro)){
                    $datas[$vc] = (string)$pro['_id'];
                }else{
                    $datas[$vc] = $data['_id'];
                }
                $old = $this->product_model->findOne($vc, $datas[$vc]);
                if (empty($old)) {
                    redirect('Showerror/index/'.$vc.' product not exists');
                }
                $soldtotal[$vc] = $data['sold']['init'] + $old['sold']['number'];
                $olds['tag'][$vc] = $old['tag'];
                $hasExists = $this->product_model->hasExists($vc, $datas[$vc], $data['sku'], $data['seo_url']);
                if ($hasExists) {
                    redirect('Showerror/index/'.$vc.' sku or seo_url has exists');
                }
            }
        }
        // 主表价格
        $data['cost']*=100;
        $data['price']*=100;
        // 定义最低价，后面如果有子属性会覆盖
        $price = $data['price'];
        $data['original']*=100;
        $data['bundle']*=100;
        $data['gross'] = (int)$data['gross']>0?(int)$data['gross']:40;
        // Mapping映射
        $data['children'] = 0;
        if (isset($data['variants'])) {
            $variants = array();
            $i = 0;
            foreach ($data['variants'] as $key => $vo) {
                if ($vo['option'] != NULL) {
                    $value = explode(',', $vo['value']);
                    $vCount = count($value);
                    if(/*strpos($vo['value'],'"')!==false||  */substr_count($vo['value'],',')!=$vCount-1){
                        redirect('Showerror/index/属性value不能包含特殊符号');
                    }
                    $value_map = explode(',', $data['variants'][$key]['value_map']);
                    $mCount = count($value_map);
                    if ($vCount != $mCount) {
                        redirect('Showerror/index/' . base64_encode('options value do not match<br><a href="/product/edit/' . $_id . '">BACK</a>'));
                    }
                    $variants[$i] = $vo;
                    $i++;
                }
            }
            $data['variants'] = $variants;
        }
        if (isset($data['details'])) {
            $details = array();
            $j = 0;
            foreach ($data['details'] as $key => $vo) {
                if ($vo['sku'] != NULL) {
                    $vo['price'] *= 100;
                    $vo['original'] *= 100;
                    $vo['bundle'] *= 100;
                    $vo['cost'] *= 100;
                    $vo['weight'] = (isset($vo['weight'])&&$vo['weight']>0)?$vo['weight']:$data['weight'];
                    $details[$j] = $vo;
                    $j++;
                }
            }
            $data['details'] = $details;
            if ($i > 0 && $j > 0) {
                $data['children'] = 1;
                // 用子属性里最低价格覆盖price变量
                array_multisort($details, SORT_ASC);
                $price = $details[0]['price'];
                $data['original'] = $details[0]['original'];
                $data['bundle'] = $details[0]['bundle'];
                $data['price'] = $price;
            }
        }
        if(isset($data['freebies'])){
            $data['freebies'] = 1;
        }else{
            $data['freebies'] = 0;
        }
        if(isset($data['GF_enable'])&&$data['GF_enable']>0){
            $data['GF_enable'] = 1;
            if(empty($data['GF_color'])){
                redirect('Showerror/index/google feed color not empty');
            }
            if(empty($data['GF_size'])){
                redirect('Showerror/index/google feed size not empty');
            }
            if(!in_array($data['GF_gender'],array('male','female','unisex'))){
                redirect('Showerror/index/google feed gender error');
            }
            if(!in_array($data['GF_agegroup'],array('newborn','infant','toddler','kids','adult'))){
                redirect('Showerror/index/google feed agegroup error');
            }
        }else{
            $data['GF_enable'] = 0;
        }
        if (isset($data['diy']) && $data['diy'] == 1) {
            $data['diy'] = 1;
        } else {
            $data['diy'] = 0;
        }
        // 用最低价格计算tag1标签
        if ($price >= 0 && $price <= 999) {
            $data['tag']['Tag1'] = $tag[0];
        } else if ($price >= 1000 && $price <= 1999) {
            $data['tag']['Tag1'] = $tag[1];
        } else if ($price >= 2000 && $price <= 2999) {
            $data['tag']['Tag1'] = $tag[2];
        } else if ($price >= 3000 && $price <= 3999) {
            $data['tag']['Tag1'] = $tag[3];
        } else if ($price >= 4000 && $price <= 6999) {
            $data['tag']['Tag1'] = $tag[4];
        } else if ($price >= 7000 && $price <= 9999) {
            $data['tag']['Tag1'] = $tag[5];
        } else if ($price >= 10000 && $price <= 19999) {
            $data['tag']['Tag1'] = $tag[6];
        } else {
            $data['tag']['Tag1'] = $tag[7];
        }
        
        
        if (isset($_FILES['thumb'])) {
            $url = $_SERVER['DOCUMENT_ROOT'] . '/../uploads/product/' . $data['sku'] . '/';
            if (!file_exists($url)) {
                mkdir($url, 0777,true);
            }
            $config['allowed_types'] = 'jpg|jpeg';
            $config['overwrite'] = TRUE;
            $config['upload_path'] = $url;
            $this->load->library('upload');
            $config['max_width'] = '460';
            $config['max_height'] = '300';
            if ($_FILES['thumb']['error'] == 0) {
                $config['file_name'] = $data['sku'];
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('thumb')) {
                    $error = array('error' => $this->upload->display_errors());
                    redirect('Showerror/index/' . $error['error']);
                }
            }
        }
        // 组装B\C模式售价
        if (isset($data['bnumber'])&&isset($data['bprice'])&&$data['bnumber'] != NULL && $data['bprice'] != NULL) {
            $bnumber = explode(',', $data['bnumber']);
            $bprice = explode(',', $data['bprice']);
            $i = 0;
            foreach ($bnumber as $key => $vo) {
                $data['plural'][$i]['number'] = $bnumber[$key];
                $data['plural'][$i]['price'] = $bprice[$key] * 100;
                $i++;
            }
            if ($i > 0) {
                $data['bundletype'] = 1;
            }
        } else {
            $data['plural'] = array();
            $data['bundletype'] = 0;
        }
        if ($data['type'] != NULL) {
            $type = $this->category_model->getInfoByName($data['type']);
            $data['type'] = $type['_id'];
        }
        // Tag1由价格自行决定，不再支持修改
        $data['tag']['Tag2'] = explode(',', $data['tag']['Tag2']);
        $data['tag']['Tag2'] = array_unique($data['tag']['Tag2']);
        $data['tag']['Tag3'] = explode(',', $data['tag']['Tag3']);
        $data['tag']['Tag3'] = array_unique($data['tag']['Tag3']);
        //$this->db->trans_start();
        if(!empty($updatecountry)){
            foreach($updatecountry as $kc=>$vc){
                if($vc!=$this->country){
                    unset($data['details'],$data['variants'],$data['price'],$data['original'],$data['bundle'],$data['gross']);
                }
                $data['_id'] = $datas[$vc];
                $data['sold']['total'] = $soldtotal[$vc];
                $rstag = $this->tag_model->upTag($vc, $olds['tag'][$vc], $data['tag']);
                $this->collection_model->addOneProduct($data['_id'],$vc, $data['collection']);
                if ($data['countdown'] == NULL) {
                    $this->countdown_model->clearOneProduct($data['_id'],$vc);
                } else {
                    $this->countdown_model->addOneProduct($data['_id'],$vc, $data['countdown']);
                }
                $this->product_model->update($vc, $data);
            }
        }
        //$this->db->trans_complete();
        header('location:/product/edit/' .$_id);
    }

    function duplicatePro($_id = '') {
        if (empty($_id)) {
            header('location:/product/add/' . $data['_id']);
        } else {
            $this->load->model('product_model');
            $this->load->model('category_model');
            $this->load->model('collection_model');
            $this->load->model('dropdown_model');
            $this->load->model('countdown_model');
            $this->page['head'] = $this->load->view('head', $this->_category, true);
            $this->page['pro'] = $this->product_model->findOne($this->country, $_id);
            $data = $this->category_model->getInfoByID($this->page['pro']['type']);
            if (is_array($data) && isset($data['title'])) {
                $this->page['pro']['type'] = $data['title'];
            }
            $data = $this->collection_model->getListByProductId($this->country, $_id);
            $arr = array();
            if ($data) {
                foreach ($data as $vo) {
                    $arr[] = $vo['_id'];
                }
            }
            $this->page['col'] = $arr;
            $this->page['type'] = $this->dropdown_model->category();
            $this->page['collection'] = $this->dropdown_model->collection($this->country);
            $this->page['countdown'] = $this->dropdown_model->countDown();
            $this->page['cur_countdown'] = $this->countdown_model->getInfoByProductId($this->country, $_id);
            $this->page['foot'] = $this->load->view('foot', $this->_category, true);
            $d = $this->collection_model->getCollectionByProductId($this->country, $_id);
            if (is_array($this->page['pro']['tag']['Tag1'])) {
                $this->page['pro']['tag']['Tag1'] = isset($this->page['pro']['tag']['Tag1'][0]) ? $this->page['pro']['tag']['Tag1'][0] : '';
            }
            $this->page['sku_list'] = $this->product_model->distinct($this->country,'sku');
            $this->load->view('productcopy', $this->page);
        }
    }

    public function hidden($_id) {
        $this->load->model('product_model');
        $result = $this->product_model->hidden($this->country, $_id);
        header('location:/product/?'.$_SERVER['QUERY_STRING']);
    }

    public function recover($_id) {
        $this->load->model('product_model');
        $result = $this->product_model->recover($this->country, $_id);
        header('location:/product/?'.$_SERVER['QUERY_STRING']);
    }

    /*
      黑暗方法之产品删除
     */

    public function delblackpro($_id) {
        $this->load->model('product_model');
        echo '产品ID为：' . $_id . '<br>';
        $data = $this->product_model->findOne($this->country, $_id);
        if (is_array($data)) {
            $dir = $_SERVER['DOCUMENT_ROOT'] . '/../uploads/product/' . $data['sku'];
            if (!is_dir($dir)) {
                echo '产品目录不存在！<br>';
            } else {
                if ($this->deldir($dir)) {
                    echo '产品目录删除成功！<br>';
                } else {
                    echo '产品目录删除失败！<br>';
                }
            }
            $rs = $this->product_model->delete($this->country, $_id);
            if ($rs) {
                echo '产品信息删除成功！<br>';
            } else {
                echo '产品信息删除失败！<br>';
            }
        } else {
            //die('产品不存在！');
        }
    }
    
    function delallhidden() {
        set_time_limit(0);
        $this->load->model('product_model');
        $a = $this->product_model->findhidden($this->country);
        foreach ($a as $v) {
            $this->delblackpro((string) $v['_id']);
        }
    }

    public function removethumb() {
        $data = $this->input->post();
        $link = $_SERVER['DOCUMENT_ROOT'] . '/../uploads' . $data['picurl'];
        unlink($link);
        $return = array('status' => 200, 'link' => $link);
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($return));
    }

    public function deldir($dir) {
        $dh = opendir($dir);
        while ($file = readdir($dh)) {
            if ($file != "." && $file != "..") {
                $fullpath = $dir . "/" . $file;
                if (!is_dir($fullpath)) {
                    unlink($fullpath);
                } else {
                    $this->deldir($fullpath);
                }
            }
        }
        closedir($dh);
        if (rmdir($dir)) {
            return true;
        } else {
            return false;
        }
    }
    
    function replachtmlimage($pageContents = '') {
        if (empty($pageContents)) {
            return '';
        }
        $reg = '/<img +src=[\'"](http.*?)[\'"]/i';
        preg_match_all($reg, $pageContents, $results);
        $find = $results[1];
        $replace = array();
        if (!empty($find)) {
            foreach ($find as $k => $v) {
                $v = str_replace('\\', '', $v);
                $type = getimagesize($v); //取得图片的大小，类型等
                $file_content = base64_encode(file_get_contents($v)); //base64编码
                switch ($type[2]) {//判读图片类型
                    case 1:$img_type = "gif";
                        break;
                    case 2:$img_type = "jpg";
                        break;
                    case 3:$img_type = "png";
                        break;
                }
                $replace[$k] = 'data:image/' . $img_type . ';base64,' . $file_content; //合成图片的base64编码
            }
            return str_replace($find, $replace, $pageContents);
        }
        return $pageContents;
    }
    
    function soldbydate(){
        $data = $this->input->post();
        $id = $data['id'];
        $s1 = strtotime($data['s'])?strtotime($data['s']):strtotime(date('Y-m-d'));
        $e1 = strtotime($data['e'])?strtotime($data['e'])+24*3600-1:strtotime(date('Y-m-d'))+24*3600-1;
        if($s1>$e1||!$data['s']||!$data['e']){
            $s1 = strtotime(date('Y-m-d'));
            $e1 = $s1 + 24*3600-1;
        }
        $this->load->model('product_model');
         $this->load->model('order_model');
        $tmpC = ['US', 'GB', 'AU', 'CA', 'IE', 'NZ', 'SG'];
        $sku_arr = $this->product_model->findOne($this->country,$id);
        $a = array();
        foreach($tmpC as $vc){
            $this->country_model->getInfoByCode($vc, array('domain'));
            if($vc != $this->country){
                $pro = $this->product_model->findPro($vc,array('sku'=>$sku_arr['sku']));
                //$a[$vc][0] = $this->product_model->a($vc,(string)$pro['_id'],$s,$e);
                $ts = $this->order_model->getOrderAmountbyproductId($vc,(string)$pro['_id'],array($s1,$e1),true);
                $a[$vc][0] = $ts['qty'];
                $symbol = $this->country_model->getInfoByCode($vc, array('currency_symbol'));
                $a[$vc][1] = $ts['amount']>0?$symbol['currency_symbol'].$ts['amount']:$ts['amount'];
            }else{
                //$a[$vc][0] = $this->product_model->a($vc,$id,$s,$e);
                $ts = $this->order_model->getOrderAmountbyproductId($vc,$id,array($s1,$e1),true);
                $a[$vc][0] = $ts['qty'];
                $a[$vc][1] = $ts['amount']>0?$this->session->userdata('my_currency').$ts['amount']:$ts['amount'];
            }
        }
        $html = "<tr><th style=\"width:150px;\"></th><th>US</th><th>GB</th><th>AU</th><th>CA</th><th>IE</th><th>NZ</th><th>SG</th></tr>";
        $t = array('销售量','销售额');
            foreach($t as $k=>$v){
                $html .= "<tr><td>".$v."</td>";
                foreach($tmpC as $vc){
                    $vv = isset($a[$vc][$k])?$a[$vc][$k]:0;
                    $html .= "<td style=\"width:150px;\">".$vv."</td>";
                }
                $html .= "</tr>";
            }
       
        echo $html.",".date('Y-m-d',$s1).",".date('Y-m-d',$e1);
    }

}
