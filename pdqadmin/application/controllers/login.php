<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Login extends CI_Controller {
    protected $page = array(
        'template' => '/template/'
    );
    
    public function index() {
        if (!$this->session->userdata('user_in')) {
            $this->page['redirectURL'] = $this->input->get('url');
            $this->load->view('login',$this->page);
        } else {
            redirect('home');
        }
    }
    
    public function enter() {
        $user_account = strtolower($this->input->post('username'));
        $user_password = md5($this->input->post('password'));
        $this->load->model('rbacuser_model');
        $user_info = $this->rbacuser_model->check_user($user_account, $user_password);
        if (is_array($user_info)) {
            switch ($user_info['user_status']) {
                case 1:
                    $result['success'] = FALSE;
                    $result['msg'] = '帐号尚未审核，请稍候再登……';
                    break;
                case 2:
                    $user = array(
                        'user_account' => $user_account,
                        'user_id' => $user_info['user_id'],
                        'user_in' => TRUE
                    );
                    $this->rbacuser_model->update_lastdate($user_info['user_id']);
                    $this->session->set_userdata($user);
                    $result['success'] = true;
                    break;
                case 3:
                    $result['success'] = FALSE;
                    $result['msg'] = '帐号已锁定，请稍候再登……';
                    break;
                default:
                    $result['success'] = FALSE;
                    $result['msg'] = '帐号状态不明，请联系系统管理员……';
                    break;
            }
        } else {
            $result['success'] = FALSE;
            $result['msg'] = $user_info . '……';
        }
        echo json_encode($result);
    }
    
    public function logout() {
        $this->session->unset_userdata('user_in');
        redirect('login');
    }
}
