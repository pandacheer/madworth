<?php

class refund extends MY_Controller {

    private $terminal;

    function __construct() {
        parent::__construct();

        /* 登录检测 */
        $key = $this->config->item('encryption_key');
        $mail = $this->session->userdata('member_email');
        $auth = $this->session->userdata('auth');

        if ($auth != md5($key . $mail)) {
            $this->load->helper('url');
            redirect('/login');
        }

        $this->member_id = $this->session->userdata('member_id');
        $this->country = $this->page ['country'];
        $this->terminal = $this->session->userdata('isMobile');
        $this->load->model('template_model');
        $headView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'head');
        $this->page['head'] = $this->load->view($headView, $this->page, true);
        $footLogosView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'foot_logos');
        $this->page['footLogosView'] = $this->load->view($footLogosView, $this->page, true);
        $footView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'foot');
        $this->page['foot'] = $this->load->view($footView, $this->page, true);
        $shoppingcartView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'shoppingcart');
        $this->page['shoppingcart'] = $this->load->view($shoppingcartView, $this->page, true);
    }

    function index($details_id) {
        $this->load->helper('form');
        $this->load->model('Orderdetails_model');
        $order_details = $this->Orderdetails_model->getInfoByID($this->country, $details_id);

        if (empty($order_details)) {
            redirect('/personal/order');
        }


        if ($this->member_id == $order_details['member_id']) {
            $this->load->model('Product_model');
            $this->load->model('collection_model');

            $pro = $this->Product_model->orderPics($this->country, $order_details['product_id']);
            $order_details['collection_url'] = $this->collection_model->getCollectionUrl($this->country, $order_details['product_id']);
            $order_details['seo_url'] = $pro['seo_url'];
            $order_details['image'] = $pro['image'];


            $this->load->model('Orderrefund_model');
            $this->page['refundApply'] = $this->Orderrefund_model->getRefund($this->country, $details_id);

            $this->page['order_details'] = $order_details;
            $accountOrderRefundView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'account-order-refund');
            $this->load->view($accountOrderRefundView, $this->page);
        } else {
            redirect('/personal/order');
        }
    }

    function apply() {
        $this->load->helper('language');
        $this->lang->load('sys_refund');


        $details_id = $this->input->post('detailsId', TRUE);
        if (!is_numeric($details_id)) {
            exit(json_encode(array('success' => false, 'resultMessage' => lang('id_error'))));
        }

        $this->load->model('Orderdetails_model');
        $this->load->model('Orderrefund_model');

        $order_details = $this->Orderdetails_model->getInfoByID($this->country, $details_id);
        if (!count($order_details)) {
            exit(json_encode(array('success' => false, 'resultMessage' => lang('order_details_error'))));
        }


        if ($this->member_id == $order_details['member_id']) {
            $isRefund = $this->Orderrefund_model->isRefund($this->country, $details_id);
            if ($isRefund) {
                exit(json_encode(array('success' => false, 'resultMessage' => lang('is_refund'))));
            }

            $reason = $this->input->post('reason', TRUE);
            if (empty($reason)) {
                exit(json_encode(array('success' => false, 'resultMessage' => lang('reason_error'))));
            }

            $details = $this->input->post('detailsText', TRUE);
            if (empty($details)) {
                exit(json_encode(array('success' => false, 'resultMessage' => lang('details_error'))));
            }

            //查找产品创建人
            $this->load->model('Product_model');
            $creator = $this->Product_model->productCreator($this->country, $order_details['product_id']);


            $data = array(
                '_id' => $details_id,
                'order_number' => $order_details['order_number'],
                'refund_reason' => $reason,
                'refund_details' => $details,
                'refund_proName' => $order_details['product_name'],
                'refund_proSku' => $order_details['product_sku'],
                'refund_proId' => $order_details['product_id'],
                'equal_order' => 1,
                'status' => 1,
                'creator' => $creator['creator'],
                'create_time' => time()
            );

            //上传图片start
            $times = time();
            $url = $_SERVER['DOCUMENT_ROOT'] . '/../uploads/refundApply/' . $times;
            if (!file_exists($url)) {
                mkdir($url, 0777);
            }

            $config['upload_path'] = $url;
            $config['overwrite'] = TRUE;
            $config['allowed_types'] = 'gif|jpg|jpeg|png';
            $config['max_size'] = 3072;

            $this->load->library('upload', $config);
            $data['pics'] = array();
            $error = array();
            if (!empty($_FILES['file'])) {
                $a = $_FILES['file'];
                foreach ($a['error'] as $k1 => $v1) {
                    if ($v1 == 0) {
                        $_FILES['file'] = array('error' => $v1, 'name' => $a['name'][$k1], 'size' => $a['size'][$k1], 'tmp_name' => $a['tmp_name'][$k1], 'type' => $a['type'][$k1]);
                        $config['file_name'] = $k1 + 1;
                        $this->upload->initialize($config);
                        if ($this->upload->do_upload('file')) {
                            $updata = $this->upload->data();
                            $data['pics'][$k1]['img'] = '/refundApply/' . $times . '/' . $updata['file_name'];
                            //压压压压压压压缩图片
                            $this->load->library('image_lib');
                            $config['image_library'] = 'gd2';
                            $config['quality'] = '85%';
                            $config['source_image'] = '../uploads' . $data['pics'][$k1]['img'];
                            $config['thumb_marker'] = '';
                            $config['create_thumb'] = TRUE;
                            $config['maintain_ratio'] = TRUE;
                            $config['width'] = 1024;
                            $config['height'] = 1024;
                            $config['master_dim'] = 'auto';
                            $this->image_lib->initialize($config);
                            if (!$this->image_lib->resize()) {
                                exit(json_encode(array('success' => false, 'resultMessage' => $this->image_lib->display_errors())));
                            }
                        } else {
                            exit(json_encode(array('success' => false, 'resultMessage' => $this->upload->display_errors())));
                        }
                    }
                }
            }
            //上传图片end


            $result = $this->Orderrefund_model->addRefund($this->country, $data);
            if ($result) {
                $is_moreOrder = $this->Orderrefund_model->getMoreRefund($this->country, $order_details['order_number']);
                if ($is_moreOrder > 1) {
                    $this->Orderrefund_model->up_equalOrder($this->country, $order_details['order_number']);
                }
                exit(json_encode(array('success' => true,)));
            } else {
                exit(json_encode(array('success' => false, 'resultMessage' => lang('refundApply_error'))));
            }
        } else {
            exit(json_encode(array('success' => false, 'resultMessage' => lang('member_error'))));
        }
    }

    function orderLost($order_number) {
        //判断订单号是否存在   是否符合退货时间
        $this->load->model('order_model');
        $orderInfo = $this->order_model->getInfoByNumber($this->country, $order_number, 'create_time,member_id,estimated_time');

        if (count($orderInfo)) {
            if (time() < strtotime('+' . 35 . 'day', $orderInfo['create_time'])) {
                redirect('/personal/order');
            }


            if ($this->member_id == $orderInfo['member_id']) {
                $this->load->model('Orderrefund_model');
                $this->page['refundApply'] = $this->Orderrefund_model->getRefund($this->country, $order_number);
                $this->page['order_number'] = $order_number;
            } else {
                redirect('/personal/order');
            }
        } else {
            redirect('/personal/order');
        }
        $accountOrderLostView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'account-order-lost');
        $this->load->view($accountOrderLostView, $this->page);
    }

    function orderLostApply() {
        $this->load->model('order_model');
        $this->load->model('Orderrefund_model');

        $details = $this->input->post('details', TRUE);

        $orderNumber = $this->input->post('orderNumber', TRUE);
        if (empty($orderNumber)) {
            exit(json_encode(array('success' => false, 'resultMessage' => lang('id_error'))));
        }


        $isRefund = $this->Orderrefund_model->isRefund($this->country, $orderNumber);
        if ($isRefund) {
            exit(json_encode(array('success' => false, 'resultMessage' => lang('is_refund'))));
        }

        $orderInfo = $this->order_model->getInfoByNumber($this->country, $orderNumber, 'member_id');
        if ($this->member_id == $orderInfo['member_id']) {
            $data = array(
                '_id' => $orderNumber,
                'order_number' => $orderNumber,
                'refund_reason' => 0,
                'refund_details' => $details,
                'refund_proName' => 0,
                'refund_proSku' => 0,
                'refund_proId' => 0,
                'equal_order' => 1,
                'status' => 1,
                'creator' => 0,
                'create_time' => time()
            );

            $result = $this->Orderrefund_model->addRefund($this->country, $data);
            if ($result) {
                exit(json_encode(array('success' => true,)));
            } else {
                exit(json_encode(array('success' => false, 'resultMessage' => lang('refundApply_error'))));
            }
        } else {
            exit(json_encode(array('success' => false, 'resultMessage' => lang('member_error'))));
        }
    }

}
