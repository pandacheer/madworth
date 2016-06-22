<?php

/**
 *  @说明  订单投诉控制器
 *  @作者  zhujian
 *  @qq    407284071
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class orderTracking extends Pc_Controller {

    public function __construct() {
        parent::__construct();
        parent::_active('complaints');
        $this->country = $this->session->userdata('my_country');
        $this->load->model('ordertracking_model');
    }

    //显示列表
    public function index() {
        $this->load->helper('form');
        $per_page = 10; //每页记录数
        if ($this->input->post()) {
            $pagenum = 1;
        } else {
            $pagenum = ($this->uri->segment(3) === FALSE ) ? 1 : $this->uri->segment(3);
            $keyword = urldecode($this->uri->segment(4));
        }
        $total_rows = $this->ordertracking_model->complaintsrCount($this->country);
        //获取信息
        $this->page['complaintsList'] = $this->ordertracking_model->getComplaints($this->country, ($pagenum - 1) * $per_page, $per_page);

        //分页开始
        $this->load->library('pagination');
        $config['base_url'] = base_url() . 'orderTracking/index/' . $keyword;
        $config['total_rows'] = $total_rows; //总记录数
        $config['per_page'] = $per_page; //每页记录数
        $config['num_links'] = 2; //当前页码边上放几个链接
        $config['uri_segment'] = 3; //页码在第几个uri上
        $this->pagination->initialize($config);
        $this->page['pages'] = $this->pagination->create_links();
        //分页结束
        $this->page['head'] = $this->load->view('head', $this->_category, true);
        $this->page['foot'] = $this->load->view('foot', $this->_category, true);
        $this->load->view('orderTracking', $this->page);
    }

    //获取Excel
    public function getExcel() {
        $timeStart = strtotime($this->input->post('timeStart', TRUE));
        $timeEnd = strtotime($this->input->post('timeEnd', TRUE));
        if ($timeStart === false || $timeStart == -1) {
            redirect('Showerror/index/'.base64_encode('开始时间格式不正确'));
        }
        if ($timeEnd === false || $timeEnd == -1) {
            redirect('Showerror/index/结束时间格式不正确');
        }
        if($timeEnd<$timeStart){
             redirect('Showerror/index/结束时间需不早于开始时间');
        }
        $question_type = ['0' => '错发', '1' => '漏发', '2' => '丢包', '3' => '尺码问题', '4' => '质量问题', '5' => '物流超时', '6' => '取消订单  ', '7' => '退回中国', '8' => '尚未发货', '9' => '其他'];
        $department = ['0' => '销售部', '1' => '运营部', '2' => '客服部', '3' => 'ERP', '4' => '邮路'];
        $dispose = ['0' => '发货', '1' => '重寄', '2' => '退款', '3' => '退运费', '4' => '退关税', '5' => 'Coupon'];
        $coupon = ['0' => '', '1' => 'Coupon10%', '2' => 'Coupon15%', '3' => 'Coupon20%', '4' => 'Coupon30%'];
        $data = $this->ordertracking_model->getExcel($this->country, $timeStart, $timeEnd);
        if (empty($data)) {
            if($timeEnd<$timeStart){
                 redirect('Showerror/index/你查询时间的数据为空');
            }
        }
        foreach ($data as $key => $value) {
            if ($value['refund_amount'] == 0) {
                $data[$key]['refund_amount'] = '';
            }
            $data[$key]['create_time'] = date('Y-m-d H:i:s', $value['create_time']);
            $data[$key]['order_number'] = $this->country . '_' . $value['order_number'];
            $data[$key]['send_time'] = date('Y-m-d H:i:s', $value['send_time']);
            $data[$key]['question_type'] = date('Y-m-d H:i:s', $value['send_time']);
            $data[$key]['department'] = $department[$value['department']];
            $data[$key]['dispose'] = $dispose[$value['dispose']];
            $data[$key]['coupon'] = $coupon[$value['coupon']];
        }
        $title = array('创建日期', '顾客姓名', '订单编号', '发货单号', '发货时间', '产品名称（产品名称×SKU×属性×数量)', '问题分类', '问题详情', '问题物流方式', '跟踪号/单号', '责任部门', '处理方式', '退款金额', '退款备注', 'coupon', '操作人');
        $filename = $this->country.date('YmdHis');
        $this->exportexcel($data, $title, $filename);
    }

    /**
     * 导出数据为excel表格
     * @param $data    一个二维数组,结构如同从数据库查出来的数组
     * @param $title   excel的第一行标题,一个数组,如果为空则没有标题
     * @param $filename 下载的文件名
     * @examlpe 
     * $stu = M ('User');
     * $arr = $stu -> select();
     * exportexcel($arr,array('id','账户','密码','昵称'),'文件名!');
     */
    public function exportexcel($data = array(), $title = array(), $filename = 'report') {
        header("Content-type:application/octet-stream");
        header("Accept-Ranges:bytes");
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename=" . $filename . ".xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        //导出xls 开始
        if (!empty($title)) {
            foreach ($title as $k => $v) {
                $title[$k] = iconv("UTF-8", "GB2312", $v);
            }
            $title = implode("\t", $title);
            echo "$title\n";
        }
        if (!empty($data)) {
            foreach ($data as $key => $val) {
                foreach ($val as $ck => $cv) {
                    $data[$key][$ck] = iconv("UTF-8", "GB2312", $cv);
                }
                $data[$key] = implode("\t", $data[$key]);
            }
            echo implode("\n", $data);
        }
    }
}

?>