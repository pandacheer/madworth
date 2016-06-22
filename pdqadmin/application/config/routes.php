<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$route['default_controller'] = "home";
$route['404_override'] = '';
$route['ordersContent/(:num)'] = "ordersContent/index/$1";
$route['ordersRefundContent/(:num)'] = "ordersRefundContent/index/$1";
$route['orderTrackingContent/(:num)'] = "orderTrackingContent/index/$1";