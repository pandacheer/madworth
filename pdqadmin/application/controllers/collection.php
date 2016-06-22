<?php

/**
 * @文件： collection
 * @时间： 2015-6-18 14:09:58
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：
 */
class Collection extends Pc_Controller {

    var $pageCountry, $userAccount, $userID;

    function __construct() {
        parent::__construct();
        parent::_active('collection');
        $this->pageCountry = $this->session->userdata('my_country');
        $this->userAccount = $this->session->userdata('user_account');
        $this->userID = $this->session->userdata('user_id');
    }

    function index() {
        $per_page = 10; //每页记录数
        $this->page['head'] = $this->load->view('head', $this->_category, true);
        $this->page['foot'] = $this->load->view('foot', $this->_category, true);
        $whereData['$or'] = array(array('status' => array('$lt' => '3')), array('status' => array('$lt' => 3)));
        if ($this->input->post()) {
            $pagenum = 1;
            $keyword = $this->input->post('txtKeyWords') ? $this->input->post('txtKeyWords') : 'ALL';
        } else {
            $pagenum = ($this->uri->segment(4) === FALSE ) ? 1 : $this->uri->segment(4);
            $keyword = urldecode($this->uri->segment(3) ? $this->uri->segment(3) : 'ALL');
        }
        if ($keyword != '' and $keyword != 'ALL') {
            $whereData['title'] = new MongoRegex("/{$keyword}/i");
        }
        $this->load->model('dropdown_model');
        $this->page['categoryList'] = iterator_to_array($this->dropdown_model->category());

        $this->load->model('collection_model');

        $total_rows = $this->collection_model->count($this->pageCountry, $whereData);
        //分页开始
        $this->load->library('pagination');

        $config['base_url'] = base_url() . 'collection/index/' . $keyword;
        $config['total_rows'] = $total_rows; //总记录数
        $config['per_page'] = $per_page; //每页记录数
        $config['num_links'] = 9; //当前页码边上放几个链接
        $config['uri_segment'] = 4; //页码在第几个uri上
        $this->pagination->initialize($config);
        $this->page['pages'] = $this->pagination->create_links();
        //分页结束
        $collectionArr = $this->collection_model->listData($this->pageCountry, $whereData, array(), ($pagenum - 1) * $per_page, $per_page);

        $this->load->model('collectioncountry_model');
        $doc = [];
        foreach ($collectionArr as $collection) {
            $tmp = $this->collectioncountry_model->getCountries($collection['_id']);
            $collection['country_show'] = $tmp['show'];
            $collection['country_hide'] = $tmp['hide'];
            $doc[] = $collection;
        }
        $this->page['cursor'] = $doc;
        $this->load->model('language_model');
        $language = $this->language_model->listData();
        $this->load->model('country_model');
        foreach ($language as $key => $language_code) {
            $countries[$key] = $this->country_model->getCountryByLangCode($key);
        }
        $this->page['language'] = $language;
        $this->page['country'] = $countries;
        //搜索条件赋值给前台
        $this->page['where'] = $keyword;
        $this->load->view('CollectionList', $this->page);
    }

    function loadAddPage() {
        $this->page['head'] = $this->load->view('head', $this->_category, true);
        $this->page['foot'] = $this->load->view('foot', $this->_category, true);
        $this->load->model('language_model');
        $language = $this->language_model->listData();
        $this->load->model('country_model');
        foreach ($language as $key => $language_code) {
            $country[$key] = $this->country_model->getCountryByLangCode($key);
        }
        $this->page['language'] = $language;
        $this->page['country'] = $country;
        $this->load->model('dropdown_model');
        $this->page['categoryArr'] = $this->dropdown_model->category();
        $this->load->view('CollectionAdd', $this->page);
    }

