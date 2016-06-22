<?php

/**
 * @文件： encryption_helper
 * @后缀： php
 * @时间： 2015-6-10 14:17:44
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：   加密函数库
 */
function createSalt() {
    $chars = [
        "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K",
        "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V",
        "W", "X", "Y", "Z", "*", "%", "#", "@", "1", "2", "3",
        "4", "5", "6", "7", "8", "9", "?", "a", "b", "c", "d",
        "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o",
        "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z"
    ];
    shuffle($chars);
    $salt = "";
    for ($i = 0; $i < 25; $i++) {
        $salt .= $chars[$i];
    }
    return $salt;
}

function encryption($pwd, $salt) {
    $options = [
        'cost' => 12,
        'salt' => $salt
    ];
    $password_hash = password_hash($pwd, PASSWORD_BCRYPT, $options);
    $md5 = '';
    $salt_arr = str_split($salt);
    for ($i = 1; $i < 25; $i++) {
        if ($i % 5 == 0) {
            $md5.=$salt_arr[$i];
        }
    }
    return md5($password_hash . $md5);
}
