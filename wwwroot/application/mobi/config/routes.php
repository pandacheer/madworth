<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$route['default_controller'] = "home";
$route['404_override'] = 'home/showError404';
$route['product/(:any)'] = 'product/index/$1';
$route['products/(:any)'] = 'products/index/$1';
$route['pages/(:any)'] = 'pages/index/$1';

$route['collections/(:any)'] = 'collections/index/$1';
$route['collections/loadPage'] = 'collections/loadPage/$1';

$route['search/(:any)'] = 'search/index/$1';
$route['search/loadPage'] = 'search/loadPage/$1';