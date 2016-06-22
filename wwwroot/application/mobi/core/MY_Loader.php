<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MY_Loader extends CI_Loader {

    public function __construct() {
        parent::__construct();
    }

    //切换视图路径
    public function switch_theme($theme = 'en') {
        $this->_ci_view_paths = array(FCPATH . 'template_' . $_SESSION['isMobile'] . '/' . $theme . '/' => TRUE);
    }

}