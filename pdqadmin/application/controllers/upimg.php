<?php if (! defined('BASEPATH')) exit('No direct script access allowed');
class upimg extends Pc_Controller {
    private $country;
    private $max_width = 1200;
    private $max_height = 1200;
    private $filetype = array('.jpg','.jpeg');

    public function __construct() {
        parent::__construct();
        $this->country = $this->session->userdata('my_country');
    }
    
    public function index($_id = 0){
        $this->load->model('product_model');
        $pro = $this->product_model->findOne($this->country, $_id);
        $n = $this->uri->total_rsegments();
        $this->load->model('country_model');
        $_countryList = $this->country_model->getCountryList('name');
        $countryList = array_keys($_countryList);
        $cty = $this->uri->rsegment($n);
        $_cty = explode(',',$cty);
        $diff = array_diff($_cty, $countryList);
        if(!empty($diff)){
            $pro['cty'] = '';
        }else{
            $pro['cty'] = $cty;
        }
        $this->load->view('upimg',$pro);
    }
    
    public function action(){
        // 模型与库
        $this->load->library('upload');
        $this->load->model('product_model');
        // 路径检查
        $data = $this->input->post();
        $pro = $this->product_model->findOne($this->country,$data['_id']);
        $url = $_SERVER['DOCUMENT_ROOT'].'/../uploads/product/'.$pro['sku'].'/';
        // 判断文件夹是否存在，如不存在则建立
        if(!file_exists($url)){
            mkdir($url,0777,true);
        }
        $time = time();
        $error = $succ = array();
        $count = $i = count($pro['pics']);
        // 上传已经全部成功
        foreach($_FILES['pic']['tmp_name'] as $key => $vo){
            if($_FILES['pic']['error'][$key]!=0){
                $error[] = $_FILES['pic']['name'][$key].' upload failed,error:'.$_FILES['pic']['error'][$key];
                continue;
            }
            $tmpfile = $_FILES['pic']['tmp_name'][$key];
            $upfile = $url.md5($_FILES['pic']['name'][$key].$time);
            $x = explode('.', $_FILES['pic']['name'][$key]);
            $ext = '.'.  strtolower(end($x));
            if(!in_array($ext,  $this->filetype)){
                $error[] = $_FILES['pic']['name'][$key].' require jpg or jpeg format';
                continue;
            }
            if (function_exists('getimagesize')){
                    $D = @getimagesize($_FILES['pic']['tmp_name'][$key]);
                    if ($this->max_width > 0 AND $D['0'] > $this->max_width) {
                        $error[] = $_FILES['pic']['name'][$key].' exclude '.$this->max_width.'*'.$this->max_height;
                        continue;
                    }

                    if ($this->max_height > 0 AND $D['1'] > $this->max_height){
                        $error[] = $_FILES['pic']['name'][$key].' exclude '.$this->max_width.'*'.$this->max_height;
                        continue;
                    }
            }else{
                echo '<script type="text/javascript">alert("pleasse open getimagesize extension in php.ini");location.href="/upimg/index/'.$pro['_id'].'"</script>';
                exit;
            }
            $upfile .= $ext;
            $return = move_uploaded_file($tmpfile,$upfile);
            // 判断文件移动是否成功！
            if(!$return){
                $error[] = $_FILES['pic']['name'][$key].' upload failed';
                continue;
            }
            $succ[] = $url.md5($_FILES['pic']['name'][$key].$time).$ext;
            $pro['pics'][$count]['img'] = '/product/'.$pro['sku'].'/'.md5($_FILES['pic']['name'][$key].$time).$ext;
            $pro['pics'][$count]['sort'] = $count+1;
            $count++;
        }
        if($count > $i){
            $tmpcountry = '';
            if(!empty($data['country'])){
                $tmpcountry = $data['country'];
                $data['country'] = explode(',',$data['country']);
                $countrys = array_merge(array($this->country),$data['country']);
            }else{
                $countrys = array($this->country);
            }
            $countrys = array_unique($countrys);
            $dbsuc = false;
            foreach ($countrys as $kc=>$vc){
                $pros = $this->product_model->findPro($vc,array('sku'=>$pro['sku']));
                if(!empty($pros)){
                    $pro['_id'] = $pros['_id'];
                }
                $rs = $this->product_model->updateAppendPro($vc,array('_id'=>$pro['_id']),array('$set'=>array('pics'=>$pro['pics'])));
                if($rs){
                    if(!$dbsuc)$dbsuc = true;
                    $pics = $this->product_model->findPics($vc,(string)$pro['_id']);
                    $t = $tt = array();
                    foreach ($pics as $key => $vo) {
                        if(!empty($vo['pics'])){
                            foreach($vo['pics'] as $vo1){
                                $t[] = $vo1['sort'];
                                $tt[$vo1['sort']] = $vo1['img'];
                            }
                        }
                    }
                    $rs = $this->product_model->whimage($vc,(string)$pro['_id'],$tt[min($t)]);
                }
            }
            if(!$dbsuc&&!empty($succ)){
                foreach($succ as $vv){
                    @unlink($vv);
                }
                echo '<script type="text/javascript">alert("update failed!");</script>';
                exit;
            }
            if(!empty($error)){
                echo '<script type="text/javascript">alert("'.join(',',$error).' upload failed");parent.location.href="/upimg/index/'.$data['_id'].'/'.$tmpcountry.'"</script>';
            }else{
                echo '<script type="text/javascript">parent.location.href="/upimg/index/'.$data['_id'].'/'.$tmpcountry.'"</script>';
            }
        }else{
            echo '<script type="text/javascript">alert("'.join(",",$error).'");</script>';
        }
    }
    
