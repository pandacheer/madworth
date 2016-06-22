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
class RpcServer extends Pc_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        $this->load->library('xmlrpc');
        $this->load->library('xmlrpcs');

        $config['functions']['Greetings'] = array('function' => 'RpcServer.process');
        $this->xmlrpcs->initialize($config);
        $this->xmlrpcs->serve();
    }

    function process($request) {
        $parameters = $request->output_parameters();
        $response = array(
            array(
                'you_said' => $parameters['0'],
                'i_respond' => 'Not bad at all.'),
            'struct');
        return $this->xmlrpc->send_response($response);
    }

}
