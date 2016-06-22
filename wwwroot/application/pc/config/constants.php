<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);
//define('IMAGE_DOMAIN','//static.catchoftheworld.com:1234');
define('STATIC_DOMAIN', '//static.catchoftheworld.com:1234');
define('IMAGE_DOMAIN', '//static.pdq.com');
define('STATIC_HTTP_DOMAIN', 'http://static.catchoftheworld.com:1234');

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');



/* * **************************************************
  PayPal constants

  This is the configuration file for the samples.This file
  defines the parameters needed to make an API call.

  PayPal includes the following API Signature for making API
  calls to the PayPal sandbox:

  Called by CallerService.php.
 * ************************************************** */

define('PAYPAL_BUSINESS','paddy@drgrab.com');
define('API_USERNAME', 'paddy_api1.drgrab.com');
define('API_PASSWORD', '4UVKKT3TPHEES23J');
define('API_SIGNATURE', 'AqhFCyVCekmeJP2qwp2YKa6kRdafAU.fPus3Z3VoCZxULKo3.vji6t2S');

//define('API_USERNAME', 'impudd_api1.live.com');
//define('API_PASSWORD', '7SASNC9DDK3UZTG7');
//define('API_SIGNATURE', 'AFcWxV21C7fd0v3bYYYRCpSSRl31AXMlMmsC6YVZCvm5cuJrbEg.ITMR');

/**
  # Endpoint: this is the server URL which you have to connect for submitting your API request.
 */
define('API_ENDPOINT', 'https://api-3t.sandbox.paypal.com/nvp');

/*
  # Third party Email address that you granted permission to make api call.
 */
define('SUBJECT', '');
/* for permission APIs ->token, signature, timestamp  are needed
  define('AUTH_TOKEN',"4oSymRbHLgXZVIvtZuQziRVVxcxaiRpOeOEmQw");
  define('AUTH_SIGNATURE',"+q1PggENX0u+6vj+49tLiw9CLpA=");
  define('AUTH_TIMESTAMP',"1284959128");
 */
/**
  USE_PROXY: Set this variable to TRUE to route all the API requests through proxy.
  like define('USE_PROXY',TRUE);
 */
define('USE_PROXY', FALSE);
/**
  PROXY_HOST: Set the host name or the IP address of proxy server.
  PROXY_PORT: Set proxy port.

  PROXY_HOST and PROXY_PORT will be read only if USE_PROXY is set to TRUE
 */
define('PROXY_HOST', '127.0.0.1');
define('PROXY_PORT', '808');

/* Define the PayPal URL. This is the URL that the buyer is
  first sent to to authorize payment with their paypal account
  change the URL depending if you are testing on the sandbox
  or going to the live PayPal site
  For the sandbox, the URL is
  https://www.sandbox.paypal.com/webscr&cmd=_express-checkout&token=
  For the live site, the URL is
  https://www.paypal.com/webscr&cmd=_express-checkout&token=
 */
define('PAYPAL_URL', 'https://www.sandbox.paypal.com/webscr&cmd=_express-checkout&token=');


/**
  # Version: this is the API version in the request.
  # It is a mandatory parameter for each API request.
  # The only supported value at this time is 2.3
 */
//define('VERSION', '65.1');
define('VERSION', '109.0');
// Ack related constants
define('ACK_SUCCESS', 'SUCCESS');
define('ACK_SUCCESS_WITH_WARNING', 'SUCCESSWITHWARNING');


/* End of file constants.php */
/* Location: ./application/config/constants.php */
