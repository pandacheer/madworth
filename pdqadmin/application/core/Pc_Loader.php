<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Pc_Loader extends CI_Loader {
	public function __construct() {
		parent::__construct();
		$this->_ci_view_paths = array(FCPATH.'template/' => TRUE);
	}
}