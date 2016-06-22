<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Subscription_model extends CI_Model {

    protected $CI;

    public function __construct() {
        $this->CI = &get_instance();
    }

    public function insert($country, $email, $time) {
        $subscription = $this->CI->mongo->selectCollection($country . '_subscription');
        $return = $subscription->insert(array('_id' => $email, 'create_time' => $time, 'status' => 0));
        if ($return['ok']) {
            $dateTimePRC = new DateTime('@' . (time() + 28800), new DateTimeZone("PRC"));
            $redisKey = 'T:' . $dateTimePRC->format("Ymd") . ':' . $country . ':member';
            $this->redis->hashInc($redisKey, 'subscription', 1);
            $this->redis->timeOut($redisKey, 259200);
            return true;
        } else {
            return FALSE;
        }
    }

    public function checkMail($country, $email) {
        $subscription = $this->CI->mongo->selectCollection($country . '_subscription');
        return $subscription->findOne(array('_id' => $email));
    }

}