    function insert() {
        //获取选择的国家代码
//        $this->load->model('country_model');
//        $country_codes = array_keys($this->country_model->getCountryList('name'));

        if (empty($this->input->post('title'))) {
            redirect('Showerror/index/please enter title');
        }
        if (empty($this->input->post('model'))) {
            redirect('Showerror/index/please select conditions');
        }
        $country_codes = array($this->pageCountry);
        foreach ($this->input->post() as $key => $value) {
            if (substr_count($key, 'lang') > 0) {
                $country_codes = array_merge_recursive($country_codes, $this->input->post($key));
            }
        }
        $country_codes = array_unique($country_codes);
        if (empty($country_codes)) {
            redirect('Showerror/index/please select Country');
        }
        //条件模式，获取条件
        $conditions = [];
        if ($this->input->post('model') == 2) {
            for ($i = 0; $i < count($this->input->post('fields')); $i++) {
                if (!empty($this->input->post('fields')[$i]) && !empty($this->input->post('link')[$i]) && !empty($this->input->post('values')[$i])) {
                    $conditions[] = array('fields' => $this->input->post('fields')[$i], 'link' => $this->input->post('link')[$i], 'values' => $this->input->post('values')[$i]);
                }
            }
        }
        $seo_url = $this->input->post('seo_url') ? $this->input->post('seo_url') : '';
        $seo_url = str_replace(array('&', '#', '%', '"', '?', '/', '\'', '\\', ' '), array('', '', '', '', '', '', '', '', '-'), $seo_url);
        $seo_url = trim($seo_url, '-');
        $s_url = preg_replace("/\-+/", "-", $seo_url);
        if (empty($s_url)) {
            redirect('Showerror/index/please enter seo_url');
        }
        $newlast = !in_array((int) $this->input->post('newlast'), array(0, 1)) ? 0 : (int) $this->input->post('newlast');
        $doc = array(
            'title' => htmlspecialchars((string) $this->input->post('title'), ENT_COMPAT),
            'description' => htmlspecialchars($this->input->post('description'), ENT_COMPAT),
            'description2' => htmlspecialchars($this->input->post('description2'), ENT_COMPAT),
            'model' => (int) $this->input->post('model'),
            'relation' => $this->input->post('relation'),
            'conditions' => $conditions,
            'status' => 1,
            "show_comment" => (int) $this->input->post("show_comment"),
            'seo_url' => $s_url,
            'seo_title' => $this->input->post('seo_title') ? $this->input->post('seo_title') : '',
            'seo_description' => $this->input->post('seo_description') ? $this->input->post('seo_description') : '',
            'seo_keyword' => $this->input->post('seo_keyword') ? $this->input->post('seo_keyword') : '',
            'sort' => 'create_time,-1',
            'allow' => array(),
            'disallow' => array(),
            'creator' => $this->userAccount,
            'create_time' => time(),
            'columns' => (int) $this->input->post('columns'),
            'newlast' => $newlast
        );
        $this->load->model('collection_model');
        if ($this->collection_model->insert($country_codes, $doc)) {
            redirect('collection');
        } else {
            redirect('Showerror/index/Error');
        }
    }

