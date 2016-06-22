<?php

/**
 * @文件： soap
 * @时间： 2015-6-30 13:59:27
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：
 */
class RpcClient extends Pc_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        $this->load->helper('url');
        $server_url = site_url('rpcServer');

        $this->load->library('xmlrpc');

        $this->xmlrpc->server($server_url, 80);
        $this->xmlrpc->method('Greetings');

        $request = array('How is it going?');
        $this->xmlrpc->request($request);

        if (!$this->xmlrpc->send_request()) {
            echo $this->xmlrpc->display_error();
        } else {
            echo '<pre>';
            print_r($this->xmlrpc->display_response());
            echo '</pre>';
        }
    }

}
