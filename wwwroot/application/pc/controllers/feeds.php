<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class feeds extends MY_Controller {

    private $cate = array();

    public function index($country = 'US') {
        $country = strtoupper($country);
        $this->load->model('country_model');
        $this->load->model('product_model');
        $this->cate = $this->getcate($country);
        $imagehost = STATIC_HTTP_DOMAIN;
        $countryinfo = $this->country_model->getInfoByCode($country, $fields = array(
            'country_code',
            'currency_payment',
            'domain'
        ));
        $countryinfo ['domain'] = "http://" . $countryinfo['domain'];
        $head = '<?xml version="1.0"?>
                <rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">
                  <channel>
                    <title>DrGrab Product Feed</title>
                    <link>' . $countryinfo['domain'] . '</link>
                    <description>DrGrab Product Feed</description>
                ';
        $items = '';
        $foot = '
                  </channel>
                </rss>';
        $data = $this->product_model->selectAll($country);
        $this->load->model('countdown_model');
        $time = time();
        foreach ($data as $vo) {
            if (array_key_exists($vo['sku'], $this->cate)) {
                $category = $this->cate[$vo['sku']];
                $category = strpos($category, '>') !== false ? htmlspecialchars($category) : $category;
                if (strpos(htmlspecialchars_decode($category), '&amp;')) {
                    $category = htmlspecialchars_decode($category);
                }
            } else {
                continue;
            }
            $price = $vo['price'];
            $countdown_id = $this->countdown_model->getInfoByProductId($countryinfo['country_code'], (string) $vo['_id']);
            if ($countdown_id) {
                $countdownInfo = $this->countdown_model->getInfoById($countdown_id);
                if (is_array($countdownInfo) && $countdownInfo['status'] == 2 && $countdownInfo['start'] < $time) {
                    $price = $this->countdown_model->getPrice($countdown_id, $vo['price']);
                }
            }
            $price = $price / 100;
            if ($vo['GF_enable'] == 1) {
                $combine = self::combine(array(explode(',',$vo['GF_color']), explode(',',$vo['GF_size'])));
                if (!empty($combine)) {
                    foreach ($combine as $k1 => $v1) {
                        $cs = explode('/', $v1);
                        $items .= '
                            <item>
                            <g:id>' . $vo['sku'].'/'.$v1 . '</g:id>
                            <title>' . htmlspecialchars(htmlspecialchars_decode($vo['title']),ENT_COMPAT) . '</title>
                            <link>' . $countryinfo['domain'] . '/products/' . $vo['seo_url'] . '</link>
                            <g:mpn>' . (string) $vo['_id'] . '</g:mpn>
                            <g:price>' . $price . ' ' . $countryinfo['currency_payment'] . '</g:price>
                            <g:online_only>y</g:online_only>
                            <description>' . htmlspecialchars(htmlspecialchars_decode($vo['seo']['description']),ENT_COMPAT) . '</description>
                            <g:condition>new</g:condition>
                            <g:google_product_category>' . $category . '</g:google_product_category>
                            <g:product_type>' . $category . '</g:product_type>
                            <g:image_link>' . $imagehost . $vo['image'] . '</g:image_link>
                            <g:availability>in stock</g:availability>
                            <g:brand>DrGrab</g:brand>
                            <g:age_group>' . $vo['GF_agegroup'] . '</g:age_group>
                            <g:gender>' . $vo['GF_gender'] . '</g:gender>
                            <g:color>' . $cs[0] . '</g:color>
                            <g:size>' . $cs[1] . '</g:size>
                            <g:shipping>
                              <g:country>' . $countryinfo['country_code'] . '</g:country>
                              <g:price>0.00 ' . $countryinfo['currency_payment'] . '</g:price>
                            </g:shipping>
                            </item>';
                    }
                } else {
                    $items .= '
                        <item>
                        <g:id>' . $vo['sku'] . '</g:id>
                        <title>' . htmlspecialchars(htmlspecialchars_decode($vo['title']),ENT_COMPAT) . '</title>
                        <link>' . $countryinfo['domain'] . '/products/' . $vo['seo_url'] . '</link>
                        <g:mpn>' . (string) $vo['_id'] . '</g:mpn>
                        <g:price>' . $price . ' ' . $countryinfo['currency_payment'] . '</g:price>
                        <g:online_only>y</g:online_only>
                        <description>' . htmlspecialchars(htmlspecialchars_decode($vo['seo']['description']),ENT_COMPAT) . '</description>
                        <g:condition>new</g:condition>
                        <g:google_product_category>' . $category . '</g:google_product_category>
                        <g:product_type>' . $category . '</g:product_type>
                        <g:image_link>' . $imagehost . $vo['image'] . '</g:image_link>
                        <g:availability>in stock</g:availability>
                        <g:brand>DrGrab</g:brand>
                        <g:shipping>
                          <g:country>' . $countryinfo['country_code'] . '</g:country>
                          <g:price>0.00 ' . $countryinfo['currency_payment'] . '</g:price>
                        </g:shipping>
                        </item>';
                }
            } else {
                $items .= '
                    <item>
                    <g:id>' . $vo['sku'] . '</g:id>
                    <title>' . htmlspecialchars(htmlspecialchars_decode($vo['title']),ENT_COMPAT) . '</title>
                    <link>' . $countryinfo ['domain'] . '/products/' . $vo['seo_url'] . '</link>
                    <g:mpn>' . (string) $vo['_id'] . '</g:mpn>
                    <g:price>' . $price . ' ' . $countryinfo['currency_payment'] . '</g:price>
                    <g:online_only>y</g:online_only>
                    <description>' . htmlspecialchars(htmlspecialchars_decode($vo['seo']['description']),ENT_COMPAT) . '</description>
                    <g:condition>new</g:condition>
                    <g:google_product_category>' . $category . '</g:google_product_category>
                    <g:product_type>' . $category . '</g:product_type>
                    <g:image_link>' . $imagehost . $vo['image'] . '</g:image_link>
                    <g:availability>in stock</g:availability>
                    <g:brand>DrGrab</g:brand>
                    <g:shipping>
                      <g:country>' . $countryinfo['country_code'] . '</g:country>
                      <g:price>0.00 ' . $countryinfo['currency_payment'] . '</g:price>
                    </g:shipping>
                    </item>';
            }
        }
        header("Content-type:text/xml");
        echo $head . $items . $foot;
    }

    function getcate($country) {
        $this->load->model('product_model');
        return $this->product_model->erp_sku($country);
    }
    
    private static function combine($arr,$prefix = ''){
        if(empty($arr)){
            return array();
        }
        $new_arr = array_shift($arr);
        foreach($arr as $v){
            $temp = $new_arr;
            $step = count($new_arr);
            for($i=1; $i<count($v); $i++){
                $new_arr = array_merge($new_arr, $temp);
            }
            for($i=0; $i<count($new_arr); $i++){
                $new_arr[$i] = $new_arr[$i].'/'. $v[$i / $step];
            }
        }
        if($prefix&&!empty($new_arr)){
            foreach ($new_arr as $k => $v){
                $new_arr[$k] = $prefix.$v;
            }
        }
        if(!is_array($new_arr))$new_arr = array();
        return $new_arr;
    }

}

?>