    function loadEditPage($collection_id) {
        $this->page['head'] = $this->load->view('head', $this->_category, true);
        $this->page['foot'] = $this->load->view('foot', $this->_category, true);
        $this->load->model('language_model');
        $tmp = $this->page['languages'] = $this->language_model->listData();
        $this->load->model('country_model');
        $language['code'] = $this->country_model->getInfoByCode($this->pageCountry, 'language_code'); //获取国家语种
        $language['name'] = $tmp[$language['code']];

        $countries = $this->country_model->getCountryByLangCode($language['code']);

        foreach ($this->page['languages'] as $key => $language_code) {
            $c = $this->country_model->getCountryByLangCode($key);
            $c = array_diff($c, array($this->pageCountry));
            $country[$key] = $c;
        }
        $this->page['country'] = $country;

        $this->page['language'] = $language;
        $this->page['countryArr'] = $countries;
        $this->load->model('collection_model');
        //获取Colletcion信息
        $doc = $this->collection_model->getInfoById($this->pageCountry, $collection_id);
        $this->load->model('dropdown_model');
        $this->page['tag3s'] = $this->dropdown_model->tag(array(), $this->pageCountry);
        $this->page['categories'] = $this->dropdown_model->category(array(), $this->pageCountry);
        if (!$doc) {
            redirect('Showerror/index/调出错误页面');
        }
        if (empty($doc['allow'])) {
            $doc['allow'] = array();
        }
        $productMongo = $this->mongo->{$this->pageCountry . '_product'};

        if ($doc['model'] == 1) {//手动模式，查找白名单里的商品信息
            $doc['sortProductID'] = $doc['allow'];
            $mongoCondtion = array(
                '_id' => array('$in' => $doc['allow'])
            );
            if ($doc['sort'] == 'manual') {
                $productInfos = $productMongo->find($mongoCondtion, array('title' => true, 'image' => TRUE, 'sku' => true));
                $doc['allow'] = iterator_to_array($productInfos);
            } else {
                $sortArr = explode(',', $doc['sort']);
                $sort = array($sortArr[0] => (int) $sortArr[1]);
                $doc['allow'] = iterator_to_array($productMongo->find($mongoCondtion, array('title' => true, 'image' => TRUE, 'sku' => true))->sort($sort));
            }
        } else {//条件模式
            //拼接条件，生成条件
            if (count($doc['conditions']) == 1) {
                $mongoCondtion = array($doc['conditions'][0]['fields'] => $doc['conditions'][0]['values']);
            } else {
                $mongoCondtion = [];
                foreach ($doc['conditions'] as $condition) {
                    if (!empty($condition['field']) && !empty($condition['link']) && !empty($condition['values'])) {
                        if ($condition['fields'] == 'type') {
                            $mongoCondtion[] = array($condition['fields'] => (int) $condition['values']);
                        } else {
                            if ($condition['link'] == 'contains') {
                                $mongoCondtion[] = array($condition['fields'] => new MongoRegex("/{$condition['values']}/"));
                            } else {
                                $mongoCondtion[] = array($condition['fields'] => $condition['values']);
                            }
                        }
                    }
                }
                if ($doc['relation'] == 'or') {
                    $mongoCondtion = array('$or' => $mongoCondtion);
                }
            }

            //手动排序，查找白名单里的商品信息
            if ($doc['sort'] == 'manual') {
                $mongoCondtionAllow = array(
                    '_id' => array('$in' => $doc['allow'])
                );
                $allowProduct = iterator_to_array($productMongo->find($mongoCondtionAllow, array('title' => true, 'image' => TRUE, 'sku' => true)));
                $searchProduct = iterator_to_array($productMongo->find($mongoCondtion, array('title' => true, 'image' => TRUE, 'sku' => true)));
                $same = array_intersect_key($allowProduct, $searchProduct); //交集
                $diff = array_diff_key($searchProduct, $same); //差集
                $doc['allow'] = array_merge($same, $diff);
            } else {
                $sortArr = explode(',', $doc['sort']);
                $sort = array($sortArr[0] => (int) $sortArr[1]);
                $doc['allow'] = iterator_to_array($productMongo->find($mongoCondtion, array('title' => true, 'image' => TRUE, 'sku' => true))->sort($sort));
            }
        }


        //获取Collection产品
        $this->page['doc'] = $doc;
//        echo '<pre>';
//        print_r($doc);
//        exit;


        $this->load->model('country_model');
        $countryInfo = $this->country_model->getInfoByCode($this->pageCountry, array('domain'));
        $this->page['domain'] = $countryInfo['domain'];

        $this->page['collection_id'] = $collection_id;
        $this->load->view('CollectionEdit', $this->page);
    }

    //改变Collection的产品排序规则
    function changeSort() {
        $sort = $this->input->post('sort');
        $collection_id = $this->input->post('collection_id');
        $productIDs = $this->input->post('keyList');
        foreach ($productIDs as $key => $value) {
            $productIDs[$key] = new MongoId($value);
        }
        $cty = $this->input->post('cty');
        if (!empty($cty)) {
            $cty = explode(',', $cty);
            $countrys = array_merge(array($this->pageCountry), $cty);
        } else {
            $countrys = array($this->pageCountry);
        }
        $this->load->model('collection_model');
        $res = 1;
        foreach ($countrys as $kc => $vc) {
            $result = $this->collection_model->changeSort($vc, $collection_id, $sort, $productIDs);
            if ($vc == $this->pageCountry) {
                $res = $result;
            }
        }
        if ($res) {
            exit(json_encode(array('success' => TRUE)));
        } else {
            exit(json_encode(array('success' => false, 'error' => '数据库操作失败！！')));
        }
    }

    //改变Collection手动排序后的排序顺序
    function updateSort() {
        $productIDs = $this->input->post('keyList');
        $collection_id = $this->input->post('collection_id');
        foreach ($productIDs as $key => $value) {
            $productIDs[$key] = new MongoId($value);
        }
        $doc = array(
            'allow' => $productIDs
        );
        $cty = $this->input->post('cty');
        if (!empty($cty)) {
            $cty = explode(',', $cty);
            $countrys = array_merge(array($this->pageCountry), $cty);
        } else {
            $countrys = array($this->pageCountry);
        }
        $results = array('ok' => 1);
        foreach ($countrys as $kc => $vc) {
            $collection = $this->mongo->{$vc . '_collection'};
            $sortarray = $collection->findOne(array("_id" => $collection_id), array('sort' => 1, '_id' => 0));
            if ($sortarray['sort'] == 'manual') {
                $result = $collection->update(array("_id" => $collection_id), array('$set' => $doc));
                if ($vc == $this->pageCountry) {
                    $results = $result;
                }
            }
        }
        exit(json_encode(array('success' => $results['ok'])));
    }

