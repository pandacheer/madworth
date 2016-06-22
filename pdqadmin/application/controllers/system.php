<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class System extends Pc_Controller {

    function __construct() {
        parent::__construct();
        // parent::_active('system');
    }

    public function index() {
        $data['template'] = $this->page['template'];
//        $this->load->model('rbac_model');
//        $data['menus'] = $this->rbac_model->menuIntoRedis(10000);
//        $data['user_menus'] = $this->rbac_model->user_menu($this->session->userdata('user_id'));
        $this->load->view('system/v_main', $data);
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */