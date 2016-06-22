<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class slideshow extends Pc_Controller {

    private $country;
    private $user;

    public function __construct() {
        parent::__construct();
        parent::_active('slideshow');
        $this->country = $this->session->userdata('my_country');
        $this->user = $this->session->userdata('user_account');
    }

    public function index() {
        $pagesize = 10;
        $this->page['head'] = $this->load->view('head', $this->_category, true);
        $this->load->model('collection_model');
        $pagenum = ($this->uri->segment(3) === FALSE ) ? 1 : $this->uri->segment(3);
        $where = array();
        if ($this->input->post('keyword')) {
            $where['title'] = new MongoRegex("/{$this->input->post('keyword', true)}/i");
        }
        $this->page['collection'] = $this->collection_model->listData($this->country, $where, array('_id' => 1, 'title' => 1), ($pagenum - 1) * $pagesize, $pagesize);
        $this->page['foot'] = $this->load->view('foot', $this->_category, true);
        $total_rows = $this->collection_model->count($this->country, $where);
        //分页开始
        $this->load->library('pagination');
        $config['base_url'] = base_url() . 'slideshow/index';
        $config['total_rows'] = $total_rows; //总记录数
        $config['per_page'] = $pagesize; //每页记录数
        $config['num_links'] = 9; //当前页码边上放几个链接
        $config['uri_segment'] = 3; //页码在第几个uri上
        $this->pagination->initialize($config);
        $this->page['pages'] = $this->pagination->create_links();
        //分页结束
        $this->page['where'] = $this->input->post('keyword');
        $this->load->view('SlideCollection', $this->page);
    }

    public function edit($_id) {
        $this->load->helper('url');
        $this->page['head'] = $this->load->view('head', $this->_category, true);
        $this->page['_id'] = $_id;
        $this->load->model('slideshow_model');
        $this->page['image'] = $this->slideshow_model->findOne($this->country, $_id);
        $this->page['foot'] = $this->load->view('foot', $this->_category, true);
        $this->load->model('country_model');
        $countryInfo = $this->country_model->getInfoByCode($this->country, array('domain'));
        $this->load->model('language_model');
        $this->page['language'] = $this->language_model->listData();
        foreach ($this->page['language'] as $key => $language_code) {
            $c = $this->country_model->getCountryByLangCode($key);
            $c = array_diff($c, array($this->country));
            $country[$key] = $c;
        }
        $this->page['country'] = $country;
        $this->load->view('slideshow', $this->page);
    }

    public function upPic() {
        if ($_FILES['pic']['error'] == 0) {
            $config['file_name'] = md5($_FILES['pic']['name'] . mt_rand(10, 99));
        } else {
            redirect('Showerror/index/Error');
        }
        $this->load->library('upload');
        $this->load->model('slideshow_model');
        $data = $this->input->post();
        $updatecountry = array($this->country);
        foreach ($data as $key => $value) {
            if (substr_count($key, 'lang') > 0) {
                $updatecountry = array_merge_recursive($updatecountry, $data[$key]);
            }
        }
        $updatecountry = array_unique($updatecountry);
        $config['allowed_types'] = 'gif|jpg|jpeg|png';
        $config['max_size'] = '2048';
        // 正常尺寸为945x385
        $config['max_width'] = '1045';
        $config['max_height'] = '485';
        $array['_id'] = new MongoId();
        $array['collection'] = $data['collection'];
        $arrays['link'] = $data['piclink'];
        $array['sort'] = 1;
        $error = array();
        $hasC = false;
        if (preg_match('/^(https?:\/\/)([^\/]+)/i', $array['link'], $matches)) {
            if (stripos($matches[2], '.drgrab.') !== false) {
                $this->load->model('country_model');
                $hasC = $matches[2];
            }
        }
        foreach ($updatecountry as $kc => $vc) {
            if ($vc != $this->country && $hasC) {
                $_domain = $this->country_model->getInfoByCode($vc, array('domain'));
                $array['link'] = str_replace($hasC, $_domain['domain'], $arrays['link']);
            }else{
                $array['link'] = $arrays['link'];
            }
            $url = $_SERVER['DOCUMENT_ROOT'] . '/../uploads/slide/' . $vc . '/' . $data['collection'] . '/';
            if (!file_exists($url)) {
                @mkdir($url, 0777, true);
            }
            $config['upload_path'] = $url;
            $this->upload->initialize($config);
            if ($this->upload->do_upload('pic')) {
                $updata = $this->upload->data();
                $array['image'] = '/slide/' . $vc . '/' . $data['collection'] . '/' . $updata['file_name'];
                $this->slideshow_model->insert($vc, $array);
            } else {
                $error[] = $vc . ' ' . $this->upload->display_errors();
            }
        }
        if (count($error) == $updatecountry) {
            redirect('Showerror/index/Error');
        } elseif (!empty($error)) {
            redirect('Showerror/index/' . join(',', $error));
        }
        header('location:/slideshow/edit/' . $data['collection']);
    }

    public function removepic() {
        $_id = $this->input->post('id');
        $this->load->model('slideshow_model');
        $cty = $this->input->post('cty');
        if (!empty($cty)) {
            $cty = explode(',', $cty);
            $countrys = array_merge(array($this->country), $cty);
        } else {
            $countrys = array($this->country);
        }
        $error = array();
        foreach ($countrys as $kc => $vc) {
            $rs = $this->slideshow_model->findPic($vc, $_id);
            if ($rs) {
                $res = $this->slideshow_model->remove($vc, $_id);
                if ($res) {
                    $url = $_SERVER['DOCUMENT_ROOT'] . '/../uploads' . $rs['image'];
                    if (!@unlink($url)) {
                        $error[] = $vc;
                    }
                } else {
                    $error[] = $vc;
                }
            }
        }
        if (empty($error)) {
            $return = array('status' => 200, 'info' => '');
        } elseif (!in_array($this->country, $error)) {
            $return = array('status' => 200, 'info' => join(',', $error) . ' 删除失败');
        } else {
            $return = array('status' => 0, 'info' => join(',', $error) . ' 删除失败');
        }
        exit(json_encode($return));
    }

    public function syncpic() {
        $_id = $this->input->post('id');
        $this->load->model('slideshow_model');
        $cty = $this->input->post('cty');
        if (!empty($cty)) {
            $countrys = explode(',', $cty);
        } else {
            exit(json_encode(array('status' => 200, 'info' => '')));
        }
        $error = array();
        $hasC = false;
        $rs = $array = $this->slideshow_model->findPic($this->country, $_id);
        if (preg_match('/^(https?:\/\/)([^\/]+)/i', $array['link'], $matches)) {
            if (stripos($matches[2], '.drgrab.') !== false) {
                $this->load->model('country_model');
                $hasC = $matches[2];
            }
        }
        foreach ($countrys as $kc => $vc) {
            $rss = $this->slideshow_model->findPic($vc, $_id);
            if (!$rss) {
                //复制图片
                $url = $_SERVER['DOCUMENT_ROOT'] . '/../uploads/slide/' . $vc . '/' . $rs['collection'] . '/';
                if (!file_exists($url)) {
                    @mkdir($url, 0777, true);
                }
                $turl = $url . basename($rs['image']);
                $ourl = $_SERVER['DOCUMENT_ROOT'] . '/../uploads' . $rs['image'];
                $copyres = @copy($ourl, $turl);
                if ($copyres) {
                    if ($hasC) {
                        $_domain = $this->country_model->getInfoByCode($vc, array('domain'));
                        $array['link'] = str_replace($hasC, $_domain['domain'], $rs['link']);
                    }

                    $array['image'] = '/slide/' . $vc . '/' . $rs['collection'] . '/' . basename($rs['image']);
                    $res = $this->slideshow_model->insert($vc, $array);
                    if (!$res) {
                        $delurl = $_SERVER['DOCUMENT_ROOT'] . '/../uploads' . $array['image'];
                        @unlink($delurl);
                        $error[] = $vc;
                    }
                } else {
                    $error[] = $vc;
                }
            }
        }
        if (!empty($error)) {
            $return = array('status' => 0, 'info' => join(',', $error) . ' 同步失败');
        } else {
            $return = array('status' => 200, 'info' => '同步成功');
        }
        exit(json_encode($return));
    }

    public function changesort($_id = 0) {
        $id = $this->input->post('id');
        $sort = $this->input->post('sort');
        if ($id && $sort) {
            $cty = $this->input->post('cty');
            if (!empty($cty)) {
                $cty = explode(',', $cty);
                $countrys = array_merge(array($this->country), $cty);
            } else {
                $countrys = array($this->country);
            }
            $error = array();
            $this->load->model('slideshow_model');
            foreach ($countrys as $kc => $vc) {
                $result = $this->slideshow_model->updateSort($vc, $id, $sort);
                if (!$result)
                    $error[] = $vc;
            }
            if (empty($error)) {
                exit(json_encode(array('success' => true)));
            } else {
                exit(json_encode(array('success' => False, 'info' => join(',', $error) . ' 修改此图片顺序失败')));
            }
        } else {
            exit(json_encode(array('success' => False, 'info' => 'id,sort参数错误')));
        }
    }

    public function changelink($_id = 0) {
        $id = $this->input->post('id');
        $link = $this->input->post('link');
        if ($id) {
            $cty = $this->input->post('cty');
            if (!empty($cty)) {
                $cty = explode(',', $cty);
                $countrys = array_merge(array($this->country), $cty);
            } else {
                $countrys = array($this->country);
            }
            $error = array();
            $hasC = false;
            if (preg_match('/^(https?:\/\/)([^\/]+)/i', $link, $matches)) {
                if (stripos($matches[2], '.drgrab.') !== false) {
                    $this->load->model('country_model');
                    $hasC = $matches[2];
                }
            }
            
            $this->load->model('slideshow_model');
            foreach ($countrys as $kc => $vc) {
                if ($this->country != $vc && $hasC) {
                    $_domain = $this->country_model->getInfoByCode($vc, array('domain'));
                    $links = str_replace($hasC, $_domain['domain'], $link);
                }else{
                    $links = $link;
                }
                $result = $this->slideshow_model->updateLink($vc, $id, $links);
                if (!$result)
                    $error[] = $vc;
            }
            if (empty($error)) {
                exit(json_encode(array('success' => true)));
            } else {
                exit(json_encode(array('success' => False, 'info' => join(',', $error) . ' 修改此图片链接失败')));
            }
        } else {
            exit(json_encode(array('success' => False, 'info' => 'id参数错误')));
        }
    }

}
