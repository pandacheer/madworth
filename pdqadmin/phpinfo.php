<?php

// $price = ceil((4300 + 36.2 * 100) / 0.6 / 4.63 *0.8);
//
//echo $price;exit;
//    echo date('m/d/Y H:i:s',1449384790);exit;
$time_zones = $timezone_identifiers = \DateTimeZone::listIdentifiers();
date_default_timezone_set('Asia/Shanghai'); //设置国家时区
 $time=time();
 echo date('Y-m-d H:i:s P');
   echo '<br>';
 date_default_timezone_set("UTC"); //设置国家时区
 $time=time();
 echo date('Y-m-d H:i:s P');
   echo '<br>';
date_default_timezone_set("PRC"); //设置国家时区
 echo date('Y-m-d H:i:s P');
  echo '<br>';
 date_default_timezone_set("America/New_York"); //设置国家时区
 $time=time();
 echo date('Y-m-d H:i:s P');
 echo '<br>';
 echo $time;
 //exit;
phpinfo();
?>