    function update() {
        $collection_id = $this->input->post('collection_id');
        if (empty($this->input->post('title'))) {
            redirect('Showerror/index/please enter title');
        }
        if (empty($this->input->post('model'))) {
            redirect('Showerror/index/please select conditions');
        }
        $this->load->model('collection_model');
        $updatecountry = array($this->pageCountry);
        foreach ($this->input->post() as $key => $value) {
            if (substr_count($key, 'lang') > 0) {
                $updatecountry = array_merge_recursive($updatecountry, $this->input->post($key));
            }
        }
        $updatecountry = array_unique($updatecountry);
        $error = array();
        $conditions = [];
        if ($this->input->post('model') == 2) {
            for ($i = 0; $i < count($this->input->post('fields')); $i++) {
                $conditions[] = array('fields' => $this->input->post('fields')[$i], 'link' => $this->input->post('link')[$i], 'values' => $this->input->post('values')[$i]);
            }
        }
        $seo_url = $this->input->post('seo_url') ? $this->input->post('seo_url') : '';
        $seo_url = str_replace(array('&', '#', '%', '"', '?', '/', '\'', '\\', ' '), array('', '', '', '', '', '', '', '', '-'), $seo_url);
        $seo_url = trim($seo_url, '-');
        $s_url = preg_replace("/\-+/", "-", $seo_url);
        if (empty($s_url)) {
            redirect('Showerror/index/please enter seo_url');
        }
        if (!in_array((int) $this->input->post('status'), array(1, 2))) {
            redirect('Showerror/index/status error');
        }
        $description = $this->replachtmlimage($this->input->post('description'));
        $description2 = $this->replachtmlimage($this->input->post('description2'));
        $newlast = !in_array((int) $this->input->post('newlast'), array(0, 1)) ? 0 : (int) $this->input->post('newlast');
        $doc = array(
            'title' => htmlspecialchars((string) $this->input->post('title'), ENT_COMPAT),
            'description' => htmlspecialchars($description, ENT_COMPAT),
            'description2' => htmlspecialchars($description2, ENT_COMPAT),
            'model' => (int) $this->input->post('model'),
            'relation' => $this->input->post('relation'),
            'conditions' => $conditions,
            'status' => (int) $this->input->post('status'),
            "show_comment" => (int) $this->input->post("show_comment"),
            'seo_url' => $s_url,
            'seo_title' => $this->input->post('seo_title') ? htmlspecialchars((string) $this->input->post('seo_title'), ENT_COMPAT) : htmlspecialchars((string) $this->input->post('title'), ENT_COMPAT),
            'seo_description' => $this->input->post('seo_description') ? (string) $this->input->post('seo_description') : '',
            'seo_keyword' => $this->input->post('seo_keyword') ? (string) $this->input->post('seo_keyword') : '',
            //'creator' => $this->userAccount,
            //'create_time' => time(),
            'columns' => (int) $this->input->post('columns'),
            'newlast' => $newlast
        );
        foreach ($updatecountry as $kc => $vc) {
            $collectionInfo = $this->collection_model->getInfoById($vc, $collection_id);
            if ($collectionInfo) {
                if (!$this->collection_model->update($vc, $collection_id, $doc)) {
                    $error[] = $vc . ' save error';
                }
            } else {
                $error[] = $vc . ' collection not exists';
            }
        }
        if (empty($error)) {
            redirect("collection/loadEditPage/$collection_id");
        } else {
            redirect('Showerror/index/' . join(',', $error));
        }
    }

//同步修改多个国家
//    function update() {
//        $collection_id = $this->input->post('collection_id');
////        $editCountry = $this->input->post('editCountry');
//        //获取需要同步的国家，有可能没有包含在已同步的国家中
//        $syncCountry = [];
//        foreach ($this->input->post() as $key => $value) {
//            if (substr_count($key, 'lang') > 0) {
//                $syncCountry = array_merge_recursive($syncCountry, $this->input->post($key));
//            }
//        }
//        if (!count($syncCountry)) {
//            exit('请选择需要要同步的国家！');
//        }
//
//        $this->load->model('collection_model');
//        //获取Collection的信息，判断是否存在
//        $collectionInfo = $this->collection_model->getInfoById($this->pageCountry, $collection_id);
//        if ($collectionInfo) {
//            //已同步的国家及状态
//            $this->load->model('collectioncountry_model');
//            $tmp = $this->collectioncountry_model->getCountries($collection_id);
//            $syncCountry_have = array_unique(array_merge($tmp['show'], $tmp['hide']));
//
//
//            //条件模式，获取条件
//            $conditions = [];
//            if ($this->input->post('model') == 2) {
//                for ($i = 0; $i < count($this->input->post('fields')); $i++) {
//                    $conditions[] = array('fields' => $this->input->post('fields')[$i], 'link' => $this->input->post('link')[$i], 'values' => $this->input->post('values')[$i]);
//                }
//            }
//
//            $doc = array(
//                'title' => $this->input->post('title'),
//                'description' => $this->input->post('description'),
//                'model' => (int) $this->input->post('model'),
//                'relation' => $this->input->post('relation'),
//                'conditions' => $conditions,
//                'status' => 1,
//                'seo_url' => $this->input->post('seo_url'),
//                'seo_title' => $this->input->post('seo_title'),
//                'seo_description' => $this->input->post('seo_description'),
//                'creator' => $this->userAccount,
//                'create_time' => time()
//            );
//
//            var_dump($this->collection_model->update($syncCountry_have, $syncCountry, $collection_id, $doc));
//        } else {
//            exit('Collection不存在');
//        }
//    }

