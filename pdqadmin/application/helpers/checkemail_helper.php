<?php

/**
 * @文件： checkEmail_helper
 * @后缀： php
 * @时间： 2012-5-4 14:17:44
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明： 函数库
 */
function checkEmail($email) {
    
return filter_var($email, FILTER_VALIDATE_EMAIL);
    
//    $pattern = "/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$";
//    return preg_match($pattern, $email);
}
