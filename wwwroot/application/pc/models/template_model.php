<?php

/**
 * @文件： template_model
 * @时间： 2015-12-8 14:51:02
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：
 */
class Template_model extends CI_Model {

    var $template_Key = 'SYS_Template_';

    function __construct() {
        parent::__construct();
    }

    //初始化模板
    function init($terminal_code, $countryCode) {
        $template = $this->template_Key . $countryCode . '_' . $terminal_code;
        $terminal_id = $terminal_code == 'pc' ? 1 : 2;
        if (!$this->redis->exists($template)) {
            $this->db->select('key,private');
            $this->db->where(array('terminal' => $terminal_id, 'country_code' => $countryCode));
            $rows = $this->db->get('SYS_Template')->result_array();
            foreach ($rows as $value) {
                $this->redis->hashSet($template, array($value['key'] => $value['private']));
            }
        }
    }

    function getStyle($terminal_code, $countryCode, $viewName) {
        $template = $this->template_Key . $countryCode . '_' . $terminal_code;
        if (!$this->redis->hashExists($template, $viewName)) {
            return $viewName;
        } else {
            $templateName = $this->redis->hashGet($template, $viewName, 1);
            if ($templateName) {
                return $countryCode . '/' . $templateName;
            } else {
                return $viewName;
            }
        }
    }

}
