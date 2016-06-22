<?php

/**
 *  @说明  销售数据列表控制器
 *  @作者  zhujian
 *  @qq  407284071
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class sites extends Pc_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('order_model');
        $this->load->model('country_model');
    }

    public function index() {
        $countryList = array('AU', 'NZ', 'US', 'CA', 'GB', 'IE', 'SG');
        $salesData = array();
        foreach ($countryList as $key => $country) {
            $salesData[$country]['yesterdaySalesData'] = $this->order_model->yesterdaySalesData($country);
            $salesData[$country]['currentSalesData'] = $this->order_model->currentSalesData($country);
            $salesData[$country]['lastMonthSalesData'] = $this->order_model->lastMonthSalesData($country);
            $salesData[$country]['currentMonthSalesData'] = $this->order_model->currentMonthSalesData($country);
        }
        $this->page ['salesData'] = $salesData;
        $this->load->view('Selllist', $this->page);
    }

    public function usd() {
        $countryList = array('AU', 'NZ', 'US', 'CA', 'GB', 'IE', 'SG');
        $salesData = array();
        foreach ($countryList as $key => $country) {
            $salesData[$country]['yesterdaySalesData'] = $this->order_model->yesterdaySalesData($country);
            $salesData[$country]['currentSalesData'] = $this->order_model->currentSalesData($country);
            $salesData[$country]['lastMonthSalesData'] = $this->order_model->lastMonthSalesData($country);
            $salesData[$country]['currentMonthSalesData'] = $this->order_model->currentMonthSalesData($country);
        }
        
        foreach ($salesData as $key => $sales) {
            $currencyPayment = $this->country_model->getInfoByCode($key, array('currency_payment'));
            if($currencyPayment['currency_payment']!='USD'){
                if ($sales['yesterdaySalesData']) {
                    $salesData[$key]['yesterdaySalesData'] = $this->conversion($currencyPayment['currency_payment'], 'USD', $sales['yesterdaySalesData']);
                }
                if ($sales['currentSalesData']) {
                    $salesData[$key]['currentSalesData'] = $this->conversion($currencyPayment['currency_payment'], 'USD', $sales['currentSalesData']);
                }
                if ($sales['lastMonthSalesData']) {
                    $salesData[$key]['lastMonthSalesData'] = $this->conversion($currencyPayment['currency_payment'], 'USD', $sales['lastMonthSalesData']);
                }
                if ($sales['currentMonthSalesData']) {
                    $salesData[$key]['currentMonthSalesData'] = $this->conversion($currencyPayment['currency_payment'], 'USD', $sales['currentMonthSalesData']);
                }
            }
        }
        $totalSalesData = array('yesterdaySalesData'=>0,'currentSalesData'=>0,'lastMonthSalesData'=>0,'currentMonthSalesData'=>0);
        foreach ($salesData as $sale) {
            @$totalSalesData['yesterdaySalesData']+= $sale['yesterdaySalesData'];
            @$totalSalesData['currentSalesData']+=$sale['currentSalesData'];
            @$totalSalesData['lastMonthSalesData']+=$sale['lastMonthSalesData'];
            @$totalSalesData['currentMonthSalesData']+=$sale['currentMonthSalesData'];
        }
        $this->page ['salesData'] = $salesData;
        $this->page ['totalSalesData'] = $totalSalesData;
        $this->load->view('Selllist', $this->page);
    }

    public function aud() {
        $countryList = array('AU', 'NZ', 'US', 'CA', 'GB', 'IE', 'SG');
        $salesData = array();
        foreach ($countryList as $key => $country) {
            $salesData[$country]['yesterdaySalesData'] = $this->order_model->yesterdaySalesData($country);
            $salesData[$country]['currentSalesData'] = $this->order_model->currentSalesData($country);
            $salesData[$country]['lastMonthSalesData'] = $this->order_model->lastMonthSalesData($country);
            $salesData[$country]['currentMonthSalesData'] = $this->order_model->currentMonthSalesData($country);
        }
        foreach ($salesData as $key => $sales) {
            $currencyPayment = $this->country_model->getInfoByCode($key, array('currency_payment'));
            if($currencyPayment['currency_payment']!='AUD'){
                if ($sales['yesterdaySalesData']) {
                    $salesData[$key]['yesterdaySalesData'] = $this->conversion($currencyPayment['currency_payment'], 'AUD', $sales['yesterdaySalesData']);
                }
                if ($sales['currentSalesData']) {
                    $salesData[$key]['currentSalesData'] = $this->conversion($currencyPayment['currency_payment'], 'AUD', $sales['currentSalesData']);
                }
                if ($sales['lastMonthSalesData']) {
                    $salesData[$key]['lastMonthSalesData'] = $this->conversion($currencyPayment['currency_payment'], 'AUD', $sales['lastMonthSalesData']);
                }
                if ($sales['currentMonthSalesData']) {
                    $salesData[$key]['currentMonthSalesData'] = $this->conversion($currencyPayment['currency_payment'], 'AUD', $sales['currentMonthSalesData']);
                }
            }
        }
        $totalSalesData = array('yesterdaySalesData'=>0,'currentSalesData'=>0,'lastMonthSalesData'=>0,'currentMonthSalesData'=>0);
        foreach ($salesData as $sale) {
            @$totalSalesData['yesterdaySalesData']+= $sale['yesterdaySalesData'];
            @$totalSalesData['currentSalesData']+=$sale['currentSalesData'];
            @$totalSalesData['lastMonthSalesData']+=$sale['lastMonthSalesData'];
            @$totalSalesData['currentMonthSalesData']+=$sale['currentMonthSalesData'];
        }
        $this->page ['salesData'] = $salesData;
        $this->page ['totalSalesData'] = $totalSalesData;
        $this->load->view('Selllist', $this->page);
    }

    //汇率转换api
    public function conversion($fromCurrency, $toCurrency, $amount) {
        $ch = curl_init();
        $url = "http://apis.baidu.com/apistore/currencyservice/currency?fromCurrency=$fromCurrency&toCurrency=$toCurrency&amount=$amount";
        $header = array(
            'apikey: 74290a25158bbdc6300a1714b8661bd7',
        );
        // 添加apikey到header
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // 执行HTTP请求
        curl_setopt($ch, CURLOPT_URL, $url);
        $res = curl_exec($ch);
        $arr = json_decode($res, true);
        return isset($arr['retData']['convertedamount']) ? $arr['retData']['convertedamount'] : $arr['errMsg'];
    }

}

?>