    public function changesort($_id = 0) {
        $data = $this->input->post();
        if (empty($data)) {
            echo '<script type="text/javascript">alert("no image sort");</script>';
            exit;
        }
        $this->load->model('product_model');
        $tmpcounty = '';
        if (!empty($data['country'])) {
            $tmpcounty = $data['country'];
            $data['country'] = explode(',',$data['country']);
            $countrys = array_merge(array($this->country), $data['country']);
        } else {
            $countrys = array($this->country);
        }
        $countrys = array_unique($countrys);
        $pro = $this->product_model->findOne($this->country,$_id);
        $error = array();
        foreach ($countrys as $kc => $vc) {
            $pros = $this->product_model->findPro($vc,array('sku'=>$pro['sku']));
            if(!empty($pros)){
                $pro['_id'] = $pros['_id'];
            }
            $rs = $this->product_model->changesort($vc, $pro['_id'], $data);
            if(!$rs){
                $error[] = $vc;
            }
        }
        if (empty($error)) {
            echo '<script type="text/javascript">location.href="/upimg/index/' . $_id . '/'.$tmpcounty.'"</script>';
        } else {
            echo '<script type="text/javascript">alert("'.join(',',$error).' update failed");location.href="/upimg/index/' . $_id . '/'.$tmpcounty.'"</script>';
        }
    }

    public function removepic($_id=0,$key=0,$country=''){
        $this->load->model('product_model');
        $tmpcountry = $country;
        if(empty($country))$country = $this->country;
        else $country = $this->country.','.$country;
        $countrys = array_unique(explode(',',$country));
        $pro = $this->product_model->findOne($this->country,$_id);
        foreach($countrys as $kc=>$vc){
            $pros = $this->product_model->findPro($vc,array('sku'=>$pro['sku']));
            if(!empty($pros)){
                $pro['_id'] = $pros['_id'];
            }
            $result = $this->product_model->removepic($vc,(string)$pro['_id'],$key);
            $link = $_SERVER['DOCUMENT_ROOT'].'/../uploads'.$result;
            unlink($link);
        }
        header('location:/upimg/index/'.$_id.'/'.$tmpcountry);
    }
}