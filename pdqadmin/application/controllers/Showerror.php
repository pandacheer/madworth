<?php

/**
 * @文件： showError
 * @时间： 2015-12-12
 * @编码： utf8
 * @作者： zway
 * @QQ：   524611646
 * 说明：
 */
class Showerror extends Pc_Controller {

    function index($errmsg = '') {
        $this->page['head'] = $this->load->view('head', $this->_category, true);
        $this->page['foot'] = $this->load->view('foot', $this->_category, true);
        $isbase64 = base64_encode(base64_decode($errmsg))==$errmsg ? true : false;
        if($isbase64){
            $errmsg = base64_decode($errmsg);
        }
        $errmsg = urldecode($errmsg);
        $this->page['errorMessage'] = $errmsg;
        $this->load->view('showError', $this->page);
    }

}
