<?php

/**
 * @文件： funs_helper
 * @后缀： php
 * @时间： 2012-5-4 14:17:44
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明： 函数库
 */

function CreatOrderNumber($incr) {
//    $CustomerIDKey = 'CustomerIDInc';
//    $incr = $this->redis->deinc($CustomerIDKey);
    $CustomerID = date('y') . str_pad(date('W'), 2, '0', STR_PAD_LEFT) . date('N') . str_pad(time() - strtotime(date('Y-m-d')), 5, '0', STR_PAD_LEFT) . str_pad($incr, 2, '0', STR_PAD_LEFT);
    return $CustomerID;
}

function CreatCustomerID() {
    $CustomerIDKey = 'CustomerIDInc';
    $incr = $this->redis->deinc($CustomerIDKey);
    $CustomerID = date('y') . str_pad(date('W'), 2, '0', STR_PAD_LEFT) . date('N') . str_pad(time() - strtotime(date('Y-m-d')), 5, '0', STR_PAD_LEFT) . str_pad($incr, 2, '0', STR_PAD_LEFT);
    return $CustomerID;
}

function cut_str($sourcestr, $cutlength, $ellipsis) {
    $returnstr = '';
    $i = 0;
    $n = 0;
    $str_length = strlen($sourcestr); //字符串的字节数
    while (($n < $cutlength) and ( $i <= $str_length)) {
        $temp_str = substr($sourcestr, $i, 1);
        $ascnum = Ord($temp_str); //得到字符串中第$i位字符的ascii码
        if ($ascnum >= 224) {    //如果ASCII位高与224，
            $returnstr = $returnstr . substr($sourcestr, $i, 3); //根据UTF-8编码规范，将3个连续的字符计为单个字符
            $i = $i + 3;            //实际Byte计为3
            $n++;            //字串长度计1
        } elseif ($ascnum >= 192) { //如果ASCII位高与192，
            $returnstr = $returnstr . substr($sourcestr, $i, 2); //根据UTF-8编码规范，将2个连续的字符计为单个字符
            $i = $i + 2;            //实际Byte计为2
            $n++;            //字串长度计1
        } elseif ($ascnum >= 65 && $ascnum <= 90) { //如果是大写字母，
            $returnstr = $returnstr . substr($sourcestr, $i, 1);
            $i = $i + 1;            //实际的Byte数仍计1个
            $n++;            //但考虑整体美观，大写字母计成一个高位字符
        } else {                //其他情况下，包括小写字母和半角标点符号，
            $returnstr = $returnstr . substr($sourcestr, $i, 1);
            $i = $i + 1;            //实际的Byte数计1个
            $n = $n + 0.5;        //小写字母和半角标点等与半个高位字符宽...
        }
    }
    if ($str_length > $cutlength) {
        if ($ellipsis)
            $returnstr = $returnstr . "……"; //超过长度时在尾处加上省略号
    }
    return $returnstr;
}

function my_replace($str) {
    $str = str_replace('&', '-', $str);
    $str = str_replace(' ', '_', $str);
    $str = str_replace('\'', '~', $str);
    return $str;
}

function my_unreplace($str) {
    $str = str_replace('-', '&', $str);
    $str = str_replace('_', ' ', $str);
    $str = str_replace('~', '\'', $str);
    return $str;
}


function combine($arr,$prefix = ''){
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
            $new_arr[$i] .= '/'. $v[$i / $step];
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

function arr_sort($array, $key, $order = "asc") {//asc是升序 desc是降序
    $arr_nums = $arr = array();
    if(empty($array)){
        return array();
    }
    foreach ($array as $k => $v) {
        $arr_nums[$k] = isset($v[$key])?$v[$key]:0;
    }
    if ($order == 'asc') {
        asort($arr_nums);
    } else {
        arsort($arr_nums);
    }
    foreach ($arr_nums as $k => $v) {
        $arr[$k] = $array[$k];
    }
    return $arr;
}
