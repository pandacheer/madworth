<?php

/**
 * @文件： common_helper
 * @后缀： php
 * @时间： 2015-7-21 14:17:44
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明： 全站页面通用函数库
 */

/**
 * 获取用户的真实ip地址
 * @return string
 */
//function get_client_ip() {
//    $headers = array('HTTP_X_REAL_FORWARDED_FOR', 'HTTP_X_FORWARDED_FOR', 'HTTP_CLIENT_IP', 'REMOTE_ADDR');
//    foreach ($headers as $h) {
//        $ip = $_SERVER[$h];
//        // 有些ip可能隐匿，即为unknown
//        if (isset($ip) && strcasecmp($ip, 'unknown')) {
//            break;
//        }
//    }
//    if ($ip) {
//        // 可能通过多个代理，其中第一个为真实ip地址
//        list($ip) = explode(', ', $ip, 2);
//    }
//    /* 如果是服务器自身访问，获取服务器的ip地址(该地址可能是局域网ip)
//      if ('127.0.0.1' == $ip){
//      $ip = $_SERVER['SERVER_ADDR'];
//      }
//     */
//    return $ip;
//}

function getIP() {
    $unknown = 'unknown';
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], $unknown)) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], $unknown)) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    /*
      处理多层代理的情况
      或者使用正则方式：$ip = preg_match("/[d.]
      {7,15}/", $ip, $matches) ? $matches[0] : $unknown;
     */
    if (false !== strpos($ip, ','))
        $ip = reset(explode(',', $ip));
    return $ip;
}

function RandProduct($array = array(), $begin = 0, $end = 19, $limit = 2) {
    if (empty($array))
        return;
    $count = count($array);
    if ($end > $count - 1) {
        $end = $count - 1;
    }
    $array = array_values($array);
    $rand_array = range($begin, $end);
    shuffle($rand_array);
    if (empty($rand_array))
        return;
    $rand_array = array_slice($rand_array, 0, $limit);
    $tmp = array();
    foreach ($rand_array as $v) {
        $tmp[] = $array[$v];
    }
    return $tmp;
}

function A($collections = array(), $navigation = array(), $diao = false, $t = '') {
    if (empty($collections) || empty($navigation))
        return;
    $collection = $collections[0];
    $title = $collections[1];
    static $array = array();
    static $f = false;
    foreach ($navigation as $k => $v) {
        preg_match("/{title:(.*?),/", $v['msg'], $vo);
        preg_match("/url:(.*?)}/", $v['msg'], $v1);
        $collection_name_array = explode('/', $v1[1]);
        $collection_name = end($collection_name_array);
        if (!$diao) {
            $t = $vo[1];
        }
        if (strtolower($collection) == strtolower($collection_name)) {
            $f = true;
            $array[$t][] = array($vo[1], $v1[1]);
        } else if (!$f && !empty($v['children'])) {
            $array[$t][] = array($vo[1], $v1[1]);
            A($collections, $v['children'], true, $t);
        }
        if ($f) {
            return end($array);
        }
    }
    if (!$f) {
        return array(array($title, '/collections/' . $collection));
    }
}