    function getInfoById($collection_id, $country = 'AU') {
        $this->load->model('collection_model');
        $doc = $this->collection_model->getInfoById($country, $collection_id);
        var_dump($doc);
    }

    //同传
    function syncCollection() {
        $collection_id = $this->input->post('collection_id');
        if ($this->input->post('sourceCountry') == 'Public') {
            exit(json_encode(array('success' => false, 'msg' => '请选择数据源！')));
        }
        $sourceCountry_code = $this->input->post('sourceCountry');

        //获取需要同步的国家
        $syncCountry = [];
        foreach ($this->input->post() as $key => $value) {
            if (substr_count($key, 'lang') > 0) {
                $syncCountry = array_merge_recursive($syncCountry, $this->input->post($key));
            }
        }
        if (!count($syncCountry)) {
            exit(json_encode(array('success' => false, 'msg' => '请选择需要要同步的国家！')));
        }
        $this->load->model('collection_model');
        //获取源国家Collection的信息
        $collectionInfo = $this->collection_model->getInfoById($sourceCountry_code, $collection_id);
        if ($collectionInfo) {
            //获取已同步的国家
            $this->load->model('collectioncountry_model');
            $tmp = $this->collectioncountry_model->getCountries($collection_id);
            $syncCountry_have = array_unique(array_merge($tmp['show'], $tmp['hide']));

            unset($collectionInfo['_id']);
            $collectionInfo['status'] = 1;
            if ($this->collection_model->sync($syncCountry_have, $syncCountry, $collection_id, $collectionInfo)) {
                exit(json_encode(array('success' => true, 'msg' => '数据同步成功！')));
            } else {
                exit(json_encode(array('success' => false, 'msg' => '数据同步失败！')));
            }
        } else {
            exit(json_encode(array('success' => false, 'msg' => 'Collection不存在！')));
        }
    }

    function del() {
        $collection_id = $this->input->post('collection_id');
        //获取需要删除的国家
        $delCountry = [];
        foreach ($this->input->post() as $key => $value) {
            if (substr_count($key, 'lang') > 0) {
                $delCountry = array_merge_recursive($delCountry, $this->input->post($key));
            }
        }
        $this->load->model('collection_model');
        if ($this->collection_model->del($delCountry, $collection_id)) {
            exit(json_encode(array('success' => true, 'msg' => '数据删除成功！')));
        } else {
            exit(json_encode(array('success' => false, 'error' => '数据删除成功！')));
        }
    }

    function updateStatus() {
        $collection_id = $this->input->post('collection_id');
        $status = $this->input->post('optionsRadios');
        //获取需要更改状态的国家
        $updateCountry = [];
        foreach ($this->input->post() as $key => $value) {
            if (substr_count($key, 'lang') > 0) {
                $updateCountry = array_merge_recursive($updateCountry, $this->input->post($key));
            }
        }
        if (count($updateCountry) == 0) {
            exit(json_encode(array('success' => false, 'error' => 'Change Country !')));
        }
        $this->load->model('collection_model');
        if ($this->collection_model->updateStatus($updateCountry, $status, $collection_id)) {
            exit(json_encode(array('success' => true, 'msg' => 'Collection 状态更改成功！')));
        } else {
            exit(json_encode(array('success' => FALSE, 'msg' => 'Collection 状态更改失败！')));
        }
    }

    function removeProduct() {
        $collection_id = $this->input->post('collection_id');
        $product_id = $this->input->post('product_id');
        $collection = $this->mongo->{$this->pageCountry . '_collection'};
        $whereData = array('_id' => $collection_id);
        $updatedata = array('$pull' => array('allow' => new MongoId($product_id)));
        $result = $collection->update($whereData, $updatedata);
        if ($result['ok']) {
            exit(json_encode(array('success' => true)));
        } else {
            exit(json_encode(array('success' => FALSE, 'error' => 'Collection 移除产品失败！')));
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

    //排行哦  临时的哦 先用这哦
    function rank() {
        $this->load->model('order_model');
        $this->load->model('country_model');
        $this->load->model('collection_model');
        $this->load->model('dropdown_model');

        $startTime = $this->input->post('startTime') ? $this->input->post('startTime') : date("Y-m-d");
        $endTime = $this->input->post('endTime') ? $this->input->post('endTime') : date("Y-m-d");
        $collectionName = $this->input->post('collectionName') ? $this->input->post('collectionName') : '';

        if ($collectionName) {
            $starDate = strtotime($startTime . ' 00:00:00');
            $endDate = strtotime($endTime . ' 23:59:59');

            //查出所有国家
            $countryList = $this->country_model->getCountryCodeSet();
            $collectionRank = array();
            $allQty = 0;
            $allAmount = 0;
            $error = '';
            foreach ($countryList as $country) {
                $symbol = $this->country_model->getInfoByCode($country, array('currency_payment'));
                $collectionRank[$country]['amount'] = 0;
                $collectionRank[$country]['qty'] = 0;
                $products = iterator_to_array($this->collection_model->listData($country, array('title' => $collectionName), array('allow')));
                foreach ($products as $product) {
                    foreach ($product['allow'] as $productID) {
                        $product_details = $this->order_model->getOrderAmountbyproductId($country, (string) $productID, array($starDate, $endDate), true);
                        $collectionRank[$country]['amount']+=$product_details['amount'];
                        $collectionRank[$country]['qty']+=$product_details['qty'];
                        $allQty+=$product_details['qty'];
                    }
                }
                if($collectionRank[$country]['amount']>0){
                    $audmoney = $this->conversion(strtoupper($symbol['currency_payment']), 'AUD',$collectionRank[$country]['amount']);
                    if(!is_numeric($audmoney)&&empty($error))$error = $audmoney;
                }else{
                    $audmoney = 0;
                }
                $allAmount += $audmoney;
                $collectionRank[$country]['amount'] = $symbol['currency_payment'].' '.$collectionRank[$country]['amount'];
            }
            if(!empty($error)){
                $allAmount = $error;
            }else{
                $allAmount = 'AUD '.round($allAmount,2);
            }
            //组装结果
            $this->page ['allQty'] = $allQty;
            $this->page ['allAmount'] = $allAmount;
            $this->page ['collectionRank'] = $collectionRank;
        } else {
            $this->page ['collectionRank'] = 0;
        }


        //组装条件
        $this->page ['start'] = $startTime;
        $this->page ['end'] = $endTime;
        $this->page ['collectionName'] = $collectionName;
        $this->page['collection'] = $this->dropdown_model->collection($this->pageCountry);
        $this->page['head'] = $this->load->view('head', $this->_category, true);
        $this->page['foot'] = $this->load->view('foot', $this->_category, true);
        $this->load->view('collectionRank', $this->page);
    }
    
    //汇率转换api
    private function conversion($fromCurrency, $toCurrency, $amount) {
        $ch = curl_init();
        $url = "http://apis.baidu.com/apistore/currencyservice/currency?fromCurrency=$fromCurrency&toCurrency=$toCurrency&amount=$amount";
        $header = array(
            'apikey: 74290a25158bbdc6300a1714b8661bd7',
        );
        // 添加apikey到header
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // 执行HTTP请求
        curl_setopt($ch, CURLOPT_URL, $url);
        $res = curl_exec($ch);
        $arr = json_decode($res, true);
        return isset($arr['retData']['convertedamount']) ? $arr['retData']['convertedamount'] : $arr['errMsg'];
    }

}
